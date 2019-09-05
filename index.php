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
  $app->get('/carrers/list', function ($request, $response, $args) {
    $rpta = '';
    $status = 200;
    try {
      $db = call_user_func($this->get('settings')['db']);
      $stmt = $db->prepare('SELECT * FROM carrers');
    	$stmt->execute();
    	$rpta = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
    }catch (Exception $e) {
      $status = 500;
      $rpta = array(
        'tipo_mensaje' => 'error',
        'mensaje' => array('Se ha producido un error en listar las carreras',
          $e->getMessage()
        )
      );
    }
    return $response->withStatus($status)->write($rpta);
  });
  // Run app
  $app->run();
