<?php

/**
 * Load dependencies
 */
require '../vendor/autoload.php';
require 'models/AbstractModel.php';
require 'models/UserModel.php';

/**
 * Start application
 */
$app = new \Slim\Slim();;

/**
 * Start routing
 */
$app->post('/login', function () use ($app) {

    $requestJson = json_decode($app->request()->getBody(), true);
    $user = new UserModel();
    $user->setUsername($requestJson['username']);
    $user->setPassword($requestJson['password']);
    $result = $user->authentificate();
	if (!$result){
		$app->response()->body( json_encode($user->getError()) );
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/**
 * Launch application
 */
$app->run();
