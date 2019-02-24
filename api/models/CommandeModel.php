<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class CommandeModel extends AbstractModel {

	protected $_ref; 
	protected $_nom;
	protected $_prenom;
	protected $_adresse;
	protected $_email;
	protected $_telephone;
	protected $_acceptation;
	protected $_moyen;
	protected $_libelle;
	protected $_montant;
	protected $_dateheure;
	
	protected $_indexMinDem;
	protected $_indexMaxDem;
	protected $_nbCommandes;
	protected $_indexMin;
	protected $_indexMax;
	protected $_nbCommandeParPage;
	
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
	
	public function setRef($_ref)
    {
        $this->_ref = $_ref;
        return $this;
    }

    public function getRef()
    {
        return $this->_ref;
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
	
	public function setPrenom($_prenom)
    {
        $this->_prenom = $_prenom;
        return $this;
    }

    public function getPrenom()
    {
        return $this->_prenom;
    }
	
	public function setAdresse($_adresse)
    {
        $this->_adresse = $_adresse;
        return $this;
    }

    public function getAdresse()
    {
        return $this->_adresse;
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
	
	public function setAcceptation($_acceptation)
    {
        $this->_acceptation = $_acceptation;
        return $this;
    }

    public function getAcceptation()
    {
        return $this->_acceptation;
    }
	
	public function setMoyen($_moyen)
    {
        $this->_moyen = $_moyen;
        return $this;
    }

    public function getMoyen()
    {
        return $this->_moyen;
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
	
	public function setMontant($_montant)
    {
        $this->_montant = $_montant;
        return $this;
    }

    public function getMontant()
    {
        return $this->_montant;
    }
	
	public function setDateHeure($_dateheure)
    {
        $this->_dateheure = $_dateheure;
        return $this;
    }

    public function getDateHeure()
    {
        return $this->_dateheure;
    }

	public function setNbCommandes($_nbCommandes)
    {
        $this->_nbCommandes = $_nbCommandes;
        return $this;
    }

    public function getNbCommandes()
    {
        return $this->_nbCommandes;
    }

	public function setNbCommandesParPage($_nbCommandesParPage)
    {
        $this->_nbCommandesParPage = $_nbCommandesParPage;
        return $this;
    }

    public function getNbCommandesParPage()
    {
        return $this->_nbCommandesParPage;
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
		

		return $result;
	}


	public function fetchOne(){
		$result = false;

		

		return $result;

	}
	
	
	public function update(){
		$result=true;
        
		
		return $result; 
	}
	
	public function create(){
		$result = true;
        try{
			$this->openConnectionDatabase();
			
			// Exécution des requêtes SQL
			$query=sprintf("INSERT INTO commandes (ref, nom, prenom, adresse, email,telephone,acceptation,libelle,montant,moyen) values ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",mysqli_real_escape_string($this->dblink,$this->getRef()),mysqli_real_escape_string($this->dblink,$this->getNom()),mysqli_real_escape_string($this->dblink,$this->getPrenom()),mysqli_real_escape_string($this->dblink,$this->getAdresse()),mysqli_real_escape_string($this->dblink,$this->getEmail()),mysqli_real_escape_string($this->dblink,$this->getTelephone()),mysqli_real_escape_string($this->dblink,$this->getAcceptation()),mysqli_real_escape_string($this->dblink,$this->getLibelle()),mysqli_real_escape_string($this->dblink,$this->getMontant()),mysqli_real_escape_string($this->dblink,$this->getMoyen()));
 
			$mysql_result = mysqli_query($this->dblink,$query);
			if (!$mysql_result){
				$this->setError($query);
				$result=false;
			}  
		}catch(Exception $e)
		{
			$this->setError($e);
		} 
		
		$this->closeConnectionDatabase();
   
		return $result;
	}
	
	public function sendMessageUtilisateur(){
		$result = false;
		if ($this->ini_array['mail']['action']){
			$subject = "Votre commande sur le site Espace-Nutrition";
			
			if ($this->getMoyen()=='virement'){
			
				$message = '<html>
								<head>
									<title>Votre commande sur http://www.espace-nutrition.fr</title>
								</head>
								<body>
									Bonjour '.$this->getPrenom().',
									<br/>
									<br/>
									Vous avez commandé '.$this->ini_array['forfait'][$this->getRef().'.libellelong'].' d\'un montant de '.$this->getMontant().' euros.
									<br/>
									<br/>
									Je vous contacterai par email sous 24 heures pour programmer la consultation.
									<br/>
									<br/>
									Voici le lien qui permet d\'accéder à mon RIB pour procéder au paiement de votre suivi diététique : <a href="http://www.espace-nutrition.fr/files/rib.pdf">http://www.espace-nutrition.fr/files/rib.pdf</a>
									<br/>
									<br/>
									A réception du règlement, vous recevrez la facture que je vous invite à transmettre à votre assurance complétementaire car en fonction de votre contrat, il est parfois possible d\'obtenir une prise en charge des consultations diététiques.
									<br/>
									<br/>
									Je vous remercie de votre confiance
									<br/>
									<br/>
									A très bientôt,
									<br/>
									<br/>
									Angélique Guehl<br/>
									Diététicienne nutritionniste<br/>
									355, rue de l\'aunis<br/>
									79230 Aiffres<br/>
									Tél : 0668007915<br/>
									Mail : angelique.guehl@espace-nutrition.fr
								</body>
							</html>';
			}else{
				$message = '<html>
								<head>
									<title>Votre commande sur http://www.espace-nutrition.fr</title>
								</head>
								<body>
									Bonjour '.$this->getPrenom().',
									<br/>
									<br/>
									Vous avez commandé '.$this->ini_array['forfait'][$this->getRef().'.libellelong'].' d\'un montant de '.$this->getMontant().' euros.
									<br/>
									<br/>
									Je vous contacterai par email sous 24 heures pour programmer la consultation.
									<br/>
									<br/>
									Vous avez choisi de régler votre suivi diététique par chèque. Pour cela, je vous invite à m\'envoyer le règlement à l\'adresse suivante :
									<br/>
									<br/>
									Angélique GUEHL<br/>
									355, rue de l\'aunis<br/>
									79230 Aiffres
									<br/>
									<br/>
									Veillez à bien orthographier mon nom de famille s\'il vous plait.
									<br/>
									<br/>
									A réception du règlement, vous recevrez la facture que je vous invite à transmettre à votre assurance complétementaire car en fonction de votre contrat, il est parfois possible d\'obtenir une prise en charge des consultations diététiques.
									<br/>
									<br/>
									Si vous préférez régler en plusieurs fois, contactez-moi au 0668007915 ou par email : angelique.guehl@espace-nutrition.fr pour que je vous communique la marche à suivre.
									<br/>
									<br/>
									Je vous remercie de votre confiance
									<br/>
									<br/>
									A très bientôt,
									<br/>
									<br/>
									Angélique Guehl<br/>
									Diététicienne nutritionniste<br/>
									355, rue de l\'aunis<br/>
									79230 Aiffres<br/>
									Tél : 0668007915<br/>
									Mail : angelique.guehl@espace-nutrition.fr
								</body>
							</html>';
			}

			// Pour envoyer un mail HTML, l\'en-tête Content-type doit être défini
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

			// En-têtes additionnels
			$headers .= 'From: Espace Nutrition <contact@espace-nutrition.fr>' . "\r\n";

			//Envoi du mail
			try
			{
				$result = mail ($this->getEmail(),$subject,$message,$headers);
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
	
	public function sendMessageAdmin(){
		$result = false;
		if ($this->ini_array['mail']['action']){
			$subject = "Une commande sur le site Espace-Nutrition";
			
			$now = date_create()->format('d-m-Y H:i:s');
			
			$message = '<html>
							<head>
								<title>Une commande sur http://www.espace-nutrition.fr</title>
							</head>
							<body>
								Bonjour Angélique,
								<br/>
								<br/>
								Une commande a été passée sur le site Espace-Nutrition:
								<br/>
								<br/>
								Nom : '.$this->getNom().'<br/>
								Prenom : '.$this->getPrenom().'<br/>
								Email : '.$this->getEmail().'<br/>
								Telephone : '.$this->getTelephone().'<br/>
								Adresse : '.$this->getAdresse().'<br/>
								Acceptation conditions générales de ventes : '.$this->getAcceptation().'<br/>
								Type commande : '.$this->getLibelle().'<br/>
								Montant commande : '.$this->getMontant().'<br/>
								Type paiement : '.$this->getMoyen().'<br/>
								Date/Heure acceptation : '.$now.'
								<br/>
								<br/>
								A très bientôt,
							</body>
						</html>';
			

			// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

			// En-têtes additionnels
			$headers .= 'From: Espace Nutrition <contact@espace-nutrition.fr>' . "\r\n";

			//Envoi du mail
			try
			{
				$result = mail ('angelique.guehl@espace-nutrition.fr',$subject,$message,$headers);
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

       
		return $result;
    }
	public function save(){
		return true;
	}
    
}
