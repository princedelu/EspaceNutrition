<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

/**
 * Load dependencies
 */
require 'vendor/autoload.php';
require 'tools/JWT.php';
require 'models/AbstractModel.php';
require 'models/UserModel.php';
require 'tools/AuthMiddleware.php';

/**
 * Start application
 */
$app = new \Slim\Slim();
$app->add(new \AuthMiddleware());

/**
 * Start routing
 */

/***********************************************
Login
***********************************************/
$app->post('/login', function () use ($app) {

    $requestJson = json_decode($app->request()->getBody(), true);
    $user = new UserModel();
    $user->setUsername($requestJson['username']);
    $user->setPassword($requestJson['password']);
    $result = $user->authentificate();
	if (!$result){
		$app->response()->body(json_encode($user->getError()));
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Logout
***********************************************/
$app->post('/logout', function () use ($app) {
	$app->response()->body( json_encode("OK"));
});

/***********************************************
Utilisateurs
***********************************************/
$app->get('/utilisateurs', function () use ($app) {
	$user = new UserModel();
	$app->response()->body( json_encode($user->fetchAll()));
});


/**
 * Launch application
 */
$app->run();
