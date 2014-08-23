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
Utilisateurs
***********************************************/
$app->get('/utilisateurs', function () use ($app) {
	$user = new UserModel();
	$app->response()->body( json_encode($user->fetchAll()));
});

/***********************************************
Utilisateurs
***********************************************/
$app->get('/utilisateur/:id', function ($id) use ($app) {
	$user = new UserModel();
	$user->setId($id);
	$result = $user->fetchOne();
	if (!$result){
		$app->response()->body($user->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Profil
***********************************************/
$app->get('/profil', function () use ($app) {

	try{
		$payload = JWT::getPayLoad();
	
		if (isset($payload->email)){
			$user = new UserModel();
			$user->setEmail($payload->email);
			$result = $user->fetchOneByEmail();
			if (!$result){
				$app->response()->body($user->getError());
				$app->response()->status( 403 );
			}else{
			  	$app->response()->body( json_encode( $result ));
			}
		}else{
			$app->response->setStatus('403'); //Valeur du token incorrecte
			$app->response->body("Token invalid");
		}
	}catch(Exception $e){
		$app->response->setStatus('403'); //Token invalide
		$app->response->body($e->getMessage());
	}
		
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

/***********************************************
Update utilisateurs
***********************************************/
$app->post('/utilisateur', function () use ($app) {
	$requestJson = json_decode($app->request()->getBody(), true);
    $user = new UserModel();
	
	if (isset($requestJson['email']) and isset($requestJson['id'])){
		$user->setEmail($requestJson['email']);
		$user->setId($requestJson['id']);

		if (isset($requestJson['nom'])){
			$user->setNom($requestJson['nom']);
		}
		if (isset($requestJson['prenom'])){
			$user->setPrenom($requestJson['prenom']);
		}
		if (isset($requestJson['datenaissance'])){
			$user->setDateNaissance($requestJson['datenaissance']);
		}
		if (isset($requestJson['role'])){
			$user->setRole($requestJson['role']);
		}
		if (isset($requestJson['actif'])){
			$user->setActif($requestJson['actif']);
		}
		if (isset($requestJson['password'])){
			$user->setPassword($requestJson['password']);
		}
		if (isset($requestJson['profil']) && $requestJson['profil'] == 1){
			try{
				$payload = JWT::getPayLoad();
	
				if (isset($payload->email)){
					if ($payload->email == $user->getEmail()){
						$result = $user->update();
					}else{
						$result = false;
						$user->setError("Ce n'est pas votre compte");
					}
				}else{
					$result = false;
					$user->setError("Modification de profil impossible");
				}
					
			}catch(Exception $e){
				$app->response->setStatus('403'); //Token invalide
				$app->response->body($e->getMessage());
			}
		}else{
			$result = $user->update();
		}
	}else{
		$result = false;
		$user->setError("Des champs manquent pour la modification de l'utilisateur");
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
