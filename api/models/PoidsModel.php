<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class PoidsModel extends AbstractModel {

    protected $_id;
	protected $_email;
    protected $_poids;
	protected $_datemesure;
	protected $_commentaire;
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

    public function setPoids($_poids)
    {
        $this->_poids = $_poids;
        return $this;
    }

    public function getPoids()
    {
        return $this->_poids;
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

	public function setCommentaire($_commentaire)
    {
        $this->_commentaire = $_commentaire;
        return $this;
    }

    public function getCommentaire()
    {
        return $this->_commentaire;
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
			'datemesure' => $this->getDateMesure(),
			'commentaire' => $this->getCommentaire()
        );
    }

	/*
	* Récupération de toutes les mesures de poids
	*/
    public function fetchAll()
    {
        $result = array();

		try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT * FROM poids where DATEMESURE>='%s' AND DATEMESURE<'%s'",mysqli_real_escape_string($this->dblink,$this->getDateStart()),mysqli_real_escape_string($this->dblink,$this->getDateEnd()));

			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows!=0){
					while ($row = mysqli_fetch_assoc($mysql_result)) {
						$row['DATEMESURE'] = implode('-', array_reverse(explode('-', $row['DATEMESURE'])));
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
			$query=sprintf("SELECT * FROM poids where email='%s' AND DATEMESURE>='%s' AND DATEMESURE<'%s' order by DATEMESURE",mysqli_real_escape_string($this->dblink,$this->getEmail()),mysqli_real_escape_string($this->dblink,$this->getDateStart()),mysqli_real_escape_string($this->dblink,$this->getDateEnd()));
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows!=0){
					while ($row = mysqli_fetch_assoc($mysql_result)) {
						$row['DATEMESURE'] = implode('-', array_reverse(explode('-', $row['DATEMESURE'])));
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
			$query=sprintf("SELECT * FROM poids where id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows==1){
					$row = mysqli_fetch_assoc($mysql_result);
					$row['DATEMESURE'] = implode('-', array_reverse(explode('-', $row['DATEMESURE'])));
					$result = $row;
				}else{
					if ($num_rows==0){
						$this->setError("Aucune mesure de poids existant pour cet id!");
					}else{
						$this->setError("Plusieurs mesures de poids existent pour cet id!");
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

            $mesurePoids=$this->fetchOne();
            if ($mesurePoids) {
                $continueUpdate=true;
                if ($this->getControleEmail()) {
                    if ($mesurePoids['EMAIL'] != $this->getEmail()){
                        $continueUpdate=false;
                    }
                }

                if ($continueUpdate){
				    try{
					    $this->openConnectionDatabase();
                        $dateMesure=mysqli_real_escape_string($this->dblink,implode('-', array_reverse(explode('-',$this->getDateMesure()))));

                        // Exécution des requêtes SQL
			            $query=sprintf("SELECT * FROM poids where email='%s' and DATEMESURE='%s'",$mesurePoids['EMAIL'],$dateMesure);

			            $mysql_result = mysqli_query($this->dblink,$query);
			            if (!$mysql_result){
				            $this->setError(mysqli_error($this->dblink));
				            $result=false;
			            }else{
				            $num_rows = mysqli_num_rows($mysql_result);
				            if ($num_rows>0 && $mesurePoids['DATEMESURE']!=$this->getDateMesure()){
					            $this->setError('Doublon');
					            $result=false;
				            }else{

					            $query=sprintf("UPDATE poids SET POIDS=%f",mysqli_real_escape_string($this->dblink,$this->getPoids()));
					
					            $query=$query.sprintf(" ,COMMENTAIRE='%s'",mysqli_real_escape_string($this->dblink,$this->getCommentaire()));

					            if ($this->getDateMesure() != ''){
						            $query=$query.sprintf(" ,DATEMESURE='%s'",$dateMesure);
					            }
					
					            $query=$query.sprintf(" where ID=%d",mysqli_real_escape_string($this->dblink,$this->getId()));

					            $mysql_result = mysqli_query($this->dblink,$query);
					            if (!$mysql_result){
						            $this->setError(mysqli_error($this->dblink));
						            $result=false;
					            }else{
						            $result = $query;
					            }
                            }
                        }
				    }catch(Exception $e)
				    {
					    $this->setError($e->getMessage());
				    }
                }
                else {
                    $this->setError('Vous pouvez pas mettre à jour une mesure de poid d un autre utilisateur');
                    $result=false;
                }
				$this->closeConnectionDatabase();
 
            }
            else {
                $this->setError('Impossible de recuperer la mesure de poids');
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

			$dateMesure=mysqli_real_escape_string($this->dblink,implode('-', array_reverse(explode('-',$this->getDateMesure()))));

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
			        $query=sprintf("SELECT * FROM poids where email='%s' and DATEMESURE='%s'",mysqli_real_escape_string($this->dblink,$this->getEmail()),$dateMesure);

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
					        // Exécution des requêtes SQL
					        $query=sprintf("INSERT INTO poids (email,datemesure,poids,commentaire) values ('%s','%s',%f,'%s')",mysqli_real_escape_string($this->dblink,$this->getEmail()),$dateMesure,mysqli_real_escape_string($this->dblink,$this->getPoids()),mysqli_real_escape_string($this->dblink,$this->getCommentaire()));
		         
					        $mysql_result = mysqli_query($this->dblink,$query);
					        if (!$mysql_result){
						        $this->setError($query);
						        $result=false;
					        }else{
						        $result = true;
					        }
				        }
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
					$query=sprintf("DELETE FROM poids where id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));
		 
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
                $this->setError('La mesure de poids n existe pas');
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
