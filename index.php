<?php
  require 'vendor/autoload.php';

  // Create and configure Slim app
  $config = [
    'settings' => [
      'displayErrorDetails' => true,
      'addContentLengthHeader' => false,
      'db' =>  function(){
        $db = new PDO('sqlite:db/ubicaciones.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
      },
      'constants' => [
        'base_url' => 'http://localhost:8080/',
        'static_url' => 'http://localhost:8080/public/',]
      ,
    ]
  ];
  // Iniciar la aplicación Slim
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
  // Define app routes
  $app->get('/error/access/404', function ($request, $response, $args) {
    return $response->withStatus(404)->write('Error 404 pe');
  });
  $app->get('/hello/{name}', function ($request, $response, $args) {
    return $response->write("Hello " . $args['name']);
  });

  $app->get('/departamento/listar', function ($request, $response, $args) {
    $rpta = '';
    $status = 200;
    try {
      $db = call_user_func($this->get('settings')['db']);
      $stmt = $db->prepare('SELECT * FROM departamentos');
    	$stmt->execute();
    	$rpta = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }catch (Exception $e) {
      $status = 500;
      $rpta = array(
        'tipo_mensaje' => 'error',
        'mensaje' => array('Se ha producido un error en listar los departamentos',
          $e->getMessage()
        )
      );
    }
    return $response->withStatus($status)->write($rpta);
  });

  // Run app
  $app->run();
