<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class RepasModel extends AbstractModel {

    protected $_id;
	protected $_email;
    protected $_repas;
    protected $_datemesure;
    protected $_heuremesure;
	protected $_commentaire;
    protected $_commentaireDiet;
    protected $_datestart;
    protected $_dateend;
    protected $_controleEmail;

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

    public function setRepas($_repas)
    {
        $this->_repas = $_repas;
        return $this;
    }

    public function getRepas()
    {
        return $this->_repas;
    }

    public function setDateMesure($_datemesure)
    {
        $this->_datemesure = $_datemesure;
        return $this;
    }

	public function getDateMesure()
    {
        return $this->_datemesure;
    }

    public function setHeureMesure($_heuremesure)
    {
        $this->_heuremesure = $_heuremesure;
        return $this;
    }

    public function getHeureMesure()
    {
        return $this->_heuremesure;
    }

    public function getDateEtHeureMesure($_dateheuremesure)
    {
        $result = array();
        $arrayDateHeure=explode(' ', $_dateheuremesure);
        $result['datemesure'] = implode('-', array_reverse(explode('-', $arrayDateHeure[0])));
        $arrayHeure=explode(':', $arrayDateHeure[1]);
        $result['heuremesure'] = $arrayHeure[0].':'.$arrayHeure[1];
        return $result;
    }

    public function setDateHeureMesure($_dateheuremesure)
    {
        $result=$this->getDateEtHeureMesure($_dateheuremesure);
        $this->_datemesure = $result['datemesure'];
        $this->_heuremesure =  $result['heuremesure'];
        return $this;
    }

    public function getDateHeureMesure()
    {
        return implode('-', array_reverse(explode('-', $this->_datemesure))).' '.$this->_heuremesure.':00';
    }

	public function setCommentaire($_commentaire)
    {
        $this->_commentaire = $_commentaire;
        return $this;
    }

    public function getCommentaire()
    {
        return $this->_commentaire;
    }

    public function setCommentaireDiet($_commentaireDiet)
    {
        $this->_commentaireDiet = $_commentaireDiet;
        return $this;
    }

    public function getCommentaireDiet()
    {
        return $this->_commentaireDiet;
    }

    public function setDateStart($_datestart)
    {
        $this->_datestart = $_datestart;
        return $this;
    }

    public function getDateStart()
    {
        return $this->_datestart;
    }

    public function setDateEnd($_dateend)
    {
        $this->_dateend = $_dateend;
        return $this;
    }

    public function getDateEnd()
    {
        return $this->_dateend;
    }

    public function setControleEmail($_controleEmail)
    {
        $this->_controleEmail = $_controleEmail;
        return $this;
    }

    public function getControleEmail()
    {
        return $this->_controleEmail;
    }

    public function toArray()
    {
        return array (
            'id' => $this->getId(),
			'email' => $this->getEmail(),
			'dateheuremesure' => $this->getDateHeureMesure(),
			'commentaire' => $this->getCommentaire(),
            'commentaireDiet' => $this->getCommentaireDiet(),
            'repas' => $this->getRepas()
        );
    }

	/*
	* Récupération de tous les repas
	*/
    public function fetchAll()
    {
        $result = array();

		try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT * FROM repas where DATEHEUREMESURE>='%s' AND DATEHEUREMESURE<'%s'",mysqli_real_escape_string($this->dblink,$this->getDateStart()),mysqli_real_escape_string($this->dblink,$this->getDateEnd()));

			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows!=0){
					while ($row = mysqli_fetch_assoc($mysql_result)) {
                        $resultTmp = $this->getDateEtHeureMesure($row['DATEHEUREMESURE']);
                        $row['DATEMESURE']=$resultTmp['datemesure'];
                        $row['HEUREMESURE']=$resultTmp['heuremesure'];
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
	* Récupération de toutes les mesures de poids d'un utilisateur
	*/
    public function fetchAllByEmail()
    {
        $result = array();

		try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT * FROM repas where email='%s' AND DATEHEUREMESURE>='%s' AND DATEHEUREMESURE<'%s'",mysqli_real_escape_string($this->dblink,$this->getEmail()),mysqli_real_escape_string($this->dblink,$this->getDateStart()),mysqli_real_escape_string($this->dblink,$this->getDateEnd()));
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows!=0){
					while ($row = mysqli_fetch_assoc($mysql_result)) {
                        $resultTmp = $this->getDateEtHeureMesure($row['DATEHEUREMESURE']);
                        $row['DATEMESURE']=$resultTmp['datemesure'];
                        $row['HEUREMESURE']=$resultTmp['heuremesure'];
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
	* Récupération de toutes les notifications admin
	*/
    public function fetchNotificationsAdmin()
    {
        $result = array();

		try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT * FROM repas where DATEHEUREMODIFICATION>DATEHEURECOMMENTAIREDIET");

			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows!=0){
					while ($row = mysqli_fetch_assoc($mysql_result)) {
                        $resultTmp = $this->getDateEtHeureMesure($row['DATEHEUREMESURE']);
                        $row['DATEMESURE']=$resultTmp['datemesure'];
                        $row['HEUREMESURE']=$resultTmp['heuremesure'];
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
			$query=sprintf("SELECT * FROM repas where id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows==1){
					$row = mysqli_fetch_assoc($mysql_result);
					$resultTmp = $this->getDateEtHeureMesure($row['DATEHEUREMESURE']);
                    $row['DATEMESURE']=$resultTmp['datemesure'];
                    $row['HEUREMESURE']=$resultTmp['heuremesure'];
					$result = $row;
				}else{
					if ($num_rows==0){
						$this->setError("Aucun repas existant pour cet id!");
					}else{
						$this->setError("Plusieurs repas existent pour cet id!");
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

            $repas=$this->fetchOne();
            if ($repas) {
                $continueUpdate=true;
                if ($this->getControleEmail()) {
                    if ($repas['EMAIL'] != $this->getEmail()){
                        $continueUpdate=false;
                    }
                }

                if ($continueUpdate){
				    try{
					    $this->openConnectionDatabase();

					    $query=sprintf("UPDATE repas SET REPAS='%s'",mysqli_real_escape_string($this->dblink,$this->getRepas()));
					
					    $query=$query.sprintf(" ,COMMENTAIRE='%s'",mysqli_real_escape_string($this->dblink,$this->getCommentaire()));
                        $query=$query.sprintf(" ,COMMENTAIREDIET='%s'",mysqli_real_escape_string($this->dblink,$this->getCommentaireDiet()));
                        
                        if ($repas['COMMENTAIREDIET']!=$this->getCommentaireDiet() && $this->getCommentaireDiet()!=""){
                            $query=$query." ,DATEHEURECOMMENTAIREDIET=NOW()";
                        }else{
                            $query=$query." ,DATEHEUREMODIFICATION=NOW()";
                        }                        
                        
						$query=$query.sprintf(" ,DATEHEUREMESURE='%s'",mysqli_real_escape_string($this->dblink,$this->getDateHeureMesure()));;

					    $query=$query.sprintf(" where ID=%d",mysqli_real_escape_string($this->dblink,$this->getId()));

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
                }
                else {
                    $this->setError('Vous pouvez pas mettre à jour un repas d un autre utilisateur');
                    $result=false;
                }
				$this->closeConnectionDatabase();
 
            }
            else {
                $this->setError('Impossible de recuperer le repas');
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

			$dateHeureMesure=$this->getDateHeureMesure();

            // Exécution des requêtes SQL
			$query=sprintf("SELECT * FROM utilisateurs where email='%s'",mysqli_real_escape_string($this->dblink,$this->getEmail()));

			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows==0){
					$this->setError("PbUser");
					$result=false;
				}else{
                    // Exécution des requêtes SQL
			        $query=sprintf("INSERT INTO repas (EMAIL,DATEHEUREMODIFICATION,DATEHEUREMESURE,REPAS,COMMENTAIRE,COMMENTAIREDIET) values ('%s',NOW(),'%s','%s','%s','%s')",mysqli_real_escape_string($this->dblink,$this->getEmail()),$dateHeureMesure,mysqli_real_escape_string($this->dblink,$this->getRepas()),mysqli_real_escape_string($this->dblink,$this->getCommentaire()),mysqli_real_escape_string($this->dblink,$this->getCommentaireDiet()));
         
			        $mysql_result = mysqli_query($this->dblink,$query);
			        if (!$mysql_result){
				        $this->setError($query);
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
					$query=sprintf("DELETE FROM repas where id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));
		 
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
                $this->setError('Le repas n existe pas');
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
