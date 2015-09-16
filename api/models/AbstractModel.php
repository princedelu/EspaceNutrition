<?php

abstract class AbstractModel {

    abstract public function fetchAll();
    abstract public function fetchOne();
    abstract public function update();
    abstract public function create();
    abstract protected function save();
    abstract public function delete();

	protected $ini_array;
	protected $dblink;
	protected $dbClose;
	protected $_errorType;

    // Make sure to run this parent method within all child model constructors
    public function __construct()
    {
		$this->ini_array = parse_ini_file("config.ini", true);
    }

    public function getFromArray($id, $array, $key = "id")
    {
        $found = null;
        foreach ($array as $a) {
            if ($a[$key] == $id) {
                $found = $a;
                break;
            }
        }
        return $found;
    }

    public function setError($_error)
    {
        $this->_error = $_error;
    }

    public function getError()
    {
        return $this->_error;
    }
	
	public function setErrorType($_errorType)
    {
        $this->_errorType = $_errorType;
    }

    public function getErrorType()
    {
        return $this->_errorType;
    }


	public function openConnectionDatabase(){
		$result = true;

		$this->dblink = mysqli_connect($this->ini_array['MYSQL']['host'], $this->ini_array['MYSQL']['user'], $this->ini_array['MYSQL']['password']);
		if (!$this->dblink){
			throw new Exception(mysql_error());
		}else{
			if (!mysqli_select_db($this->dblink,$this->ini_array['MYSQL']['db'])){
				throw new Exception("Connexion à la base de données impossible");
			}
		}
		
		// Passage en mode UTF-8
		$mysql_result = mysqli_query($this->dblink,"set names 'utf8'"); 
		if (!$mysql_result){
			throw new Exception(mysql_error());
		}
		$this->dbClose = false;
	}

	public function closeConnectionDatabase(){
		if (!$this->dbClose){
			// Fermeture de la connexion
			mysqli_close($this->dblink);
			$this->dbClose = true;
		}

	}
}
