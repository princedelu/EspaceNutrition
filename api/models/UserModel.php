<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class UserModel extends AbstractModel {

    protected $_id;
	protected $_email;
    protected $_password;
	protected $_nom;
	protected $_prenom;
	protected $_datenaissance;
	protected $_role;
	protected $_actif;
	protected $_token;

    public function __construct()
    {
        // Run parent method
        parent::__construct();

        // Do additional work if needed
    }

    public function setId($_id)
    {
        $this->_id = $_id;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setPassword($_password)
    {
        $this->_password = $_password;
        return $this;
    }

    public function getPassword()
    {
        return $this->_password;
    }

	public function setNom($_nom)
    {
        $this->_nom = $_nom;
        return $this;
    }

    public function getNom()
    {
        return $this->_nom;
    }

	public function setPrenom($_prenom)
    {
        $this->_prenom = $_prenom;
        return $this;
    }

    public function getPrenom()
    {
        return $this->_prenom;
    }

	public function setEmail($_email)
    {
        $this->_email = $_email;
        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
    }

	public function setDateNaissance($_datenaissance)
    {
        $this->_datenaissance = $_datenaissance;
        return $this;
    }

    public function getDateNaissance()
    {
        return $this->_datenaissance;
    }

	public function setRole($_role)
    {
        $this->_role = $_role;
        return $this;
    }

    public function getRole()
    {
        return $this->_role;
    }

	public function setActif($_actif)
    {
        $this->_actif = $_actif;
        return $this;
    }

    public function getActif()
    {
        return $this->_actif;
    }

	public function setToken($_token)
    {
        $this->_token = $_token;
        return $this;
    }

    public function getToken()
    {
        return $this->_token;
    }

    public function toArray()
    {
        return array (
            'id' => $this->getId(),
            'password' => $this->getPassword(),
			'nom' => $this->getNom(),
			 'prenom' => $this->getPrenom(),
			 'email' => $this->getEmail(),
			 'datenaissance' => $this->getDateNaissance(),
			 'role' => $this->getRole()
        );
    }

	/*
	* Récupération de tous les utilisateurs
	*/
    public function fetchAll()
    {
        $result = array();

		try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT id,email,role,nom, prenom,datenaissance,actif FROM utilisateurs");
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysql_error());
				$result=false;
			}else{
				while ($row = mysqli_fetch_assoc($mysql_result)) {
					array_push($result,$row);
				}
			}
		}
		catch(Exception $e)
		{
			$this->setError($e->getMessage());
		} 
		
		mysqli_free_result($mysql_result);
		$this->closeConnectionDatabase();

		return $result;
    }

    /**
     * Fetch one model from storage
     * @param $id
     * @return array
     */
    public function fetchOne($id)
    {
        
		$result = array();

		try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT id FROM utilisateurs where id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysql_error());
				$result=false;
			}else{
				while ($row = mysqli_fetch_assoc($mysql_result)) {
					array_push($result,$row);
				}
			}
		}catch(Exception $e)
		{
			$this->setError($e->getMessage());
		} 

		mysqli_free_result($mysql_result);
		$this->closeConnectionDatabase();

		return $result;
        
    }

	/**
     * Fetch one model from storage
     * @param $id
     * @return array
     */
    public function fetchOneByEmail()
    {
        
		$result = array();

		try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT * FROM utilisateurs where email='%s'",mysqli_real_escape_string($this->dblink,$this->getEmail()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysql_error());
				$result=false;
			}else{

				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows==1){
					$row = mysqli_fetch_assoc($mysql_result);
					$this->setId($row['ID']);
					$this->setNom($row['NOM']);
					$this->setPrenom($row['PRENOM']);
					$this->setEmail($row['EMAIL']);
					$this->setToken($row['TOKEN']);
					$result=true;
				}else{
					if ($num_rows==0){
						$this->setError("Aucun utilisateur existant pour cet email!");
					}else{
						$this->setError("Plusieurs utilisateurs existent pour cet email!");
					}
					$result=false;
				}
			}
		}catch(Exception $e)
		{
			$this->setError($e->getMessage());
		} 

		mysqli_free_result($mysql_result);
		$this->closeConnectionDatabase();

		return $result;
        
    }

    /**
     *
     * @return $this|bool
     */
    public function update()
    {
        if ($this->getId()) {

            if ($this->fetchOne($this->getId())) {

                if ($this->validate()) {

                    return $this->save();
                }
            }
            else {
                $this->setError('Impossible de recuperer un utilisateur');
                return false;
            }
        }
        else {
            $this->setError('Champ id manquant');
            return false;
        }
    }

    public function create()
    {
		$result = false;
        try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT id FROM utilisateurs where email='%s'",mysqli_real_escape_string($this->dblink,$this->getEmail()));

			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysql_error());
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows>0){
					$this->setError("Doublon");
					$result=false;
				}else{
					// Génération du token
					$this->setToken(JWT::randomStringBase64URLSafe(40));
					// Exécution des requêtes SQL
					$query=sprintf("INSERT INTO utilisateurs set email='%s',nom='%s',prenom='%s',role=%d,actif=false,token='%s'",mysqli_real_escape_string($this->dblink,$this->getEmail()),mysqli_real_escape_string($this->dblink,$this->getNom()),mysqli_real_escape_string($this->dblink,$this->getPrenom()),mysqli_real_escape_string($this->dblink,$this->getRole()),mysqli_real_escape_string($this->dblink,$this->getToken()));
		 
					$mysql_result = mysqli_query($this->dblink,$query);
					if (!$mysql_result){
						$this->setError(mysql_error());
						$result=false;
					}else{
						// Envoi du token à l'utilisateur
						$result = $this->sendMailToken();
					}
				}
			}
			
		}catch(Exception $e)
		{
			$this->setError($e);

		} 
	
		$this->closeConnectionDatabase();
   
		return $result; 
    }

    public function delete()
    {
		$result = false;

        if ($this->getId()) {

            if ($this->fetchOne($this->getId())) {

                try{
					$this->openConnectionDatabase();

					// Exécution des requêtes SQL
					$query=sprintf("DELETE FROM utilisateurs where id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));
		 
					$mysql_result = mysqli_query($this->dblink,$query);
					if (!$mysql_result){
						$this->setError(mysql_error());
						$result=false;
					}else{
						$result = true;
					}
				}catch(Exception $e)
				{
					$this->setError($e->getMessage());
				} 
				$this->closeConnectionDatabase();
            }
            else {
                $this->setError('L identifiant n existe pas');
                $result = false;
            }
        }
        else {
            $this->setError('Champ id manquant');
            $result = false;
        }
		return $result;
    }

	public function sendMailTokenByEmail(){
		
		if ($this->fetchOneByEmail()){
			 return $this->sendMailToken();
		}else{
			return false;
		}
	}

	private function sendMailToken(){
		$result = false;
		if ($this->ini_array['mail']['action']){
			$subject = "Votre compte sur http://www.espace-nutrition.fr";
			$message = '<html>
							<head>
								<title>Votre compte sur http://www.espace-nutrition.fr</title>
							</head>
							<body>
								Bonjour '.$this->getPrenom().' '.$this->getNom().',
								<br/>
								<br/>
								Voici le lien ci-dessous pour valider votre inscription sur le site <a href="http://www.espace-nutrition.fr">http://www.espace-nutrition.fr</a>
								<br/>
								<br/>
								<a href="http://espace-nutrition.fr/admin/login?token='.$this->getToken().'">Valider votre inscription</a>
								<br/>
								<br/>
								Cordialement,
								<br/>
								<br/>
								PS : Merci de ne pas r&eacute;pondre &agrave; ce mail. Aucune r&eacute;ponse ne vous sera faite
							</body>
						</html>';

			// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			// En-têtes additionnels
			$headers .= 'To: '.str_replace(',',' ',$this->getPrenom()).' '.str_replace(',',' ',$this->getNom()).' <'.$this->getEmail().'>' . "\r\n";
			$headers .= 'From: Espace Nutrition <contact@espace-nutrition.fr>' . "\r\n";

			//Envoi du mail
			try
			{
				$result = mail ($this->getEmail(),$subject,$message,$headers);
				if (!$result)
				{
					$this->setError("Erreur d'envoi de l'email");
				}
			}
			catch(Exception $e)
			{
				$this->setError($e);
			}
		}else{
			$result = true;
		}
		return $result;

	}

    protected function save()
    {
        //$_SESSION['teams'][$this->getId()] = $this->toArray();
        return $this;
    }

    protected function validate()
    {       
		$valid = true;

        if (! $this->getEmail()) {
            $this->setError('Login manquant');
            $valid = false;
        }

        if (! $this->getPassword()) {
            $this->setError('Mot de passe manquant');
            $valid = false;
        }

        return $valid;
    }

    public function authentificate()
    {		
		$result = true;

		if ($this->validate()){
			try{
				$this->openConnectionDatabase();

				// Exécution des requêtes SQL
				$query=sprintf("SELECT * FROM utilisateurs WHERE email='%s' AND password='%s' AND ACTIF=1",mysqli_real_escape_string($this->dblink,$this->getEmail()),mysqli_real_escape_string($this->dblink,md5($this->getEmail().$this->getPassword())));

			
				$mysql_result = mysqli_query($this->dblink,$query);
				if (!$mysql_result){
					$this->setError(mysql_error());
					$result=false;
				}else{

					$num_rows = mysqli_num_rows($mysql_result);
					if ($num_rows==1){
						$row = mysqli_fetch_assoc($mysql_result);

						$payload = array(
							"email" => $this->getEmail(),
							"role" => $row['role'],
							"iss" => "http://www.espace-nutrition.fr",
							"aud" => "Espace Nutrition",
							"iat" => time(),
							"exp" => time()+3600
						);

						$encoded = JWT::encode($payload, $this->ini_array['JWT']['privatekey'],'RS256');

						$result = array('value' => $encoded);
					}else{
						$this->setError("Identification impossible");
						$result=false;
					}
				}
			}catch(Exception $e)
			{
				$this->setError($e->getMessage());
				$result=false;
			} 
			mysqli_free_result($mysql_result);
			$this->closeConnectionDatabase();
		}else{
			$result=false;
		}
		return $result;	
	}

	public function changePassword()
    {		
		$result = true;

		if ($this->validate()){
			try{
				$this->openConnectionDatabase();

				// Exécution des requêtes SQL
				$query=sprintf("SELECT id FROM utilisateurs WHERE email='%s' AND token='%s'",mysqli_real_escape_string($this->dblink,$this->getEmail()),mysqli_real_escape_string($this->dblink,$this->getToken()));

			
				$mysql_result = mysqli_query($this->dblink,$query);
				if (!$mysql_result){
					$this->setError(mysql_error());
					$result=false;
				}else{

					$num_rows = mysqli_num_rows($mysql_result);
					if ($num_rows==1){
						$row = mysqli_fetch_assoc($mysql_result);

						$id_utilisateur = $row['id'];

						// Exécution des requêtes SQL
						$query=sprintf("UPDATE utilisateurs SET ACTIF=1, PASSWORD='%s' where ID=%d",mysqli_real_escape_string($this->dblink,md5($this->getEmail().$this->getPassword())),$id_utilisateur);
						
						$mysql_result = mysqli_query($this->dblink,$query);
						if (!$mysql_result){
							$this->setError(mysql_error());
							$result=false;
						}else{
							$result = $this->authentificate();
						}
						
					}else{
						$this->setError("Utilisateur non trouve");
						$result=false;
					}
				}
			}catch(Exception $e)
			{
				$this->setError($e->getMessage());
				$result=false;
			} 
			$this->closeConnectionDatabase();
		}else{
			$result=false;
		}
		return $result;	
	}
}
