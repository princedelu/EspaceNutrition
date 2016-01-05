<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class ArticleModel extends AbstractModel {

	protected $_id;

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

	public function toArray()
    {
        return array (
            'id' => $this->getId(),
			'email' => $this->getEmail(),
			'datemesure' => $this->getDateMesure(),
			'commentaire' => $this->getCommentaire()
        );
    }

	/*
	* Récupération de touq les articles
	*/
    public function fetchAll()
    {
        return true;
    }


    /**
     * Fetch one model from storage
     * @param $id
     * @return array
     */
    public function fetchOne()
    {
       return true;
    }

    /**
     *
     * @return $this|bool
     */
    public function update()
    {
		return true;
    }

    public function create()
    {
		return true;
    }

    public function delete()
    {
		return true;
    }
	
	protected function save()
    {
       return true;
    }
}

