<?php

require_once dirname(__FILE__).'/../vendor/autoload.php';

/**
 * Class to interact with the database defined in the configuration file
 */
class FeedDatabase {    
	public 	$config 	=  array();
	private $tableName 		= 'jobs_alerts_feed';
	private $tableFields 	= array (
		  'job_id'  => 'varchar(255) NOT NULL',
		  'title' => 'varchar(255) NOT NULL',
		  'city' => 'varchar(255) NOT NULL',
		  'state' => 'varchar(255) NOT NULL',
		  'country' => 'varchar(255) NOT NULL',
		  'category' => 'varchar(255) NOT NULL',
		  'description' => 'text NOT NULL',
		  'apply_url' => 'varchar(255) NOT NULL',
		  'employment_type' => 'varchar(255) NOT NULL',
		  'start_date' => 'datetime NOT NULL',
		  'date_created' => 'datetime NOT NULL',
		  'date_updated' => 'datetime NOT NULL'
	);

	function __construct($config) {
		$this->config = $config;
		$this->database_config = $this->config['database'];
		$this->feed_config = $this->config['feed'];
    }

    /** 
	* Connects to the database
	* @return (MysqliDb) an instance of MysqliDb to handle the db operations
	*/
    public function connectDb(){
    		$db = new MysqliDb (
			Array (
                'host' => $this->database_config['host'],
                'username' => $this->database_config['username'], 
                'password' => $this->database_config['password'],
                'db'=> $this->database_config['name'],
                'port' => $this->database_config['port'],
                'charset' => 'utf8'));
    		return $db;
    }

	/** 
	* Creates the feed table
	* @return (int) 1 if the creation is successful, otherwise 0
	*/
	public function createTable(){
		$db = $this->connectDb();

		$fields = $this->tableFields;
		$query = 'CREATE TABLE '.$this->tableName.' ( id int NOT NULL AUTO_INCREMENT,';
	
		foreach ($fields as $fieldName => $fieldType) {
			$query.= ' '.$fieldName.' '.$fieldType. ',';
		}
		$query .= ' PRIMARY KEY (id)';
		$query .= ' );';
		$result = $db->rawQuery($query);
		var_dump($result);
		return is_array($result) ? 1 : 0;
	}

	/** 
	* Populates the database based on the feed defined in the configuration file using the Feed object
	*
	* @return (int) 1 if the creation is successful, otherwise 0
	*/
	public function populateTable(){
		$db = $this->connectDb();
	}
}