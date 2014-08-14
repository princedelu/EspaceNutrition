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
		$app->response()->body( json_encode($user->getError()) );
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
	$headers = apache_request_headers();
  	if(isset($headers['Authorization'])){
		$authorization = explode(" ",$headers['Authorization']);
		if($authorization[0] == "Bearer"){
		  $result = $authorization[1];
		}
	}else{
		$result="OK";
	}
	$app->response()->body( json_encode($result));
});


/**
 * Launch application
 */
$app->run();
