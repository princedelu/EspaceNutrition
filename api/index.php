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
require 'models/PaiementModel.php';
require 'models/ContactModel.php';
require 'models/AbonnementModel.php';
require 'models/PoidsModel.php';
require 'models/RepasModel.php';
require 'models/ArticleModel.php';
require 'models/CategorieModel.php';
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
	$result = $user->fetchAll();
	if (!is_array($result)){
		$app->response()->body($user->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
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

/***********************************************
Update monprofil
***********************************************/
$app->post('/monprofil', function () use ($app) {
	$requestJson = json_decode($app->request()->getBody(), true);
    $user = new UserModel();
	
	if (isset($requestJson['email']) and isset($requestJson['id'])){
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
			$result = false;
			$user->setError("Modification de profil impossible");
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

/***********************************************
Notify paiement
***********************************************/
$app->post('/notifyPaiement', function () use ($app) {
    $paiement = new PaiementModel();
	$post = $app->request()->post();

	if (isset($post['txn_id']) && isset($post['txn_type'])){
		$paiement->setPost($post);

		$paiement->init();
		$result = $paiement->notify();
	}else{
		$result = false;
		$paiement->setError("Des champs manquent pour la validation d un paiement");
	}
    
	if (!$result){
		$app->response()->body($paiement->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Paiements
***********************************************/
$app->get('/paiements', function () use ($app) {
	$paiement = new PaiementModel();
	$result = $paiement->fetchAll();
	if (!is_array($result)){
		$app->response()->body($paiement->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Send Message
***********************************************/
$app->post('/sendMessage', function () use ($app) {
	$requestJson = json_decode($app->request()->getBody(), true);
	$contact = new ContactModel();

	if (isset($requestJson['email']) and isset($requestJson['nom'])){
		$contact->setNom($requestJson['nom']);
		$contact->setEmail($requestJson['email']);
		$contact->setTelephone($requestJson['telephone']);
		$contact->setMessage($requestJson['message']);
		$result = $contact->sendMessage();
	}else{
		$result = false;
	}
    
	if (!$result){
		$app->response()->body($contact->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Paiements
***********************************************/
$app->get('/abonnements', function () use ($app) {
	$abonnement = new AbonnementModel();
	$result = $abonnement->fetchAll();
	 if (!is_array($result)){
		$app->response()->body($abonnement->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Mes abonnements
***********************************************/
$app->get('/mesabonnements', function () use ($app) {

	try{
		$payload = JWT::getPayLoad();
	
		if (isset($payload->email)){
			$abonnement = new AbonnementModel();
            $abonnement->setEmail($payload->email);
	        $result = $abonnement->fetchAllByEmail();
	        if (!is_array($result)){
		        $app->response()->body($result);
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
Ajout abonnement
***********************************************/
$app->put('/abonnement', function () use ($app) {
	$requestJson = json_decode($app->request()->getBody(), true);
    $abonnement = new AbonnementModel();
	
	if (isset($requestJson['email']) and isset($requestJson['datedebut']) and isset($requestJson['datefin']) and isset($requestJson['type'])){
		$abonnement->setDateDebut($requestJson['datedebut']);
		$abonnement->setDateFin($requestJson['datefin']);
		$abonnement->setEmail($requestJson['email']);
		$abonnement->setType($requestJson['type']);

		$result = $abonnement->create();
	}else{
		$result = false;
		$abonnement->setError("Des champs manquent pour la création de l'abonnement");
	}
    
	if (!$result){
		$app->response()->body($abonnement->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Update utilisateurs
***********************************************/
$app->post('/abonnement', function () use ($app) {
	$requestJson = json_decode($app->request()->getBody(), true);
    $abonnement = new AbonnementModel();
	
	if (isset($requestJson['email']) and isset($requestJson['id'])){
		$abonnement->setEmail($requestJson['email']);
		$abonnement->setId($requestJson['id']);

		if (isset($requestJson['datedebut'])){
			$abonnement->setDateDebut($requestJson['datedebut']);
		}
		if (isset($requestJson['datefin'])){
			$abonnement->setDateFin($requestJson['datefin']);
		}
		if (isset($requestJson['type'])){
			$abonnement->setType($requestJson['type']);
		}
		
	    $result = $abonnement->update();
	}else{
		$result = false;
		$abonnement->setError("Des champs manquent pour la modification de l'abonnement");
	}
    
	if (!$result){
		$app->response()->body($abonnement->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Suppression abonnement
***********************************************/
$app->delete('/abonnement/:id', function ($id) use ($app) {
	$abonnement = new AbonnementModel();
	$abonnement->setId($id);
	$result = $abonnement->delete();
	if (!$result){
		$app->response()->body($abonnement->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Poids
***********************************************/
$app->get('/poids/:id', function ($id) use ($app) {
	$poidsModel = new PoidsModel();
    $poidsModel->setId($id);
    
    $result = $poidsModel->fetchOne();
    
	 if (!$result){
		$app->response()->body($poidsModel->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Poids
***********************************************/
$app->get('/monpoids/:id', function ($id) use ($app) {
    try{
		$payload = JWT::getPayLoad();
	
		if (isset($payload->email)){
	        $poidsModel = new PoidsModel();
            $poidsModel->setId($id);
            
            $result = $poidsModel->fetchOne();
            
	         if (!$result){
		        $app->response()->body($poidsModel->getError());
                $app->response()->status( 403 );
	        }else{
                if ($result['EMAIL'] != $payload->email){
                    $app->response()->body("Ceci n'est pas votre mesure de poids");
                    $app->response()->status( 403 );
                }else{
	          	    $app->response()->body( json_encode( $result ));
                }
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
Mesures
***********************************************/
$app->get('/mesures/:email/:dateStart/:dateEnd', function ($email,$dateStart,$dateEnd) use ($app) {
    // Recherche des informations sur les mesures de poids	
    $poidsModel = new PoidsModel();
    $poidsModel->setDateStart($dateStart);
    $poidsModel->setDateEnd($dateEnd);
    if ($email == "Tous"){
	    $resultPoids = $poidsModel->fetchAll();
    }else{
         $poidsModel->setEmail($email);
         $resultPoids = $poidsModel->fetchAllByEmail();
    }

    // Recherche des informations sur les repas	
    $repasModel = new RepasModel();
    $repasModel->setDateStart($dateStart);
    $repasModel->setDateEnd($dateEnd);
    if ($email == "Tous"){
	    $resultRepas = $repasModel->fetchAll();
    }else{
         $repasModel->setEmail($email);
         $resultRepas = $repasModel->fetchAllByEmail();
    }
    
    // Concaténation des deux résultats
	if (!is_array($resultPoids) || !is_array($resultRepas)){
        $result = false;
    }else{
        $result = array();
        foreach ($resultPoids as $value) {
            $valueTmp = $value;
            $valueTmp['TYPE']='POIDS';
            array_push($result,$valueTmp);
        }
        foreach ($resultRepas as $value) {
            $valueTmp = $value;
            $valueTmp['TYPE']='REPAS';
            array_push($result,$valueTmp);
        }
    }

    // Passage en JSON
	if (!is_array($result)){
		$app->response()->body('Poids : '.$poidsModel->getError().'<br/>Repas : '.$repasModel->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Mes mesures
***********************************************/
$app->get('/mesmesures/:dateStart/:dateEnd', function ($dateStart,$dateEnd) use ($app) {

	try{
		$payload = JWT::getPayLoad();
	
		if (isset($payload->email)){
			$poidsModel = new PoidsModel();
            $poidsModel->setDateStart($dateStart);
            $poidsModel->setDateEnd($dateEnd);
            $poidsModel->setEmail($payload->email);
	        $resultPoids = $poidsModel->fetchAllByEmail();

            // Recherche des informations sur les repas	
            $repasModel = new RepasModel();
            $repasModel->setDateStart($dateStart);
            $repasModel->setDateEnd($dateEnd);
            $repasModel->setEmail($payload->email);
            $resultRepas = $repasModel->fetchAllByEmail();

            // Concaténation des deux résultats
	        if (!is_array($resultPoids) || !is_array($resultRepas)){
                $result = false;
            }else{
                $result = array();
                foreach ($resultPoids as $value) {
                    $valueTmp = $value;
                    $valueTmp['TYPE']='POIDS';
                    array_push($result,$valueTmp);
                }
                foreach ($resultRepas as $value) {
                    $valueTmp = $value;
                    $valueTmp['TYPE']='REPAS';
                    array_push($result,$valueTmp);
                }
            }
            
	        if (!is_array($result)){
		        $app->response()->body($result);
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
Dernières mesures
***********************************************/
$app->get('/lastmesures', function () use ($app) {

	try{
		$payload = JWT::getPayLoad();
	
		if (isset($payload->email)){
			$poidsModel = new PoidsModel();
            $poidsModel->setDateStart('1970-01-01');
            $poidsModel->setDateEnd('9999-12-12');
            $poidsModel->setEmail($payload->email);
	        $resultPoids = $poidsModel->fetchAllByEmail();
            $resultPoidsDernier = end($resultPoids);

            // Recherche des informations sur les repas	
            $repasModel = new RepasModel();
            $repasModel->setDateStart('1970-01-01');
            $repasModel->setDateEnd('9999-12-12');
            $repasModel->setEmail($payload->email);
            $resultRepas = $repasModel->fetchAllByEmail();
            $resultRepasDernier = end($resultRepas);

            // Concaténation des deux résultats
	        if (!is_array($resultPoids) || !is_array($resultRepas)){
                $result = false;
            }else{
                $result = array();

                $resultPoidsDernier['TYPE']='POIDS';
                array_push($result,$resultPoidsDernier);
               
                $resultRepasDernier['TYPE']='REPAS';
                array_push($result,$resultRepasDernier);                
            }
            
	        if (!is_array($result)){
		        $app->response()->body($result);
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
Mon poids
***********************************************/
$app->get('/listpoids/:email', function ($email) use ($app) {

	try{
		$payload = JWT::getPayLoad();
	
		if (isset($payload->email) and isset($payload->role)){
			$poidsModel = new PoidsModel();
            $poidsModel->setDateStart('1970-01-01');
            $poidsModel->setDateEnd('9999-12-12');
            $poidsModel->setEmail($email);
            if ($email == $payload->email){
	            $result = $poidsModel->fetchAllByEmail();
            }else{
                if ($payload->role==2){
                    $result = $poidsModel->fetchAllByEmail();
                }else{
                    $result = false;
                    $poidsModel->setError('Vous ne pouvez pas lister les poids d une autre personne');
                }
            }
            
	        if (!is_array($result)){
		        $app->response()->body($poidsModel->getError());
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
Ajout mesure de poids
***********************************************/
$app->put('/poids', function () use ($app) {

    $requestJson = json_decode($app->request()->getBody(), true);
    $poidsModel = new PoidsModel();            

    if (isset($requestJson['email']) and isset($requestJson['dateMesure']) and isset($requestJson['poidsMesure'])){
        $poidsModel->setDateMesure($requestJson['dateMesure']);
        $poidsModel->setPoids($requestJson['poidsMesure']);
        $poidsModel->setEmail($requestJson['email']);
        $poidsModel->setCommentaire($requestJson['commentaireMesure']);

        $result = $poidsModel->create();
    }else{
        $result = false;
        $poidsModel->setError("Des champs manquent pour l'ajout du poids");
    }
    
    if (!$result){
        $app->response()->body($poidsModel->getError());
        $app->response()->status( 403 );
    }else{
      	$app->response()->body( json_encode( $result ));
    }
});

/***********************************************
Ajout mesure de poids
***********************************************/
$app->put('/monpoids', function () use ($app) {

    try{
		$payload = JWT::getPayLoad();
	
		if (isset($payload->email)){
            $poidsModel = new PoidsModel();  
            $abonnementModel = new AbonnementModel();
            $abonnementModel->setEmail($payload->email);
            if ($abonnementModel->isActifByEmail()){
	            $requestJson = json_decode($app->request()->getBody(), true);
	
	            if (isset($requestJson['dateMesure']) and isset($requestJson['poidsMesure'])){
		            $poidsModel->setDateMesure($requestJson['dateMesure']);
		            $poidsModel->setPoids($requestJson['poidsMesure']);
		            $poidsModel->setEmail($payload->email);
		            $poidsModel->setCommentaire($requestJson['commentaireMesure']);

		            $result = $poidsModel->create();
	            }else{
		            $result = false;
		            $poidsModel->setError("Des champs manquent pour l'ajout du poids");
	            }
            }else{
                 $result = false;
	             $poidsModel->setError("AbonnementInactif");
            }
            if (!$result){
	            $app->response()->body($poidsModel->getError());
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
Update poids
***********************************************/
$app->post('/poids', function () use ($app) {
	
	$requestJson = json_decode($app->request()->getBody(), true);
    $poidsModel = new PoidsModel();            

    if (isset($requestJson['id']) and isset($requestJson['dateMesure']) and isset($requestJson['poidsMesure'])){
        $poidsModel->setId($requestJson['id']);
        $poidsModel->setDateMesure($requestJson['dateMesure']);
        $poidsModel->setPoids($requestJson['poidsMesure']);
        $poidsModel->setCommentaire($requestJson['commentaireMesure']);
        $poidsModel->setControleEmail(false);
										
		$result = $poidsModel->update();
		
	}else{
		$result = false;
		$poidsModel->setError("Des champs manquent pour la modification de la mesure de poids");
	}
    
	if (!$result){
		$app->response()->body($poidsModel->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Update poids
***********************************************/
$app->post('/monpoids', function () use ($app) {
	
    try{
		$payload = JWT::getPayLoad();
	
		if (isset($payload->email)){

            $poidsModel = new PoidsModel();  
            $abonnementModel = new AbonnementModel();
            $abonnementModel->setEmail($payload->email);
            if ($abonnementModel->isActifByEmail()){
	            $requestJson = json_decode($app->request()->getBody(), true);       

                if (isset($requestJson['id']) and isset($requestJson['dateMesure']) and isset($requestJson['poidsMesure'])){
                    $poidsModel->setId($requestJson['id']);
                    $poidsModel->setEmail($payload->email);
                    $poidsModel->setDateMesure($requestJson['dateMesure']);
                    $poidsModel->setPoids($requestJson['poidsMesure']);
                    $poidsModel->setCommentaire($requestJson['commentaireMesure']);
                    $poidsModel->setControleEmail(true);
										
		            $result = $poidsModel->update();
		
	            }else{
		            $result = false;
		            $poidsModel->setError("Des champs manquent pour la modification de la mesure de poids");
	            }
                
            }else{
                $result = false;
                $poidsModel->setError("AbonnementInactif");
            }
            if (!$result){
                $app->response()->body($poidsModel->getError());
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
Repas
***********************************************/
$app->get('/repas/:id', function ($id) use ($app) {
	$repasModel = new RepasModel();
    $repasModel->setId($id);
    
    $result = $repasModel->fetchOne();
    
	 if (!$result){
		$app->response()->body($repasModel->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Repas
***********************************************/
$app->get('/monrepas/:id', function ($id) use ($app) {
    try{
		$payload = JWT::getPayLoad();
	
		if (isset($payload->email)){
	        $repasModel = new RepasModel();
            $repasModel->setId($id);
            
            $result = $repasModel->fetchOne();
            
	         if (!$result){
		        $app->response()->body($repasModel->getError());
                $app->response()->status( 403 );
	        }else{
                if ($result['EMAIL'] != $payload->email){
                    $app->response()->body("Ceci n'est pas votre repas");
                    $app->response()->status( 403 );
                }else{
	          	    $app->response()->body( json_encode( $result ));
                }
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
Ajout repas
***********************************************/
$app->put('/repas', function () use ($app) {

    $requestJson = json_decode($app->request()->getBody(), true);
    $repasModel = new RepasModel();            

    if (isset($requestJson['email']) and isset($requestJson['dateRepasMesure']) and isset($requestJson['heureRepasMesure']) and isset($requestJson['repasMesure'])){
        $repasModel->setDateMesure($requestJson['dateRepasMesure']);
        $repasModel->setHeureMesure($requestJson['heureRepasMesure']);
        $repasModel->setRepas($requestJson['repasMesure']);
        $repasModel->setEmail($requestJson['email']);
        $repasModel->setCommentaire($requestJson['commentaireRepasMesure']);
        $repasModel->setCommentaireDiet($requestJson['commentaireRepasDietMesure']);

        $result = $repasModel->create();
    }else{
        $result = false;
        $repasModel->setError("Des champs manquent pour l'ajout d un repas");
    }
    
    if (!$result){
        $app->response()->body($repasModel->getError());
        $app->response()->status( 403 );
    }else{
      	$app->response()->body( json_encode( $result ));
    }
});

/***********************************************
Ajout repas
***********************************************/
$app->put('/monrepas', function () use ($app) {

    try{
		$payload = JWT::getPayLoad();
	
		if (isset($payload->email)){
            $repasModel = new RepasModel();  
            $abonnementModel = new AbonnementModel();
            $abonnementModel->setEmail($payload->email);
            if ($abonnementModel->isActifByEmail()){
	            $requestJson = json_decode($app->request()->getBody(), true);
	
	            if (isset($requestJson['dateRepasMesure']) and isset($requestJson['heureRepasMesure']) and isset($requestJson['repasMesure'])){
		            $repasModel->setDateMesure($requestJson['dateRepasMesure']);
                    $repasModel->setHeureMesure($requestJson['heureRepasMesure']);
		            $repasModel->setRepas($requestJson['repasMesure']);
		            $repasModel->setEmail($payload->email);
		            $repasModel->setCommentaire($requestJson['commentaireRepasMesure']);
                    $repasModel->setCommentaireDiet('');

		            $result = $repasModel->create();
	            }else{
		            $result = false;
		            $repasModel->setError("Des champs manquent pour l'ajout du poids");
	            }
            }else{
                 $result = false;
	             $repasModel->setError("AbonnementInactif");
            }
            if (!$result){
	            $app->response()->body($repasModel->getError());
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
Update poids
***********************************************/
$app->post('/repas', function () use ($app) {
	
	$requestJson = json_decode($app->request()->getBody(), true);
    $repasModel = new RepasModel();            

    if (isset($requestJson['id']) and isset($requestJson['dateRepasMesure']) and isset($requestJson['heureRepasMesure']) and isset($requestJson['repasMesure'])){
        $repasModel->setId($requestJson['id']);
        $repasModel->setDateMesure($requestJson['dateRepasMesure']);
        $repasModel->setHeureMesure($requestJson['heureRepasMesure']);
        $repasModel->setRepas($requestJson['repasMesure']);
        $repasModel->setCommentaire($requestJson['commentaireRepasMesure']);
        $repasModel->setCommentaireDiet($requestJson['commentaireRepasDietMesure']);
        $repasModel->setControleEmail(false);
										
		$result = $repasModel->update();
		
	}else{
		$result = false;
		$repasModel->setError("Des champs manquent pour la modification du repas");
	}
    
	if (!$result){
		$app->response()->body($repasModel->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Update repas
***********************************************/
$app->post('/monrepas', function () use ($app) {
	
    try{
		$payload = JWT::getPayLoad();
	
		if (isset($payload->email)){

            $repasModel = new RepasModel();  
            $abonnementModel = new AbonnementModel();
            $abonnementModel->setEmail($payload->email);
            if ($abonnementModel->isActifByEmail()){
	            $requestJson = json_decode($app->request()->getBody(), true);       

                if (isset($requestJson['id']) and isset($requestJson['dateRepasMesure']) and isset($requestJson['heureRepasMesure']) and isset($requestJson['repasMesure']) ){
                    $repasModel->setId($requestJson['id']);
                    $repasModel->setEmail($payload->email);
                    $repasModel->setDateMesure($requestJson['dateRepasMesure']);
                    $repasModel->setHeureMesure($requestJson['heureRepasMesure']);
                    $repasModel->setRepas($requestJson['repasMesure']);
                    $repasModel->setCommentaire($requestJson['commentaireRepasMesure']);
                    $repasModel->setCommentaireDiet($requestJson['commentaireRepasDietMesure']);
                    $repasModel->setControleEmail(true);
										
		            $result = $repasModel->update();
		
	            }else{
		            $result = false;
		            $repasModel->setError("Des champs manquent pour la modification du repas");
	            }
                
            }else{
                $result = false;
                $repasModel->setError("AbonnementInactif");
            }
            if (!$result){
                $app->response()->body($repasModel->getError());
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
Suppression poids
***********************************************/
$app->delete('/poids/:id', function ($id) use ($app) {
     try{
		$payload = JWT::getPayLoad();
	
		if (isset($payload->email) and isset($payload->role)){
	        $poidsModel = new PoidsModel();
	        $poidsModel->setId($id);
            $poidsResult=$poidsModel->fetchOne();
            if ($poidsResult['EMAIL']==$payload->email){
	            $result = $poidsModel->delete();           
            }else{
                if ($payload->role==2){
                    $result = $poidsModel->delete();
                }else{
                    $result = false;
                    $poidsModel->setError("Vous ne pouvez pas supprimer le poids d'un autre utilisateur");
                }
            }

            if (!$result){
	            $app->response()->body($poidsModel->getError());
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
Suppression repas
***********************************************/
$app->delete('/repas/:id', function ($id) use ($app) {
     try{
		$payload = JWT::getPayLoad();
	
		if (isset($payload->email) and isset($payload->role)){
	        $repasModel = new RepasModel();
	        $repasModel->setId($id);
            $repasResult=$repasModel->fetchOne();
            if ($repasResult['EMAIL']==$payload->email){
	            $result = $repasModel->delete();           
            }else{
                if ($payload->role==2){
                    $result = $repasModel->delete();
                }else{
                    $result = false;
                    $repasModel->setError("Vous ne pouvez pas supprimer le poids d'un autre utilisateur");
                }
            }

            if (!$result){
	            $app->response()->body($repasModel->getError());
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
Notifications admin
***********************************************/
$app->get('/notificationsAdmin', function () use ($app) {
	$repasModel = new RepasModel();
	$result = $repasModel->fetchNotificationsAdmin();
	if (!is_array($result)){
		$app->response()->body($repasModel->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Notifications user
***********************************************/
$app->get('/notificationsUser', function () use ($app) {
    try{
		$payload = JWT::getPayLoad();
	
		if (isset($payload->email) and isset($payload->role)){
	        $repasModel = new RepasModel();
            $repasModel->setEmail($payload->email);
	        $result = $repasModel->fetchNotificationsUser();
	        if (!is_array($result)){
		        $app->response()->body($repasModel->getError());
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
Articles
***********************************************/
$app->get('/articles', function () use ($app) {
	$article = new ArticleModel();
	$rangeValue = $app->request()->params('range');
	$categorieValue = $app->request()->params('categorie');

	$article->setNbArticlesParPage(100);
	$article->setIdCategorie($categorieValue);

	try{
		$indexMin=0;
		$indexMax=$article->getNbArticlesParPage()-1;
		if(isset($rangeValue)){
			
			$arrayRange=explode('-', $rangeValue);
			if (count($arrayRange)==2){
				$indexMin=intval($arrayRange[0]);
				$indexMax=intval($arrayRange[1]);

				if ($indexMax<$indexMin || $indexMin<0){
					throw new Exception('Range non conforme');
				}else{
					if ($indexMax-$indexMin+1>$article->getNbArticlesParPage()){
						throw new Exception('Range trop large');
					}
				}
			}else{
				throw new Exception('La chaine range n est pas conforme');
			}	
		}

		$article->setIndexMinDem($indexMin);
		$article->setIndexMaxDem($indexMax);

		$result = $article->fetchAll();
		if (!is_array($result)){
			$app->response()->body($article->getError());
		    $app->response()->status( 403 );
		}else{
			$app->response()->header('Content-Range',strval($article->getIndexMin()).'-'.strval($article->getIndexMax()).' '.strval($article->getNbArticles()));
			$app->response()->header('Accept Range',"articles ".strval($article->getNbArticlesParPage()));
		  	$app->response()->body( json_encode( $result ));
		}
	}catch(Exception $e){
		$app->response()->body($e->getMessage());
        $app->response()->status( 400 );
	}
});

/***********************************************
Article
***********************************************/
$app->get('/articles/:id', function ($id) use ($app) {
	$articleModel = new ArticleModel();
    $articleModel->setId($id);
    
    $result = $articleModel->fetchOne();
    
	 if (!$result){
		$app->response()->body($articleModel->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Categories
***********************************************/
$app->get('/categories', function () use ($app) {
	$categorieModel = new CategorieModel();

	$result = $categorieModel->fetchAll();
    
	if (!$result){
		$app->response()->body($categorieModel->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Ajout articles
***********************************************/
$app->put('/articles', function () use ($app) {
	$requestJson = json_decode($app->request()->getBody(), true);
    $article = new ArticleModel();
	
	if (isset($requestJson['titre']) and isset($requestJson['auteur']) and isset($requestJson['date']) and isset($requestJson['partie1']) and isset($requestJson['partie2']) and isset($requestJson['id_categorie'])){
		$article->setTitre($requestJson['titre']);
		$article->setAuteur($requestJson['auteur']);
		$article->setDate($requestJson['date']);
		$article->setPartie1($requestJson['partie1']);
		$article->setPartie2($requestJson['partie2']);
		$article->setIdCategorie($requestJson['id_categorie']);

		$result = $article->create();
	}else{
		$result = false;
		$article->setError("Des champs manquent pour la création de l'article");
	}
    
	if (!$result){
		$app->response()->body($article->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Update articles
***********************************************/
$app->post('/articles', function () use ($app) {
	$requestJson = json_decode($app->request()->getBody(), true);
    $article = new ArticleModel();
	
	if (isset($requestJson['id'])){
		$article->setId($requestJson['id']);

		if (isset($requestJson['titre'])){
			$article->setTitre($requestJson['titre']);
		}
		if (isset($requestJson['auteur'])){
			$article->setAuteur($requestJson['auteur']);
		}
		if (isset($requestJson['date'])){
			$article->setDate($requestJson['date']);
		}
		if (isset($requestJson['partie1'])){
			$article->setPartie1($requestJson['partie1']);
		}
		if (isset($requestJson['partie2'])){
			$article->setPartie2($requestJson['partie2']);
		}
		if (isset($requestJson['id_categorie'])){
			$article->setIdCategorie($requestJson['id_categorie']);
		}
		
		$result = $article->update();

	}else{
		$result = false;
		$article->setError("Des champs manquent pour la modification de l'article");
	}
    
	if (!$result){
		$app->response()->body($article->getError());
		$app->response()->body($article->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/***********************************************
Suppression articles
***********************************************/
$app->delete('/articles/:id', function ($id) use ($app) {
	$article = new ArticleModel();
	$article->setId($id);
	$result = $article->delete();
	if (!$result){
		$app->response()->body($article->getError());
        $app->response()->status( 403 );
	}else{
	  	$app->response()->body( json_encode( $result ));
	}
});

/**
 * Launch application
 */
$app->run();
