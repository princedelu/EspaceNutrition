<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class CategorieModel extends AbstractModel {

    protected $_id;
	protected $_libelle;

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


	public function setLibelle($_libelle)
    {
        $this->_libelle = $_libelle;
        return $this;
    }

    public function getLibelle()
    {
        return $this->_libelle;
    }

	

    public function toArray()
    {
        return array (
            'id' => $this->getId(),
			 'libelle' => $this->getLibelle()
        );
    }

	/*
	* Récupération de toutes les catégories
	*/
    public function fetchAll()
    {
        $result = array();

		try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT * FROM categories");
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows!=0){
					while ($row = mysqli_fetch_assoc($mysql_result)) {
						if ($row['id_parent']==0){
							$row['libelle_long']=$row['libelle'];
							$libellePrec=$row['libelle'];
						}else{
							$row['libelle_long']=$libellePrec." - ".$row['libelle'];
						}
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
      $result=true;
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
		$result=true;
		return $result; 
    }

    public function delete()
    {
		$result=true;
		return $result; 
    }
	

    protected function save()
    {
       return true;
    }
}
