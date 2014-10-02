<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class PaiementModel extends AbstractModel {

	protected $_post;
	protected $_txnId;    
	protected $_txnType;
	protected $_paymentType;
	protected $_paymentDate;
	protected $_paymentStatus;
	protected $_payerStatus;
	protected $_payerFirstName;
	protected $_payerLastName;
	protected $_payerEmail;
	protected $_payerId;
	protected $_business;
	protected $_receiverEmail;
	protected $_receiverId;
	protected $_itemName;
	protected $_itemNumber;
	protected $_mcGross;

    public function __construct()
    {
        // Run parent method
        parent::__construct();

        // Do additional work if needed
    }

	/****************************************************************
	Getters et setters 
	****************************************************************/

	public function setPost($_post)
    {
        $this->_post = $_post;
        return $this;
    }

    public function getPost()
    {
        return $this->_post;
    }

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

	public function setPaymentType($_paymentType)
	{
		$this->_paymentType = $_paymentType;
		return $this;
	}

    public function getPaymentType()
    {
        return $this->_paymentType;
    }

	public function setPaymentDate($_paymentDate)
	{
		$this->_paymentDate = $_paymentDate;
		return $this;
	}

    public function getPaymentDate()
    {
        return $this->_paymentDate;
    }

	public function setPaymentStatus($_paymentStatus)
	{
		$this->_paymentStatus = $_paymentStatus;
		return $this;
	}

    public function getPaymentStatus()
    {
        return $this->_paymentStatus;
    }

	public function setPayerStatus($_payerStatus)
	{
		$this->_payerStatus = $_payerStatus;
		return $this;
	}

    public function getPayerStatus()
    {
        return $this->_payerStatus;
    }

	public function setPayerFirstName($_payerFirstName)
	{
		$this->_payerFirstName = $_payerFirstName;
		return $this;
	}

    public function getPayerFirstName()
    {
        return $this->_payerFirstName;
    }

	public function setPayerLastName($_payerLastName)
	{
		$this->_payerLastName = $_payerLastName;
		return $this;
	}

    public function getPayerLastName()
    {
        return $this->_payerLastName;
    }

	public function setPayerEmail($_payerEmail)
	{
		$this->_payerEmail = $_payerEmail;
		return $this;
	}

    public function getPayerEmail()
    {
        return $this->_payerEmail;
    }

	public function setPayerId($_payerId)
	{
		$this->_payerId = $_payerId;
		return $this;
	}

    public function getPayerId()
    {
        return $this->_payerId;
    }

	public function setBusiness($_business)
	{
		$this->_business = $_business;
		return $this;
	}

    public function getBusiness()
    {
        return $this->_business;
    }

		public function setReceiverEmail($_receiverEmail)
	{
		$this->_receiverEmail = $_receiverEmail;
		return $this;
	}

    public function getReceiverEmail()
    {
        return $this->_receiverEmail;
    }

	public function setReceiverId($_receiverId)
	{
		$this->_receiverId = $_receiverId;
		return $this;
	}

    public function getReceiverId()
    {
        return $this->_receiverId;
    }

	
	public function setItemName($_itemName)
	{
		$this->_itemName = $_itemName;
		return $this;
	}

    public function getItemName()
    {
        return $this->_itemName;
    }

	public function setItemNumber($_itemNumber)
	{
		$this->_itemNumber = $_itemNumber;
		return $this;
	}

    public function getItemNumber()
    {
        return $this->_itemNumber;
    }

	
	public function setMcGross($_mcGross)
	{
		$this->_mcGross = $_mcGross;
		return $this;
	}

    public function getMcGross()
    {
        return $this->_mcGross;
    }

	
	/****************************************************************
	Fonctions 
	****************************************************************/
	public function init(){
		$post = $this->getPost();

		$this->setTxnId($post['txn_id']);
		$this->setTxnType($post['txn_type']);
		if (isset($post['payment_type']))
			$this->setPaymentType($post['payment_type']);
		if (isset($post['payment_date']))
			$this->setPaymentDate($post['payment_date']);
		if (isset($post['payment_status']))
			$this->setPaymentStatus($post['payment_status']);
		if (isset($post['payer_status']))
			$this->setPayerStatus($post['payer_status']);
		if (isset($post['first_name']))
			$this->setPayerFirstName($post['first_name']);
		if (isset($post['last_name']))
			$this->setPayerLastName($post['last_name']);
		if (isset($post['payer_email']))
			$this->setPayerEmail($post['payer_email']);
		if (isset($post['payer_id']))
			$this->setPayerId($post['payer_id']);
		if (isset($post['business']))
			$this->setBusiness($post['business']);
		if (isset($post['receiver_email']))
			$this->setReceiverEmail($post['receiver_email']);
		if (isset($post['receiver_id']))
			$this->setReceiverId($post['receiver_id']);
		if (isset($post['item_name']))
			$this->setItemName($post['item_name']);
		if (isset($post['item_number']))
			$this->setItemNumber($post['item_number']);
		if (isset($post['mc_gross']))
			$this->setMcGross($post['mc_gross']);
	}

	public function notify(){
		$result = false;
		
		$recupPaiementExistant = $this->fetchOne();

		if ($recupPaiementExistant == false && $this->getErrorType() == 0){
			try{

				// Vérification de la requête
				// Lecture de toutes les variables fournies dans le post de PayPal
				$req = 'cmd=_notify-validate';
				foreach ($this->getPost() as $key => $value) {
					$value = urlencode(stripslashes($value));
					$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
					$req .= "&$key=$value";
				}

				if ($this->ini_array['paypal']['mode'] == 'prod'){
					$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
				} else {
					$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
				}

				$ch = curl_init($paypal_url);
				if ($ch == FALSE) {
					$this->setError("Initialisation de la connexion à paypal impossible");
					$result=false;
				}else{
				
					curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

					// Set TCP timeout to 30 seconds
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
					// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
					// of the certificate as shown below. Ensure the file is readable by the webserver.
					// This is mandatory for some environments.
					//$cert = __DIR__ . "./cacert.pem";
					//curl_setopt($ch, CURLOPT_CAINFO, $cert);
					$res = curl_exec($ch);
					if (curl_errno($ch) != 0) // cURL error
					{
						curl_close($ch);
						$this->setError("Erreur de connexion à paypal");
						$result=false;
					} else {
						curl_close($ch);

						if (strcmp ($res, "VERIFIED") == 0) {
							$result=$this->insertPaiement();	
						}else if (strcmp ($res, "INVALID") == 0) {
							$this->setError("Paiment invalide");
						}
					}
				}
			}catch(Exception $e)
			{
				$this->setError($e);
			} 
		}else{
			$this->setError("Au moins une notification a déjà été faite pour ce paiement");
		}

		return $result;
	}
	
	public function fetchAll(){
		$result = array();
		try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT txnid, payment_amount, payment_status, item_name, createdtime, payer_id, payer_last_name, payer_first_name, payer_email, business, mode FROM paiements");
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError('Erreur SQL');
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows!=0){
					while ($row = mysqli_fetch_assoc($mysql_result)) {
						array_push($result,$row);
					}

					mysqli_free_result($mysql_result);
				}
			}
		}
		catch(Exception $e)
		{
			$this->setError($e->getMessage());
			$result = false;
		} 

		$this->closeConnectionDatabase();

		return $result;
	}

	public function insertPaiement(){
		$result = false;

		try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("INSERT INTO paiements (txnid, payment_amount, payment_status, item_name, createdtime, payer_id,payer_last_name,payer_first_name,payer_email,business,mode) VALUES ('%s',%f,'%s','%s','%s','%s','%s','%s','%s','%s','%s')",mysqli_real_escape_string($this->dblink,$this->getTxnId()),mysqli_real_escape_string($this->dblink,$this->getMcGross()),mysqli_real_escape_string($this->dblink,$this->getPaymentStatus()),mysqli_real_escape_string($this->dblink,$this->getItemName()),date("Y-m-d H:i:s"),mysqli_real_escape_string($this->dblink,$this->getPayerId()),mysqli_real_escape_string($this->dblink,$this->getPayerLastName()),mysqli_real_escape_string($this->dblink,$this->getPayerFirstName()),mysqli_real_escape_string($this->dblink,$this->getPayerEmail()),mysqli_real_escape_string($this->dblink,$this->getBusiness()),$this->ini_array['paypal']['mode']);
 
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

		return $result;

	}

	public function fetchOne(){

		$result = false;

		try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT txnId FROM paiements where txnId=%d",mysqli_real_escape_string($this->dblink,$this->getTxnId()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysqli_error($this->dblink));
				$result=false;
			}else{
				$num_rows = mysqli_num_rows($mysql_result);
				if ($num_rows==1){
					$row = mysqli_fetch_assoc($mysql_result);
					$result = $row;
				}else{
					if ($num_rows==0){
						$this->setError("Aucun paiement n existe pour cet identifiant");
					}else{
						$this->setError("Plusieurs paiements existent pour cet identifiant!");
					}
					$this->setErrorType($num_rows);
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
