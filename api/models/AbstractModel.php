<?php

require 'tools/JWT.php';

abstract class AbstractModel {

    abstract public function fetchAll();
    abstract public function fetchOne($id);
    abstract public function update();
    abstract public function create();
    abstract protected function save();
    abstract public function delete();

	protected $ini_array;
	protected $dblink;

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

	public function openConnectionDatabase(){
		$result = true;

		$this->dblink = mysql_connect($this->ini_array['MYSQL']['host'], $this->ini_array['MYSQL']['user'], $this->ini_array['MYSQL']['password']);
		if (!$this->dblink){
			$this->setError(mysql_error());
			$result=false;
		}else{
			if (!mysql_select_db($this->ini_array['MYSQL']['db'])){
				$this->setError("Connexion à la base de données impossible");
				$result=false;
			}
		}
		return $result;
	}

	public function closeConnectionDatabase(){

		// Fermeture de la connexion
		mysql_close($this->dblink);

	}
}
