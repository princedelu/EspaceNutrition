<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class ArticleModel extends AbstractModel {

	protected $_id;
	protected $_titre;    
	protected $_auteur;
	protected $_partie1;
	protected $_partie2;
	protected $_date;
	protected $_idCategory;

	protected $_indexMinDem;
	protected $_indexMaxDem;
	protected $_nbArticles;
	protected $_indexMin;
	protected $_indexMax;
	protected $_nbArticlesParPage;

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

	public function setTitre($_titre)
    {
        $this->_titre = $_titre;
        return $this;
    }

    public function getTitre()
    {
        return $this->_titre;
    }

	public function setAuteur($_auteur)
    {
        $this->_auteur = $_auteur;
        return $this;
    }

    public function getAuteur()
    {
        return $this->_auteur;
    }

	public function setPartie1($_partie1)
    {
        $this->_partie1 = $_partie1;
        return $this;
    }

    public function getPartie1()
    {
        return $this->_partie1;
    }

	public function setPartie2($_partie2)
    {
        $this->_partie2 = $_partie2;
        return $this;
    }

    public function getPartie2()
    {
        return $this->_partie2;
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

	public function setIdCategorie($_idCategorie)
    {
        $this->_idCategorie = $_idCategorie;
        return $this;
    }

    public function getIdCategorie()
    {
        return $this->_idCategorie;
    }

	public function setNbArticles($_nbArticles)
    {
        $this->_nbArticles = $_nbArticles;
        return $this;
    }

    public function getNbArticles()
    {
        return $this->_nbArticles;
    }

	public function setNbArticlesParPage($_nbArticlesParPage)
    {
        $this->_nbArticlesParPage = $_nbArticlesParPage;
        return $this;
    }

    public function getNbArticlesParPage()
    {
        return $this->_nbArticlesParPage;
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


	
	/****************************************************************
	Fonctions 
	****************************************************************/
	
	
	public function fetchAll(){
		$result = array();
		$contenu = array();

		try{
			$this->openConnectionDatabase();
			// Exécution des requêtes SQL
			if ($this->getIdCategorie()!== null){
				$query=sprintf("SELECT count(*) as total FROM articles where id_categorie=%d",mysqli_real_escape_string($this->dblink,$this->getIdCategorie()));
			}else{
				$query=sprintf("SELECT count(*) as total FROM articles");
			}
 
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
						$this->setIndexMaxDem($this->getNbArticlesParPage()-1);
					}

					$premiereEntree=$this->getIndexMinDem(); // On calcul la première entrée à lire
					$this->setIndexMin($premiereEntree);				
					
					if ($this->getIdCategorie()!== null){
						$query2=sprintf(" and a.id_categorie=%d",mysqli_real_escape_string($this->dblink,$this->getIdCategorie()));
					}else{
						$query2="";
					}

					$query1=sprintf("SELECT a.id, a.titre, a.partie1,a.partie1, a.auteur, a.date, c.libelle,c1.libelle as libelle_long FROM articles AS a, categories AS c left join categories as c1 on c.id_parent = c1.id WHERE a.id_categorie = c.id".$query2." ORDER BY a.date DESC LIMIT ".strval($premiereEntree).", ".strval($this->getIndexMaxDem()-$this->getIndexMinDem()+1));
	 
					$mysql_result1 = mysqli_query($this->dblink,$query1);
					$num_rows = mysqli_num_rows($mysql_result1);

					$this->setNbArticles($num_rows);
					$this->setIndexMax($premiereEntree+$num_rows-1);
				
					if ($num_rows!=0){
						while ($row = mysqli_fetch_assoc($mysql_result1)) {
							$row['date'] = implode('-', array_reverse(explode('-', $row['date'])));
							if ($row['libelle_long']==null){
								$row['libelle_long']=$row['libelle'];
							}else{
								$row['libelle_long']=$row['libelle_long']." - ".$row['libelle'];
							}
							array_push($contenu,$row);
						}
						mysqli_free_result($mysql_result1);

						$print=false;
						$link="";
						if ($this->getIndexMin()!=0){
							$print=true;
							$link="0-".strval($this->getNbArticlesParPage()-1);
						}
						$first=array('link' => $link, 'print' => $print,'page'=> '1');

						$print=false;
						$link="";
						$page="";
						if ($this->getIndexMin()!=0){
							$print=true;
							$minIndex = 0;
							if ($this->getIndexMin() - $this->getNbArticlesParPage() > 0)
							{
								$minIndex = $this->getIndexMin() - $this->getNbArticlesParPage();
							}
							$maxIndex=$minIndex+$this->getNbArticlesParPage()-1;
							$link=strval($minIndex)."-".strval($maxIndex);
							$page=ceil($minIndex/$this->getNbArticlesParPage())+1;
						}
						$previous=array('link' => $link, 'print' => $print,'page'=> strval($page));

						$print=false;
						$link="";
						$page="";
						if ($this->getIndexMax()<$num_rowsTotal-1){
							$print=true;
							$minIndex = $this->getIndexMax()+1;
							if ($this->getIndexMax() + $this->getNbArticlesParPage() < $num_rowsTotal)
							{
								$maxIndex = $minIndex + $this->getNbArticlesParPage() - 1;
							}else{
								$maxIndex = $num_rowsTotal - 1;
							}
							$link=strval($minIndex)."-".strval($maxIndex);
							$page=ceil($minIndex/$this->getNbArticlesParPage())+1;
						}
						$next=array('link' => $link, 'print' => $print,'page'=> strval($page));

						$print=false;
						$link="";
						$page="";
						if ($this->getIndexMax()<$num_rowsTotal-1){
							$print=true;
							$maxIndex = $num_rowsTotal - 1;
							
							if ($maxIndex < $this->getIndexMax() + $this->getNbArticlesParPage())
							{
								$minIndex = $this->getIndexMax() + 1;
							}else{
								$minIndex = $maxIndex - $this->getNbArticlesParPage() + 1;
							}
							$link=strval($minIndex)."-".strval($maxIndex);
							$page=ceil($minIndex/$this->getNbArticlesParPage())+1;
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
			$query=sprintf("SELECT a.id,a.titre,a.partie1,a.partie2, a.auteur,a.date, a.id_categorie,c.libelle,c1.libelle as libelle_long FROM articles as a, categories as c left join categories as c1 on c.id_parent = c1.id where a.id_categorie=c.id and a.id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows==1){
					$row = mysqli_fetch_assoc($mysql_result);
					$row['date'] = implode('-', array_reverse(explode('-', $row['date'])));
					if ($row['libelle_long']==null){
						$row['libelle_long']=$row['libelle'];
					}else{
						$row['libelle_long']=$row['libelle_long']." - ".$row['libelle'];
					}
					$result = $row;
				}else{
					if ($num_rows==0){
						$this->setError("Aucun article existant pour cet identifiant!");
					}else{
						$this->setError("Plusieurs articles existent pour cet identifiant!");
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
		$result=false;
        if ($this->getId()) {

            if ($this->fetchOne()) {
				try{
					$this->openConnectionDatabase();
				
					
					// Exécution des requêtes SQL

					$query=sprintf("UPDATE articles SET ",mysqli_real_escape_string($this->dblink,$this->getId()));

					$query=$query.sprintf("titre='%s'",mysqli_real_escape_string($this->dblink,$this->getTitre()));
					
					if ($this->getAuteur() != ''){
						$query=$query.sprintf(" ,auteur='%s'",mysqli_real_escape_string($this->dblink,$this->getAuteur()));
					}
					if ($this->getDate() != ''){
						$query=$query.sprintf(" ,date='%s'",implode('-', array_reverse(explode('-', mysqli_real_escape_string($this->dblink,$this->getDate())))));
					}
					if ($this->getPartie1() != ''){
						$query=$query.sprintf(" ,partie1='%s'",mysqli_real_escape_string($this->dblink,$this->getPartie1()));
					}
					if ($this->getPartie2() != ''){
						$query=$query.sprintf(" ,partie2='%s'",mysqli_real_escape_string($this->dblink,$this->getPartie2()));
					}
					if ($this->getIdCategorie() != ''){
						$query=$query.sprintf(" ,id_categorie=%d",mysqli_real_escape_string($this->dblink,$this->getIdCategorie()));
					}
					$query=$query.sprintf(" where id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));


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
                $this->setError('Impossible de recuperer un article');
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
		$result = false;
        try{
			$this->openConnectionDatabase();
			
			// Exécution des requêtes SQL
			$query=sprintf("INSERT INTO articles (TITRE, AUTEUR, PARTIE1, PARTIE2, DATE, ID_CATEGORIE) values ('%s','%s','%s','%s',NOW(),%d)",mysqli_real_escape_string($this->dblink,$this->getTitre()),mysqli_real_escape_string($this->dblink,$this->getAuteur()),mysqli_real_escape_string($this->dblink,$this->getPartie1()),mysqli_real_escape_string($this->dblink,$this->getPartie2()),mysqli_real_escape_string($this->dblink,$this->getIdCategorie()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError($query);
				$result=false;
			}else{
				$result = true;
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

            $resultArticle=$this->fetchOne();
            if ($resultArticle) {

                try{
					$this->openConnectionDatabase();

                    
					// Exécution des requêtes SQL
					$query=sprintf("DELETE FROM articles where id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));
		 
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
	public function save(){
		return true;
	}
    
}
