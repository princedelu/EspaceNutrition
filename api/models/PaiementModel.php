<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class PaiementModel extends AbstractModel {

	protected $_txnId;    
	protected $_txnType;

    public function __construct()
    {
        // Run parent method
        parent::__construct();

        // Do additional work if needed
    }

	/* Setters & getters
	*/

	public function setTxnId($_txnId)
    {
        $this->_txnId = $_txnId;
        return $this;
    }

    public function getTxnId()
    {
        return $this->_txnId;
    }


    public function setTxnType($_txnType)
    {
        $this->_txnType = $_txnType;
        return $this;
    }

    public function getTxnType()
    {
        return $this->_txnType;
    }


	/* Fonctions 
	*/
	public function fetchAll(){
		return true;
	}
	public function fetchOne(){
		return true;
	}
	public function update(){
		return true;
	}
	public function create(){
		return true;
	}
	public function delete(){
		return true;
	}
	public function save(){
		return true;
	}
    
}
