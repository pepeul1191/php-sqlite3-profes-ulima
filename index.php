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
    ]
  ];
  $c = new \Slim\Container();
  $app = new \Slim\App($config);

  // Define app routes
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
