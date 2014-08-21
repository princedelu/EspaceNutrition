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
		}catch(Exception $e)
		{
			$this->setError($e->getMessage());
		} finally {
			mysqli_free_result($mysql_result);
			$this->closeConnectionDatabase();
		}

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
		} finally {
			mysqli_free_result($mysql_result);
			$this->closeConnectionDatabase();
		}

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
					// Exécution des requêtes SQL
					$query=sprintf("INSERT INTO utilisateurs set email='%s',nom='%s',prenom='%s',role=%d,actif=false,token='%s'",mysqli_real_escape_string($this->dblink,$this->getEmail()),mysqli_real_escape_string($this->dblink,$this->getNom()),mysqli_real_escape_string($this->dblink,$this->getPrenom()),mysqli_real_escape_string($this->dblink,$this->getRole()),"");
		 
					$mysql_result = mysqli_query($this->dblink,$query);
					if (!$mysql_result){
						$this->setError(mysql_error());
						$result=false;
					}else{
						$result = true;
					}
				}
			}
			
		}catch(Exception $e)
		{
			$this->setError("eee");

		} finally {
			$this->closeConnectionDatabase();
		}   
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
				} finally {
					$this->closeConnectionDatabase();
				}                
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
			} finally {
				mysqli_free_result($mysql_result);
				$this->closeConnectionDatabase();
			}
		}else{
			$result=false;
		}
		return $result;	
	}
}
