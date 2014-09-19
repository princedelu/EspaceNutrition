<?php

class AuthMiddleware extends \Slim\Middleware
{
	protected $ini_array;

    public function __construct()
    {
		$this->ini_array = parse_ini_file("config.ini", true);
    }
    public function call()
    {
        //get a reference to application
        $app = $this->app;

		$callURL=explode("/",$app->request()->getPathInfo());
		$call=$_SERVER['REQUEST_METHOD']. '.' . $callURL[1];

		try{
			$role=$this->ini_array['api'][$call];
		}catch(Exception $e)
		{
			$role = 2;
		}

        //skip routes that are exceptionally allowed without an access token:
        if ($role==-1){
            $this->next->call();
        }else{
            if ($role==0){
                // Récupération du token
			    if(isset($_SERVER['REDIRECT_REMOTE_USER'])){
				    $authorization = explode(" ",$_SERVER['REDIRECT_REMOTE_USER']);
				    if($authorization[0] == "BearerPublic"){
					    $jwt = $authorization[1];
					    try{
						    $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($jwt));
						    if (isset($payload->role) && isset($payload->exp)){
							    if (($payload->role == "anonyme") && ($payload->exp>time())) {
								    $this->next->call();
							    }else{
								    $app->response->setStatus('403'); //Valeur du token incorrecte
								    $app->response->body("Droits insuffisants ou token expire");
								    return;
							    }
						    }else{
							    $app->response->setStatus('403'); //Valeur du token incorrecte
						        $app->response->body("TokenPublic invalid");
						        return;
						    }
					    }catch(Exception $e){
						    $app->response->setStatus('403'); //Token invalide
						    $app->response->body($e->getMessage());
						    return;
					    }
				    }else{
					    $app->response->setStatus('403'); //Pas de zone autorisation bearer
		                $app->response->body("Mauvais token public");
		                return;
				    }
                } else {
                    $app->response->setStatus('403'); //Pas de zone authorization
                    $app->response->body("Accès non autorisé");
                    return;
                }
            } else {
			    // Récupération du token
			    if(isset($_SERVER['REDIRECT_REMOTE_USER'])){
				    $authorization = explode(" ",$_SERVER['REDIRECT_REMOTE_USER']);
				    if($authorization[0] == "Bearer"){
					    $jwt = $authorization[1];
					    try{
						    $payload = JWT::decode($jwt,$this->ini_array['JWT']['publickey']);
						    if (isset($payload->role)){
							    if ($payload->role >= $role) {
								    $this->next->call();
							    }else{
								    $app->response->setStatus('403'); //Valeur du token incorrecte
								    $app->response->body("Droits insuffisants");
								    return;
							    }
						    }else{
							    $app->response->setStatus('403'); //Valeur du token incorrecte
						        $app->response->body("Token invalid");
						        return;
						    }
					    }catch(Exception $e){
						    $app->response->setStatus('403'); //Token invalide
						    $app->response->body($e->getMessage());
						    return;
					    }
				    }else{
					    $app->response->setStatus('403'); //Pas de zone autorisation bearer
		                $app->response->body("Mauvais token");
		                return;
				    }
                } else {
                    $app->response->setStatus('403'); //Pas de zone authorization
                    $app->response->body("Accès non autorisé");
                    return;
                }
		    }
        }
    }
}
