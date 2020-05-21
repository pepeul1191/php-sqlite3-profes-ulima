<?php

require 'vendor/autoload.php';

// Create and configure Slim app
$config = [
  'settings' => [
    'displayErrorDetails' => true,
    'addContentLengthHeader' => false,
    'db' =>  function(){
      $db = new PDO('sqlite:db/profes.db');
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $db;
    },
    'db2' =>  function(){
      $db = new PDO('sqlite:db/pokemons.db');
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $db;
    },
    'constants' => [
      'base_url' => 'http://localhost:8080/',
      'static_url' => 'http://localhost:8080/public/',]
    ,
    'renderer' => [
      'template_path' => __DIR__,
    ],
  ]
];
// Iniciar la instancia de la aplicación Slim
$app = new \Slim\App($config);
// CORS
$app->add(function ($req, $res, $next) {
  $response = $next($req, $res);
  return $response
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});
// Container para el error 404
$container = $app->getContainer();
$container['notFoundHandler'] = function ($c) {
return function ($request, $response) use ($c) {
  /**/
    $method = $request->getMethod();
    if($method == 'GET'){
      return $response->withRedirect($c->get('settings')['constants']['base_url'] . 'error/access/404');
    }else{
      $rpta = json_encode(
        [
          'tipo_mensaje' => 'error',
          'mensaje' => [
            'Recurso no disponible',
            'Error 404'
          ]
        ]
      );
      return $c['response']
        ->withStatus(404)
        ->withHeader('Allow', implode(', ', $methods))
        ->withHeader('Content-type', 'text/html')
        ->write($rpta);
    }
  };
};
$container['renderer'] = function ($c) {
  $settings = $c->get('settings')['renderer'];
  return new Slim\Views\PhpRenderer($settings['template_path']);
};
// Define app routes
$app->get('/', function ($request, $response, $args) {
  return $this->renderer->render($response, '/public/index.html');
});
$app->get('/error/access/404', function ($request, $response, $args) {
  //return $response->withStatus(404)->write('Error 404 pe');
  return $this->renderer->render($response, '/public/error404.html');
});
// routes
$app->get('/hello/{name}', function ($request, $response, $args) {
  return $response->write('Hello ' . $args['name']);
});
// profes
$app->get('/carrer/list', function ($request, $response, $args) {
  $rpta = '';
  $status = 200;
  try {
    $db = call_user_func($this->get('settings')['db']);
    $stmt = $db->prepare('SELECT * FROM carrers');
    $stmt->execute();
    $rpta = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
  }catch (Exception $e) {
    $status = 500;
    $rpta = json_encode(array(
      'Se ha producido un error en listar las carreras',
      $e->getMessage()
    ), JSON_UNESCAPED_UNICODE);
  }
  return $response->withStatus($status)->write($rpta)->withHeader('Content-type', 'text/html');
});
$app->get('/teacher/carrer/{carrer_id}', function ($request, $response, $args) {
  $rpta = '';
  $status = 200;
  try {
    $db = call_user_func($this->get('settings')['db']);
    $sql = '
      SELECT T.id AS id, T.names, T.last_names, T.img
      FROM teachers T
      INNER JOIN teachers_carrers TC ON TC.teacher_id = T.id
      INNER JOIN carrers C ON TC.carrer_id = C.id
      WHERE TC.carrer_id = :carrer_id
      GROUP BY T.id;
    ';
    $stmt = $db->prepare($sql);
    $stmt->execute(
      array(
        ':carrer_id' => intval($args['carrer_id']),
      )
    );
    $rpta = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
    if($rpta == '[]'){
      $status = 404;
      $rpta = 'Carrera no registrada';
    }
  }catch (Exception $e) {
    $status = 500;
    $rpta = json_encode(array(
      'Se ha producido un error en listar los profesores de la carrera',
      $e->getMessage()
    ), JSON_UNESCAPED_UNICODE);
  }
  return $response->withStatus($status)->write($rpta)->withHeader('Content-type', 'text/html');
});
$app->get('/teacher/carrer/{carrer_id}/search', function ($request, $response, $args) {
  $rpta = '';
  $status = 200;
  try {
    $db = call_user_func($this->get('settings')['db']);
    $sql = '
      SELECT T.id AS id, T.names, T.last_names, T.img
      FROM teachers T
      INNER JOIN teachers_carrers TC ON TC.teacher_id = T.id
      INNER JOIN carrers C ON TC.carrer_id = C.id
      WHERE TC.carrer_id = :carrer_id AND T.names LIKE :names OR T.last_names LIKE :names
      GROUP BY T.id
      ORDER BY T.id;
    ';
    $stmt = $db->prepare($sql);
    $stmt->execute(
      array(
        ':carrer_id' => intval($args['carrer_id']),
        ':names' => '%' . strtoupper($request->getParam('name')) . '%',
      )
    );
    $rpta = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
    if($rpta == '[]'){
      $status = 404;
      $rpta = 'No se ha encontrado profesores con dicho critero de búsqueda';
    }
  }catch (Exception $e) {
    $status = 500;
    $rpta = json_encode(array(
      'Se ha producido un error en buscar los profesores de la carrera',
      $e->getMessage()
    ), JSON_UNESCAPED_UNICODE);
  }
  return $response->withStatus($status)->write($rpta)->withHeader('Content-type', 'text/html');
});
// pokemones
$app->get('/pokemon/type/list', function ($request, $response, $args) {
  $rpta = '';
  $status = 200;
  try {
    $db = call_user_func($this->get('settings')['db2']);
    $stmt = $db->prepare('SELECT type_1 AS type FROM pokemons
                          GROUP BY (type_1) ORDER BY type;
                        ');
    $stmt->execute();
    $rpta = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
  }catch (Exception $e) {
    $status = 500;
    $rpta = json_encode(array(
      'Se ha producido un error en listar los tipos de pokemones',
      $e->getMessage()
    ), JSON_UNESCAPED_UNICODE);
  }
  return $response->withStatus($status)->write($rpta)->withHeader('Content-type', 'text/html');
});
$app->get('/pokemon/search', function ($request, $response, $args) {
  $rpta = '';
  $status = 200;
  try {
    $db = call_user_func($this->get('settings')['db2']);
    $sql = '
      SELECT * FROM pokemons
      WHERE (type_1 = :type OR type_2 = :type) AND name LIKE :name
      ORDER BY number;
    ';
    $stmt = $db->prepare($sql);
    $stmt->execute(
      array(
        ':type' => $request->getParam('type'),
        ':name' => '%' . strtoupper($request->getParam('name')) . '%',
      )
    );
    $rpta = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
    if($rpta == '[]'){
      $status = 404;
      $rpta = 'No se ha encontrado pokemones con dicho critero de búsqueda';
    }
  }catch (Exception $e) {
    $status = 500;
    $rpta = json_encode(array(
      'Se ha producido un error en buscar los pokemones',
      $e->getMessage()
    ), JSON_UNESCAPED_UNICODE);
  }
  return $response->withStatus($status)->write($rpta)->withHeader('Content-type', 'text/html');
});
// Run app
$app->run();
