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
	protected $_residenceCountry;
	protected $_itemName1;
	protected $_itemNumber1;
	protected $_quantity1;
	protected $_tax;
	protected $_mcCurrency;
	protected $_mcFee;
	protected $_mcGross;
	protected $_mcHandling;
	protected $_mcHandling1;
	protected $_mcShipping;
	protected $_mcShipping1;
	protected $_notifyVersion;

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

	public function setResidenceCountry($_residenceCountry)
	{
		$this->_residenceCountry = $_residenceCountry;
		return $this;
	}

    public function getResidenceCountry()
    {
        return $this->_residenceCountry;
    }
	
	public function setItemName1($_itemName1)
	{
		$this->_itemName1 = $_itemName1;
		return $this;
	}

    public function getItemName1()
    {
        return $this->_itemName1;
    }

	public function setItemNumber1($_itemNumber1)
	{
		$this->_itemNumber1 = $_itemNumber1;
		return $this;
	}

    public function getItemNumber1()
    {
        return $this->_itemNumber1;
    }

	public function setQuantity1($_quantity1)
	{
		$this->_quantity1 = $_quantity1;
		return $this;
	}

    public function getQuantity1()
    {
        return $this->_quantity1;
    }

	public function setTax($_tax)
	{
		$this->_tax = $_tax;
		return $this;
	}

    public function getTax()
    {
        return $this->_tax;
    }

	public function setMcCurrency($_mcCurrency)
	{
		$this->_mcCurrency = $_mcCurrency;
		return $this;
	}

    public function getMcCurrency()
    {
        return $this->_mcCurrency;
    }

	public function setMcFee($_mcFee)
	{
		$this->_mcFee = $_mcFee;
		return $this;
	}

    public function getMcFee()
    {
        return $this->_mcFee;
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

	public function setMcHandling($_mcHandling)
	{
		$this->_mcHandling = $_mcHandling;
		return $this;
	}

    public function getMcHandling()
    {
        return $this->_mcHandling;
    }

	public function setMcHandling1($_mcHandling1)
	{
		$this->_mcHandling1 = $_mcHandling1;
		return $this;
	}

    public function getMcHandling1()
    {
        return $this->_mcHandling1;
    }

	public function setMcShipping($_mcShipping)
	{
		$this->_mcShipping = $_mcShipping;
		return $this;
	}

    public function getMcShipping()
    {
        return $this->_mcShipping;
    }

	public function setMcShipping1($_mcShipping1)
	{
		$this->_mcShipping1 = $_mcShipping1;
		return $this;
	}

    public function getMcShipping1()
    {
        return $this->_mcShipping1;
    }

	public function setNotifyVersion($_notifyVersion)
	{
		$this->_notifyVersion = $_notifyVersion;
		return $this;
	}

    public function getNotifyVersion()
    {
        return $this->_notifyVersion;
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
		if (isset($post['residence_country']))
			$this->setResidenceCountry($post['residence_country']);
		if (isset($post['item_name1']))
			$this->setItemName1($post['item_name1']);
		if (isset($post['item_number1']))
			$this->setItemNumber1($post['item_number1']);
		if (isset($post['quantity1']))
			$this->setQuantity1($post['quantity1']);
		if (isset($post['tax']))
			$this->setTax($post['tax']);
		if (isset($post['mc_currency']))
			$this->setMcCurrency($post['mc_currency']);
		if (isset($post['mc_fee']))
			$this->setMcFee($post['mc_fee']);
		if (isset($post['mc_gross']))
			$this->setMcGross($post['mc_gross']);
		if (isset($post['mc_handling']))
			$this->setMcHandling($post['mc_handling']);
		if (isset($post['mc_handling1']))
			$this->setMcHandling1($post['mc_handling1']);
		if (isset($post['mc_shipping']))
			$this->setMcShipping($post['mc_shipping']);
		if (isset($post['mc_shipping1']))
			$this->setMcShipping1($post['mc_shipping1']);
		if (isset($post['notify_version']))
			$this->setNotifyVersion($post['notify_version']);
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

				//if(USE_SANDBOX == true) {
					$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
				/*} else {
					$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
				}*/

				$ch = curl_init($paypal_url);
				if ($ch == FALSE) {
					$this->setError("Initialisation de la connexion à paypal impossible");
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
					} else {
						curl_close($ch);

						if (strcmp ($res, "VERIFIED") == 0) {

							$this->openConnectionDatabase();

							// Exécution des requêtes SQL
							$query=sprintf("INSERT INTO paiements (txnid, payment_amount, payment_status, itemid, createdtime) VALUES ('%s',%f,'%s','%s','%s')",mysqli_real_escape_string($this->dblink,$this->getTxnId()),mysqli_real_escape_string($this->dblink,$this->getMcGross()),mysqli_real_escape_string($this->dblink,$this->getPaymentStatus()),'itemid',date("Y-m-d H:i:s"));
				 
							$mysql_result = mysqli_query($this->dblink,$query);
							if (!$mysql_result){
								$this->setError(mysql_error());
								$result=false;
							}else{
								$result = true;
							}
						}else if (strcmp ($res, "INVALID") == 0) {
							$this->setError("Paiment invalide");
						}
					}
				}
			}catch(Exception $e)
			{
				$this->setError($e);
			} 
	
			$this->closeConnectionDatabase();
		}else{
			$this->setError("Au moins une notification a déjà été faite pour ce paiement");
		}

		return $result;
	}
	
	public function fetchAll(){
		return true;
	}
	public function fetchOne(){

		$result = false;

		try{
			$this->openConnectionDatabase();

			// Exécution des requêtes SQL
			$query=sprintf("SELECT txnId FROM paiements where txnId=%d",mysqli_real_escape_string($this->dblink,$this->getTxnId()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError(mysql_error());
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
