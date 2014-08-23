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

	if (isset($requestJson['email']) and isset($requestJson['password'])){
		$user->setEmail($requestJson['email']);
		$user->setPassword($requestJson['password']);
		$result = $user->authentificate();
		if (!$result){
			$app->response()->body($user->getError());
		    $app->response()->status( 401 );
		}else{
		  	$app->response()->body( json_encode( $result ));
		}
	}else{
		$result = false;
		$user->setError("Des champs manquent pour l'authentification");
	}
});

/***********************************************
modificationPassword
***********************************************/
$app->post('/modificationPassword', function () use ($app) {

	$requestJson = json_decode($app->request()->getBody(), true);
    $user = new UserModel();

    if (isset($requestJson['email']) and isset($requestJson['password']) and isset($requestJson['token'])){
		$user->setEmail($requestJson['email']);
		$user->setPassword($requestJson['password']);
		$user->setToken($requestJson['token']);
		$result = $user->changePassword();
		if (!$result){
			$app->response()->body($user->getError());
		    $app->response()->status( 401 );
		}else{
		  	$app->response()->body( json_encode( $result ));
		}
	}else{
		$result = false;
		$user->setError("Des champs manquent pour le changement de mot de passe");
	}
});

/***********************************************
modificationPassword
***********************************************/
$app->post('/sendMailToken', function () use ($app) {

	$requestJson = json_decode($app->request()->getBody(), true);
    $user = new UserModel();

    if (isset($requestJson['email'])){
		$user->setEmail($requestJson['email']);
		$result = $user->sendMailTokenByEmail();
		if (!$result){
			$app->response()->body($user->getError());
		    $app->response()->status( 401 );
		}else{
		  	$app->response()->body( json_encode( $result ));
		}
	}else{
		$result = false;
		$user->setError("Des champs manquent pour l envoi du mail de renouvellement de mot de passe");
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

/***********************************************
Suppression utilisateurs
***********************************************/
$app->delete('/utilisateur/:id', function ($id) use ($app) {
	$user = new UserModel();
	$user->setId($id);
	$result = $user->delete();
	if (!$result){
		$app->response()->body($user->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Ajout utilisateurs
***********************************************/
$app->put('/utilisateur', function () use ($app) {
	$requestJson = json_decode($app->request()->getBody(), true);
    $user = new UserModel();
	
	if (isset($requestJson['nom']) and isset($requestJson['prenom']) and isset($requestJson['email']) and isset($requestJson['datenaissance']) and isset($requestJson['role'])){
		$user->setNom($requestJson['nom']);
		$user->setPrenom($requestJson['prenom']);
		$user->setEmail($requestJson['email']);
		$user->setDateNaissance($requestJson['datenaissance']);
		$user->setRole($requestJson['role']);

		$result = $user->create();
	}else{
		$result = false;
		$user->setError("Des champs manquent pour la création de l'utilisateur");
	}
    
	if (!$result){
		$app->response()->body($user->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});


/**
 * Launch application
 */
$app->run();
