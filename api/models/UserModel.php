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
        $this->_password = md5($this->getEmail().$_password);
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
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows!=0){
					while ($row = mysqli_fetch_assoc($mysql_result)) {
						array_push($result,$row);
					}
					mysqli_free_result($mysql_result);
				}
			}
		}
		catch(Exception $e)
		{
			$this->setError($e->getMessage());
		} 
		
		$this->closeConnectionDatabase();

		return $result;
    }

    /**
     * Fetch one model from storage
     * @param $id
     * @return array
     */
    public function fetchOne()
    {
        
		$result = false;

		try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT ID, EMAIL,NOM,PRENOM,DATENAISSANCE,ROLE,ACTIF FROM utilisateurs where id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows==1){
					$row = mysqli_fetch_assoc($mysql_result);
					$row['DATENAISSANCE'] = implode('-', array_reverse(explode('-', $row['DATENAISSANCE'])));
					$result = $row;
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
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{

				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows==1){
					$row = mysqli_fetch_assoc($mysql_result);
					$row['DATENAISSANCE']=implode('-', array_reverse(explode('-', $row['DATENAISSANCE'])));
					$result=$row;
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
		$result=false;
        if ($this->getId()) {

            if ($this->fetchOne()) {
				try{
					$this->openConnectionDatabase();
				
					// Exécution des requêtes SQL
					$query=sprintf("SELECT * FROM utilisateurs where email='%s'",mysqli_real_escape_string($this->dblink,$this->getEmail()));
		 
					$mysql_result = mysqli_query($this->dblink,$query);
					if (!$mysql_result){
						$this->setError(mysqli_error($this->dblink));
						$result=false;
					}else{

						$num_rows = mysqli_num_rows($mysql_result);
						if ($num_rows<=1){
							// Exécution des requêtes SQL

							$query=sprintf("UPDATE utilisateurs SET EMAIL='%s'",mysqli_real_escape_string($this->dblink,$this->getEmail()));
							if ($this->getNom() != ''){
								$query=$query.sprintf(" ,NOM='%s'",mysqli_real_escape_string($this->dblink,$this->getNom()));
							}
							if ($this->getPrenom() != ''){
								$query=$query.sprintf(" ,PRENOM='%s'",mysqli_real_escape_string($this->dblink,$this->getPrenom()));
							}
							if ($this->getDateNaissance() != ''){
								$query=$query.sprintf(" ,DATENAISSANCE='%s'",implode('-', array_reverse(explode('-', mysqli_real_escape_string($this->dblink,$this->getDateNaissance())))));
							}
							if ($this->getRole() != ''){
								$query=$query.sprintf(" ,ROLE=%d",mysqli_real_escape_string($this->dblink,$this->getRole()));
							}
							if ($this->getActif() != ''){
								$query=$query.sprintf(" ,ACTIF=%d",mysqli_real_escape_string($this->dblink,$this->getActif()));
							}
							if ($this->getPassword() != ''){
								$query=$query.sprintf(" ,PASSWORD='%s'",mysqli_real_escape_string($this->dblink,$this->getPassword()));
							}
							$query=$query.sprintf(" where ID=%d",mysqli_real_escape_string($this->dblink,$this->getId()));

							if ($num_rows==1){
								$row = mysqli_fetch_assoc($mysql_result);
								// Test si l'utilisateur trouvé est le celui modifié ou non
								// Si oui alors modification
								if ($row['ID'] == $this->getId()){
									$mysql_result = mysqli_query($this->dblink,$query);
									if (!$mysql_result){
										$this->setError(mysqli_error($this->dblink));
										$result=false;
									}else{
										$result = true;
									}
								}
								else{
									$this->setError("Doublon");
									$result=false;
								}
							}else{
								// Si l'email n'existe pas
								$mysql_result = mysqli_query($this->dblink,$query);
								if (!$mysql_result){
									$this->setError(mysqli_error($this->dblink));
									$result=false;
								}else{
									$result = true;
								}
							}
						}else{
							$this->setError("Plusieurs utilisateurs existent pour cet email!");
							
							$result=false;
						}
					}

					
				}catch(Exception $e)
				{
					$this->setError($e->getMessage());
				} 
				$this->closeConnectionDatabase();
 
            }
            else {
                $this->setError('Impossible de recuperer un utilisateur');
                $result=false;
            }
        }
        else {
            $this->setError('Champ id manquant');
            $result=false;
        }
		return $result; 
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
				$this->setError(mysqli_error($this->dblink));
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
					$query=sprintf("INSERT INTO utilisateurs (email,nom,prenom,role,actif,token,datenaissance) values ('%s','%s','%s',%d,false,'%s','%s')",mysqli_real_escape_string($this->dblink,$this->getEmail()),mysqli_real_escape_string($this->dblink,$this->getNom()),mysqli_real_escape_string($this->dblink,$this->getPrenom()),mysqli_real_escape_string($this->dblink,$this->getRole()),mysqli_real_escape_string($this->dblink,$this->getToken()),mysqli_real_escape_string($this->dblink,implode('-', array_reverse(explode('-', mysqli_real_escape_string($this->dblink,$this->getDateNaissance()))))));
		 
					$mysql_result = mysqli_query($this->dblink,$query);
					if (!$mysql_result){
						$this->setError(mysqli_error($this->dblink));
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

            if ($this->fetchOne()) {

                try{
					$this->openConnectionDatabase();

					// Exécution des requêtes SQL
					$query=sprintf("DELETE FROM utilisateurs where id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));
		 
					$mysql_result = mysqli_query($this->dblink,$query);
					if (!$mysql_result){
						$this->setError(mysqli_error($this->dblink));
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
		$result = $this->fetchOneByEmail();
		if ($result){
			$this->setNom($result['NOM']);
			$this->setPrenom($result['PRENOM']);
			$this->setToken($result['TOKEN']);
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
								<a href="http://www.espace-nutrition.fr/login?token='.$this->getToken().'">Valider votre inscription</a>
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
            $this->setError('Email manquant');
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
				$query=sprintf("SELECT * FROM utilisateurs WHERE email='%s' AND password='%s' AND ACTIF=1",mysqli_real_escape_string($this->dblink,$this->getEmail()),mysqli_real_escape_string($this->dblink,$this->getPassword()));

			
				$mysql_result = mysqli_query($this->dblink,$query);
				if (!$mysql_result){
					$this->setError(mysqli_error($this->dblink));
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
					$this->setError(mysqli_error($this->dblink));
					$result=false;
				}else{

					$num_rows = mysqli_num_rows($mysql_result);
					if ($num_rows==1){
						$row = mysqli_fetch_assoc($mysql_result);

						$id_utilisateur = $row['id'];

						// Exécution des requêtes SQL
						$query=sprintf("UPDATE utilisateurs SET ACTIF=1, PASSWORD='%s' where ID=%d",mysqli_real_escape_string($this->dblink,$this->getPassword()),$id_utilisateur);
						
						$mysql_result = mysqli_query($this->dblink,$query);
						if (!$mysql_result){
							$this->setError(mysqli_error($this->dblink));
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
