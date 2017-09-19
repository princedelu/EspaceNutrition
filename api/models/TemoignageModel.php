<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class TemoignageModel extends AbstractModel {

	protected $_id;    
	protected $_prenom;
	protected $_age;
	protected $_objectif;
	protected $_temoignage;
	protected $_date;
	protected $_valide;

	protected $_indexMinDem;
	protected $_indexMaxDem;
	protected $_nbTemoignages;
	protected $_indexMin;
	protected $_indexMax;
	protected $_nbTemoignageParPage;
	
	protected $_filtreValide;

    public function __construct()
    {
        // Run parent method
        parent::__construct();

        // Do additional work if needed
    }

	/****************************************************************
	Getters et setters 
	****************************************************************/
	
	public function setId($_id)
    {
        $this->_id = $_id;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
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

	public function setAge($_age)
    {
        $this->_age = $_age;
        return $this;
    }

    public function getAge()
    {
        return $this->_age;
    }

	public function setObjectif($_objectif)
    {
        $this->_objectif = $_objectif;
        return $this;
    }

    public function getObjectif()
    {
        return $this->_objectif;
    }
	
	public function setTemoignage($_temoignage)
    {
        $this->_temoignage = $_temoignage;
        return $this;
    }

    public function getTemoignage()
    {
        return $this->_temoignage;
    }

	public function setDate($_date)
    {
        $this->_date = $_date;
        return $this;
    }

    public function getDate()
    {
        return $this->_date;
    }
	
	public function setValide($_valide)
    {
        $this->_valide = $_valide;
        return $this;
    }

    public function getValide()
    {
        return $this->_valide;
    }

	public function setNbTemoignages($_nbTemoignages)
    {
        $this->_nbTemoignages = $_nbTemoignages;
        return $this;
    }

    public function getNbTemoignages()
    {
        return $this->_nbTemoignages;
    }

	public function setNbTemoignagesParPage($_nbTemoignagesParPage)
    {
        $this->_nbTemoignagesParPage = $_nbTemoignagesParPage;
        return $this;
    }

    public function getNbTemoignagesParPage()
    {
        return $this->_nbTemoignagesParPage;
    }

	public function setIndexMin($_indexMin)
    {
        $this->_indexMin = $_indexMin;
        return $this;
    }

    public function getIndexMin()
    {
        return $this->_indexMin;
    }

	public function setIndexMax($_indexMax)
    {
        $this->_indexMax = $_indexMax;
        return $this;
    }

    public function getIndexMax()
    {
        return $this->_indexMax;
    }

	public function setIndexMinDem($_indexMinDem)
    {
        $this->_indexMinDem = $_indexMinDem;
        return $this;
    }

    public function getIndexMinDem()
    {
        return $this->_indexMinDem;
    }

	public function setIndexMaxDem($_indexMaxDem)
    {
        $this->_indexMaxDem = $_indexMaxDem;
        return $this;
    }

    public function getIndexMaxDem()
    {
        return $this->_indexMaxDem;
    }
		
	public function setFiltreValide($_filtreValide)
    {
        $this->_filtreValide = $_filtreValide;
        return $this;
    }

    public function getFiltreValide()
    {
        return $this->_filtreValide;
    }
	
	/****************************************************************
	Fonctions 
	****************************************************************/
	
	
	public function fetchAll(){
		$result = array();
		$contenu = array();
		
		try{
			$this->openConnectionDatabase();
			// Exécution des requêtes SQL
			$filtre = "";
			if ($this->getFiltreValide()){
				$filtre = "where t.valide=1";
			}
			
			$query=sprintf("SELECT count(*) as total FROM temoignages t %s",mysqli_real_escape_string($this->dblink,$filtre));
			
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rowsTotal=mysqli_fetch_assoc($mysql_result)['total'];
				mysqli_free_result($mysql_result);
				if ($num_rowsTotal != 0)
				{
					if ($num_rowsTotal<$this->getIndexMinDem()+1){
						$this->setIndexMinDem(0);
						$this->setIndexMaxDem($this->getNbTemoignagesParPage()-1);
					}

					$premiereEntree=$this->getIndexMinDem(); // On calcul la première entrée à lire
					$this->setIndexMin($premiereEntree);				
					
					$query1=sprintf("SELECT t.id, t.prenom, t.age, t.objectif, t.temoignage, t.date,t.valide FROM temoignages t %s ORDER BY t.date,t.id DESC LIMIT %d, %d",mysqli_real_escape_string($this->dblink,$filtre),strval($premiereEntree),strval($this->getIndexMaxDem()-$this->getIndexMinDem()+1));
					
					$mysql_result1 = mysqli_query($this->dblink,$query1);
					$num_rows = mysqli_num_rows($mysql_result1);

					$this->setNbTemoignages($num_rows);
					$this->setIndexMax($premiereEntree+$num_rows-1);
				
					if ($num_rows!=0){
						while ($row = mysqli_fetch_assoc($mysql_result1)) {
							$row['date'] = implode('-', array_reverse(explode('-', $row['date'])));
														
							array_push($contenu,$row);
						}
						mysqli_free_result($mysql_result1);

						$print=false;
						$link="";
						if ($this->getIndexMin()!=0){
							$print=true;
							$link="0-".strval($this->getNbTemoignagesParPage()-1);
						}
						$first=array('link' => $link, 'print' => $print,'page'=> '1');

						$print=false;
						$link="";
						$page="";
						if ($this->getIndexMin()!=0){
							$print=true;
							$minIndex = 0;
							if ($this->getIndexMin() - $this->getNbTemoignagesParPage() > 0)
							{
								$minIndex = $this->getIndexMin() - $this->getNbTemoignagesParPage();
							}
							$maxIndex=$minIndex+$this->getNbTemoignagesParPage()-1;
							$link=strval($minIndex)."-".strval($maxIndex);
							$page=ceil($minIndex/$this->getNbTemoignagesParPage())+1;
						}
						$previous=array('link' => $link, 'print' => $print,'page'=> strval($page));

						$print=false;
						$link="";
						$page="";
						if ($this->getIndexMax()<$num_rowsTotal-1){
							$print=true;
							$minIndex = $this->getIndexMax()+1;
							if ($this->getIndexMax() + $this->getNbTemoignagesParPage() < $num_rowsTotal)
							{
								$maxIndex = $minIndex + $this->getNbTemoignagesParPage() - 1;
							}else{
								$maxIndex = $num_rowsTotal - 1;
							}
							$link=strval($minIndex)."-".strval($maxIndex);
							$page=ceil($minIndex/$this->getNbTemoignagesParPage())+1;
						}
						$next=array('link' => $link, 'print' => $print,'page'=> strval($page));

						$print=false;
						$link="";
						$page="";
						if ($this->getIndexMax()<$num_rowsTotal-1){
							$print=true;
							$maxIndex = $num_rowsTotal - 1;
							
							if ($maxIndex < $this->getIndexMax() + $this->getNbTemoignagesParPage())
							{
								$minIndex = $this->getIndexMax() + 1;
							}else{
								$minIndex = $maxIndex - $this->getNbTemoignagesParPage() + 1;
							}
							$link=strval($minIndex)."-".strval($maxIndex);
							$page=ceil($minIndex/$this->getNbTemoignagesParPage())+1;
						}
						$last=array('link' => $link, 'print' => $print,'page' => strval($page));


						$links = array('first'=> $first,'previous' => $previous,'next' => $next,'last' => $last);

						$result = array('result' => $contenu, 'links' => $links);
					}
				}
			}
		}
		catch(Exception $e)
		{
			$result = false;
			$this->setError($e->getMessage());
		} 
		
		$this->closeConnectionDatabase();

		return $result;
	}


	public function fetchOne(){
		$result = false;

		try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT t.id,t.prenom,t.age,t.objectif, t.temoignage,t.date,t.valide FROM temoignages as t where t.id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows==1){
					$row = mysqli_fetch_assoc($mysql_result);
					$row['date'] = implode('-', array_reverse(explode('-', $row['date'])));
					
					$result = $row;
				}else{
					if ($num_rows==0){
						$this->setError("Aucun témoignage existant pour cet identifiant!");
					}else{
						$this->setError("Plusieurs témoignages existent pour cet identifiant!");
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
	
	
	public function update(){
		$result=true;
        if ($this->getId()) {

            if ($this->fetchOne()) {
				try{
					$this->openConnectionDatabase();

					// Exécution des requêtes SQL

					$query=sprintf("UPDATE temoignages SET ");

					$query=$query.sprintf("prenom='%s'",mysqli_real_escape_string($this->dblink,$this->getPrenom()));
					
					if ($this->getAge() != ''){
						$query=$query.sprintf(" ,age=%d",mysqli_real_escape_string($this->dblink,$this->getAge()));
					}
					if ($this->getDate() != ''){
						$query=$query.sprintf(" ,date='%s'",implode('-', array_reverse(explode('-', mysqli_real_escape_string($this->dblink,$this->getDate())))));
					}
					if ($this->getObjectif() != ''){
						$query=$query.sprintf(" ,objectif='%s'",mysqli_real_escape_string($this->dblink,$this->getObjectif()));
					}
					if ($this->getTemoignage() != ''){
						$query=$query.sprintf(" ,temoignage='%s'",mysqli_real_escape_string($this->dblink,$this->getTemoignage()));
					}
					if ($this->getValide() != ''){
						$query=$query.sprintf(" ,valide=%d",mysqli_real_escape_string($this->dblink,$this->getValide()));
					}
					$query=$query.sprintf(" where id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));

					$mysql_result = mysqli_query($this->dblink,$query);
					if (!$mysql_result){
						$this->setError(mysqli_error($this->dblink));
						$result=false;
					}					
				}catch(Exception $e)
				{
					$this->setError($e->getMessage());
				}
				
				$this->closeConnectionDatabase();
 
            }
            else {
                $this->setError('Impossible de recuperer un temoignage');
                $result=false;
            }
        }
        else {
            $this->setError('Champ id manquant');
            $result=false;
        }
		
		return $result; 
	}
	
	public function create(){
		$result = true;
        try{
			$this->openConnectionDatabase();
			
			// Exécution des requêtes SQL
			$query=sprintf("INSERT INTO temoignages (PRENOM, AGE, OBJECTIF, TEMOIGNAGE, DATE, VALIDE) values ('%s','%s','%s','%s',NOW(),0)",mysqli_real_escape_string($this->dblink,$this->getPrenom()),mysqli_real_escape_string($this->dblink,$this->getAge()),mysqli_real_escape_string($this->dblink,$this->getObjectif()),mysqli_real_escape_string($this->dblink,$this->getTemoignage()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError($query);
				$result=false;
			}
			$this->sendMessage();
		}catch(Exception $e)
		{
			$this->setError($e);
		} 
		
		$this->closeConnectionDatabase();
   
		return $result; 
	}
	
	public function sendMessage(){
		$result = false;
		if ($this->ini_array['mail']['action']){
			$subject = "Témoignage sur le site http://www.espace-nutrition.fr";
			$message = '<html>
							<head>
								<title>Message du site http://www.espace-nutrition.fr</title>
							</head>
							<body>
								Bonjour,
								<br/>
								<br/>
								Témoignage sur le site de '.$this->getPrenom().'
								<br/>
								<br/>
								Age : '.$this->getAge().' ans
								<br/>
								<br/>
								Objectif : '.$this->getObjectif().'
								<br/>
								<br/>
								Témoignage : '.$this->getTemoignage().'
								<br/>
								<br/>
								Merci de le valider.
							</body>
						</html>';

			// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

			// En-têtes additionnels
			$headers .= 'To: '.$this->ini_array['mail']['contactNom'].'<'.$this->ini_array['mail']['contactMail'].'>' . "\r\n";
			$headers .= 'From: Espace Nutrition <contact@espace-nutrition.fr>' . "\r\n";

			//Envoi du mail
			try
			{
				$result = mail ($this->ini_array['mail']['contactMail'],$subject,$message,$headers);
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
	
	public function delete()
    {
		$result = false;

        if ($this->getId()) {

            $resultTemoignage=$this->fetchOne();
            if ($resultTemoignage) {

                try{
					$this->openConnectionDatabase();

					// Exécution des requêtes SQL
					$query=sprintf("DELETE FROM temoignages where id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));
		 
					$mysql_result = mysqli_query($this->dblink,$query);
					if (!$mysql_result){
						$this->setError(mysqli_error($this->dblink));
						$result=false;
					}
                    
				}catch(Exception $e)
				{
					$this->setError($e->getMessage());
				} 
				mysqli_free_result($mysql_result);
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
	public function save(){
		return true;
	}
    
}
