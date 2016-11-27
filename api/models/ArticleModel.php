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
	protected $_idCategorie;
	protected $_idCategories;

	protected $_indexMinDem;
	protected $_indexMaxDem;
	protected $_nbArticles;
	protected $_indexMin;
	protected $_indexMax;
	protected $_nbArticlesParPage;
	protected $_recherche;

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
	
	public function setIdCategories($_idCategories)
    {
        $this->_idCategories = $_idCategories;
        return $this;
    }

    public function getIdCategories()
    {
        return $this->_idCategories;
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

	public function setRecherche($_recherche)
	{
        $this->_recherche = $_recherche;
        return $this;
    }
	
	public function getRecherche()
    {
        return $this->_recherche;
    }
	
	/****************************************************************
	Fonctions 
	****************************************************************/
	
	
	public function fetchAll(){
		$result = array();
		$contenu = array();
		$queryRecherche="";
		
		try{
			$this->openConnectionDatabase();
			// Exécution des requêtes SQL
			$query=sprintf("SELECT count(*) as total FROM articles a");
			
			if ($this->getIdCategorie()!== null){
				if ($this->getRecherche()!== null){
					$queryRecherche=sprintf("and a.titre like \"%%%s%%\" ",mysqli_real_escape_string($this->dblink,$this->getRecherche()));
				}
				$query=sprintf("%s,article_categorie ac where ac.id_article=a.id and ac.id_categorie=%d %s",$query,mysqli_real_escape_string($this->dblink,$this->getIdCategorie()),$queryRecherche);
			}else{
				if ($this->getRecherche()!== null){
					$queryRecherche=sprintf("where a.titre like \"%%%s%%\" ",mysqli_real_escape_string($this->dblink,$this->getRecherche()));
				}
				$query=sprintf("%s %s",$query,$queryRecherche);
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
					
					$queryRecherche="";
					$query1=sprintf("SELECT a.id, a.titre, a.partie1, a.auteur, a.date FROM articles a");
					
					if ($this->getIdCategorie()!== null){
						if ($this->getRecherche()!== null){
							$queryRecherche=sprintf("and a.titre like \"%%%s%%\" ",mysqli_real_escape_string($this->dblink,$this->getRecherche()));	
						}
						$query2=sprintf(", article_categorie ac where ac.id_article=a.id and ac.id_categorie=%d %s",mysqli_real_escape_string($this->dblink,$this->getIdCategorie()),$queryRecherche);
						
					}else{
						$query2="";
						if ($this->getRecherche()!== null){
							$query2=sprintf("where a.titre like \"%%%s%%\" ",mysqli_real_escape_string($this->dblink,$this->getRecherche()));
						}
					}

					$query1=sprintf("%s %s ORDER BY a.date DESC LIMIT %d, %d",$query1,$query2,strval($premiereEntree),strval($this->getIndexMaxDem()-$this->getIndexMinDem()+1));
					
					$mysql_result1 = mysqli_query($this->dblink,$query1);
					$num_rows = mysqli_num_rows($mysql_result1);

					$this->setNbArticles($num_rows);
					$this->setIndexMax($premiereEntree+$num_rows-1);
				
					if ($num_rows!=0){
						while ($row = mysqli_fetch_assoc($mysql_result1)) {
							$row['date'] = implode('-', array_reverse(explode('-', $row['date'])));
							
							$row['categories']=$this->getCategoriesByArticle($row['id']);
							
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
			$query=sprintf("SELECT a.id,a.titre,a.partie1,a.partie2, a.auteur,a.date FROM articles as a where a.id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows==1){
					$row = mysqli_fetch_assoc($mysql_result);
					$row['date'] = implode('-', array_reverse(explode('-', $row['date'])));
					
					$row['categories']=$this->getCategoriesByArticle($row['id']);
					
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
	
	private function getCategoriesByArticle($_id_article){
		$result = array();
		try{
			// Récupération des catégories
			$query=sprintf("SELECT c.id, c.libelle, c1.libelle as libelle_parent FROM article_categorie as ac, categories c left outer join categories c1 on c.id_parent=c1.id where c.id=ac.id_categorie and ac.id_article=%d",mysqli_real_escape_string($this->dblink,$_id_article));
			$mysql_result = mysqli_query($this->dblink,$query);
			
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				$array_categories = array();
				if ($num_rows!=0){
					while ($row = mysqli_fetch_assoc($mysql_result)) {
						$array_categorie = array();
						$array_categorie['id']=$row['id'];
						$array_categorie['libelle']=$row['libelle'];
						if ($row['libelle_parent']==null){
							$array_categorie['libelle_long']=$row['libelle'];
						}else{
							$array_categorie['libelle_long']=$row['libelle_parent']." - ".$row['libelle'];
						}
						array_push($array_categories,$array_categorie);
					}
				}
				$result=$array_categories;
			}
		}
		catch(Exception $e)
		{
			$this->setError($e->getMessage());
		}
		mysqli_free_result($mysql_result);
		return $result;
	}
	
	public function update(){
		$result=true;
        if ($this->getId()) {

            if ($this->fetchOne()) {
				try{
					$this->openConnectionDatabase();
				
					
					// Exécution des requêtes SQL

					$query=sprintf("UPDATE articles SET ");

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
					$query=$query.sprintf(" where id=%d",mysqli_real_escape_string($this->dblink,$this->getId()));

					$mysql_result = mysqli_query($this->dblink,$query);
					if (!$mysql_result){
						$this->setError(mysqli_error($this->dblink));
						$result=false;
					}else{
						$query1=sprintf("DELETE FROM article_categorie WHERE id_article=%d ",mysqli_real_escape_string($this->dblink,$this->getId()));
						$mysql_result1 = mysqli_query($this->dblink,$query1);
						if (!$mysql_result1){
							$this->setError(mysqli_error($this->dblink));
							$result=false;
						}else{
							foreach ($this->getIdCategories() as $cle => $valeurIdCategorieCourante) {	
								$query2=sprintf("insert into article_categorie (id_article,id_categorie) values (%d,%d) ",mysqli_real_escape_string($this->dblink,$this->getId()),mysqli_real_escape_string($this->dblink,$valeurIdCategorieCourante));
								$mysql_result2 = mysqli_query($this->dblink,$query2);
								if (!$mysql_result2){
									$this->setError(mysqli_error($this->dblink));
									$result=$result and false;
								}else{
									$result = $result and true;
								}
							}
						}
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
		$result = true;
        try{
			$this->openConnectionDatabase();
			
			// Exécution des requêtes SQL
			$query=sprintf("INSERT INTO articles (TITRE, AUTEUR, PARTIE1, PARTIE2, DATE) values ('%s','%s','%s','%s',NOW())",mysqli_real_escape_string($this->dblink,$this->getTitre()),mysqli_real_escape_string($this->dblink,$this->getAuteur()),mysqli_real_escape_string($this->dblink,$this->getPartie1()),mysqli_real_escape_string($this->dblink,$this->getPartie2()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError($query);
				$result=false;
			}else{
				$this->setId(mysqli_insert_id($this->dblink));
				foreach ($this->getIdCategories() as $cle => $valeurIdCategorieCourante) {	
					$query2=sprintf("insert into article_categorie (id_article,id_categorie) values (%d,%d) ",mysqli_real_escape_string($this->dblink,$this->getId()),mysqli_real_escape_string($this->dblink,$valeurIdCategorieCourante));
					$mysql_result2 = mysqli_query($this->dblink,$query2);
					if (!$mysql_result2){
						$this->setError(mysqli_error($this->dblink));
						$result=$result and false;
					}else{
						$result = $result and true;
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
						$query1=sprintf("DELETE FROM article_categorie WHERE id_article=%d ",mysqli_real_escape_string($this->dblink,$this->getId()));
						$mysql_result1 = mysqli_query($this->dblink,$query1);
						if (!$mysql_result1){
							$this->setError(mysqli_error($this->dblink));
							$result=false;
						}else{
							$result = true;
						}
					}
                    
				}catch(Exception $e)
				{
					$this->setError($e->getMessage());
				} 
				mysqli_free_result($mysql_result);
				mysqli_free_result($mysql_result1);
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
