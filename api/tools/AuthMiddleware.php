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
        if ($role==0){
            $this->next->call(); //let go
        } else {
			// Récupération du token
			$headers = apache_request_headers();
			if(isset($headers['Authorization'])){
				$authorization = explode(" ",$headers['Authorization']);
				if($authorization[0] == "Bearer"){
					$jwt = $authorization[1];
					try{
						$payload = JWT::decode($jwt,$this->ini_array['JWT']['publickey']);
						if (isset($payload->role)){
							if ($payload->role >= $role) {
								$this->next->call();
							}else{
								$app->response->setStatus('403'); //Valeur du token incorrecte
								$app->response->body(json_encode(array("Error"=>"Droits insuffisants")));
								return;
							}
						}else{
							$app->response->setStatus('403'); //Valeur du token incorrecte
						    $app->response->body(json_encode(array("Error"=>"Token invalid")));
						    return;
						}
					}catch(Exception $e){
						$app->response->setStatus('403'); //Token invalide
						$app->response->body(json_encode(array("Error"=>$e->getMessage())));
						return;
					}
				}else{
					$app->response->setStatus('403'); //Pas de zone autorisation bearer
		            $app->response->body(json_encode(array("Error"=>"Mauvais token")));
		            return;
				}
            } else {
                $app->response->setStatus('403'); //Pas de zone authorization
                $app->response->body(json_encode(array("Error"=>"Acces non autorise")));
                return;
            }
		}
    }
}
