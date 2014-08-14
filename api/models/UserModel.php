<?php

class UserModel extends AbstractModel {

    protected $_id;
    protected $_username;
    protected $_password;

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

    public function setUsername($_username)
    {
        $this->_username = $_username;
        return $this;
    }

    public function getUsername()
    {
        return $this->_username;
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

    public function toArray()
    {
        return array (
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword()
        );
    }

    public function fetchAll()
    {
        return false;
    }

    /**
     * Fetch one model from storage
     * @param $id
     * @return array
     */
    public function fetchOne($id)
    {
        
	return false;
        
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
        if ($this->validate()) {

            // Auto-generate ID
            $this->setId(time());

            return $this->save();
        }
        else {
            return false;
        }
    }

    public function delete()
    {
        if ($this->getId()) {

            if ($this->fetchOne($this->getId())) {

                //unset($_SESSION['teams'][$this->getId()]);

                return true;
            }
            else {
                $this->setError('L identifiant n existe pas');
                return false;
            }
        }
        else {
            $this->setError('Champ id manquant');
            return false;
        }
    }

    protected function save()
    {
        //$_SESSION['teams'][$this->getId()] = $this->toArray();
        return $this;
    }

    protected function validate()
    {       
		$valid = true;

        if (! $this->getUsername()) {
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
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT * FROM utilisateurs WHERE username='%s' AND password='%s'",mysql_real_escape_string($this->getUsername()),mysql_real_escape_string(md5($this->getPassword())));

			$mysql_result = mysql_query($query);
			if (!$mysql_result){
				$this->setError(mysql_error());
				$result=false;
			}else{

				$num_rows = mysql_num_rows($mysql_result);
				if ($num_rows==1){
					$row = mysql_fetch_assoc($mysql_result);

					$payload = array(
						"message" => $this->getUsername(),
						"iss" => "http://www.espace-nutrition.fr",
						"aud" => "Espace Nutrition",
						"iat" => time(),
						"exp" => time()+3600
					);

					$encoded = JWT::encode($payload, $this->ini_array['JWT']['key']);

					$result = array('username' => $this->getUsername(), 'role' => $row['role'], 'token' => $encoded);
				}else{
					$this->setError("Identification impossible");
					$result=false;
				}
			}

			mysql_free_result($mysql_result);
			$this->closeConnectionDatabase();
				
		}else{
			$result=false;
		}
		return $result;	
	}
}
