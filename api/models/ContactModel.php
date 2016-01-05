<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class ContactModel extends AbstractModel {

	protected $_email;
	protected $_nom;
	protected $_telephone;
	protected $_message;

    public function __construct()
    {
        // Run parent method
        parent::__construct();

        // Do additional work if needed
    }    

	public function setNom($_nom)
    {
        $this->_nom = $_nom;
        return $this;
    }

    public function getNom()
    {
        return $this->_nom;
    }

	public function setTelephone($_telephone)
    {
        $this->_telephone = $_telephone;
        return $this;
    }

    public function getTelephone()
    {
        return $this->_telephone;
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


	public function setMessage($_message)
    {
        $this->_message = $_message;
        return $this;
    }

    public function getMessage()
    {
        return $this->_message;
    }

    public function toArray()
    {
        return array (
			'nom' => $this->getNom(),
			 'email' => $this->getEmail(),
			 'message' => $this->getMessage()
        );
    }

	public function sendMessage(){
		$result = false;
		if ($this->ini_array['mail']['action']){
			$subject = "Message du site http://www.espace-nutrition.fr";
			$message = '<html>
							<head>
								<title>Message du site http://www.espace-nutrition.fr</title>
							</head>
							<body>
								Bonjour,
								<br/>
								<br/>
								Message de '.$this->getNom().'
								<br/>
								<br/>
								Email de '.$this->getEmail().'
								<br/>
								<br/>
								Téléphone : '.$this->getTelephone().'
								<br/>
								<br/>
								Message : '.$this->getMessage().'
								<br/>
								<br/>
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

	public function fetchAll(){}
    public function fetchOne(){}
    public function update(){}
    public function create(){}
    protected function save(){}
    public function delete(){}

}
