<?php

class AbonnementModel extends AbstractModel {

    protected $_id;
	protected $_email;
	protected $_datedebut;
	protected $_datefin;
	protected $_type;

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


	public function setEmail($_email)
    {
        $this->_email = $_email;
        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
    }

	public function setDateDebut($_datedebut)
    {
        $this->_datedebut = $_datedebut;
        return $this;
    }

    public function getDateDebut()
    {
        return $this->_datedebut;
    }

	public function setDateFin($_datefin)
    {
        $this->_datefin = $_datefin;
        return $this;
    }

    public function getDateFin()
    {
        return $this->_datefin;
    }

	public function setType($_type)
    {
        $this->_type = $_type;
        return $this;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function toArray()
    {
        return array (
            'id' => $this->getId(),
			 'email' => $this->getEmail(),
			'datedebut' => $this->getDateDebut(),
			'datefin' => $this->getDateFin(),
			'type' => $this->getType()
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
			$query=sprintf("SELECT * FROM abonnements");
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysql_error());
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows!=0){
					$currentDate=date('Y-m-d');
					while ($row = mysqli_fetch_assoc($mysql_result)) {
						if ($row['DATEDEBUT']<=$currentDate && $row['DATEFIN']>=$currentDate){
							$row['ACTIF']=true;
						}else{
							$row['ACTIF']=false;
						}
						$row['DATEDEBUT'] = implode('-', array_reverse(explode('-', $row['DATEDEBUT'])));
						$row['DATEFIN'] = implode('-', array_reverse(explode('-', $row['DATEFIN'])));
						array_push($result,$row);
					}
					mysqli_free_result($mysql_result);
				}
			}
		}
		catch(Exception $e)
		{
			$this->setError($e->getMessage());
            $result=false;
		} 
		
		$this->closeConnectionDatabase();

		return $result;
    }

	/*
	* Récupération de tous les utilisateurs
	*/
    public function fetchAllByEmail()
    {
        $result = array();
		$currentDate = date("Ymd");

		try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT * FROM abonnements where email='%s'",mysqli_real_escape_string($this->dblink,$this->getEmail()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysql_error());
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows!=0){
                    $currentDate=date('Y-m-d');
					while ($row = mysqli_fetch_assoc($mysql_result)) {
                        if ($row['DATEDEBUT']<=$currentDate && $row['DATEFIN']>=$currentDate){
							$row['ACTIF']=true;
						}else{
							$row['ACTIF']=false;
						}
						$row['DATEDEBUT'] = implode('-', array_reverse(explode('-', $row['DATEDEBUT'])));
						$row['DATEFIN'] = implode('-', array_reverse(explode('-', $row['DATEFIN'])));
						array_push($result,$row);
					}
					mysqli_free_result($mysql_result);
				}
			}
		}
		catch(Exception $e)
		{
			$this->setError($e->getMessage());
            $result=false;
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
			$query=sprintf("SELECT * FROM abonnements where id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysql_error());
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows==1){
					$row = mysqli_fetch_assoc($mysql_result);
					$row['DATEDEBUT'] = implode('-', array_reverse(explode('-', $row['DATEDEBUT'])));
					$row['DATEFIN'] = implode('-', array_reverse(explode('-', $row['DATEFIN'])));
					$result = $row;
				}else{
					if ($num_rows==0){
						$this->setError("Aucun abonnement existant pour cet id!");
					}else{
						$this->setError("Plusieurs abonnements existent pour cet id!");
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
    public function isActifByEmail()
    {
        
		$result = array();

		try{
			$this->openConnectionDatabase();
			
			// Exécution des requêtes SQL
			$query=sprintf("SELECT * FROM abonnements where EMAIL='%s' and DATEDEBUT<=NOW() AND and DATEFIN>=NOW()",mysqli_real_escape_string($this->dblink,$this->getEmail()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysql_error());
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows>0){
					$result=true;
				}else{
					
					$this->setError("Aucun abonnement actif existant pour cet email!");
					$result=false;
				}
			}
		}catch(Exception $e)
		{
			$this->setError($e->getMessage());
		} 

		$this->closeConnectionDatabase();

		return $result;
        
    }

    /**
     *
     * @return $this|bool
     */
    public function update()
    {
		$result=true;
		return $result; 
    }

    public function create()
    {
		$result = false;
        try{
			$this->openConnectionDatabase();

			$dateDebut=mysqli_real_escape_string($this->dblink,implode('-', array_reverse(explode('-',$this->getDateDebut()))));
			$dateFin=mysqli_real_escape_string($this->dblink,implode('-', array_reverse(explode('-',$this->getDateFin()))));

			// Exécution des requêtes SQL
			$query=sprintf("SELECT * FROM abonnements where email='%s' and ((DATEDEBUT<'%s' AND and DATEFIN>='%s') OR (DATEDEBUT<='%s' AND and DATEFIN>'%s') OR (DATEDEBUT>'%s' AND and DATEFIN<'%s'))",mysqli_real_escape_string($this->dblink,$this->getEmail()),$dateDebut,$dateDebut,$dateFin,$dateFin,$dateDebut,$dateFin);

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
					$query=sprintf("INSERT INTO abonnement (email,datedebut,datefin,type) values ('%s','%s','%s',%d)",mysqli_real_escape_string($this->dblink,$this->getEmail()),$dateDebut,$dateFin,mysqli_real_escape_string($this->dblink,$this->getType()));
		 
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
					$query=sprintf("DELETE FROM abonnements where id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));
		 
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
                $this->setError('L abonnement n existe pas');
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
       return true;
    }

    protected function validate()
    {       
		$valid = true;

        if (! $this->getEmail()) {
            $this->setError('Email manquant');
            $valid = false;
        }

        return $valid;
    }
}
