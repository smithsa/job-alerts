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
		  'city' => 'varchar(255)',
		  'state' => 'varchar(255)',
		  'country' => 'varchar(255)',
		  'category' => 'varchar(255)',
		  'description' => 'text',
		  'apply_url' => 'varchar(255) NOT NULL',
		  'employment_type' => 'varchar(255)',
		  'start_date' => 'datetime',
		  'date_created' => 'datetime NOT NULL',
		  'date_updated' => 'datetime NOT NULL'
	);

	/** 
	* Class constructor
	* @param (associative array) the configuration variable in the config.php file
	*/
	function __construct($config) {
		$this->config = $config;
		$this->database_config = $this->config['database'];
		$this->feed_config = $this->config['feed'];
    }

    /** 
	* Connects to the database
	* @return (MysqliDb) an instance of MysqliDb to handle the db operations
	*/
    public function connectDb() {
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
	public function createTable() {
		$db = $this->connectDb();

		$fields = $this->tableFields;
		$query = 'CREATE TABLE '.$this->tableName.' ( id int AUTO_INCREMENT,';
	
		foreach ($fields as $fieldName => $fieldType) {
			$query.= ' '.$fieldName.' '.$fieldType. ',';
		}
		$query .= ' PRIMARY KEY (id)';
		$query .= ' );';
		$result = $db->rawQuery($query);
		$db->disconnect();
		return is_array($result) ? 1 : 0;
	}

	/** 
	* Populates the database based on the feed defined in the configuration file using the Feed object
	*
	* @return (array) return the ids inserted, will return -1 on an insert failed error
	*/
	public function populateTable() {
		$db = $this->connectDb();
		$feed_ob = new Feed($this->config);
		$feedDataAsSimpleXML = $feed_ob->getXMLFeedObject();

		//gathering xml data into an array to later insert in db
		$jobs_feed_data = Array();
		foreach($feedDataAsSimpleXML as $feedDataPoint){
			array_push($jobs_feed_data, Array(
		          'job_id'  => (string)$feedDataPoint->JobID,
		          'title' => (string)$feedDataPoint->JobTitle,
		          'city' => (string)$feedDataPoint->City,
		          'state' => (string)$feedDataPoint->State,
		          'country' => (string)$feedDataPoint->Country,
		          'category' => (string)$feedDataPoint->Job_Function,
		          'description' => (string)$feedDataPoint->JobDescription,
		          'apply_url' => (string)$feedDataPoint->applyURL,
		          'employment_type' => (string)$feedDataPoint->JobType,
		          'start_date' => (string)$feedDataPoint->startDate.' 00:00',
		          'date_created' => $db->now(),
		          'date_updated' => $db->now()
			));
		}

		$query_result = $db->insertMulti($this->tableName, $jobs_feed_data);
		$db->disconnect();
		return !$query_result ? -1 : $query_result;
	}

	/** 
	* Updates the database based on new jobs added to the feed
	*
	* @return (array) return the ids inserted, will return -1 on an insert failed error
	*/
	public function updateTable() {
		$db = $this->connectDb();

		$db->disconnect();
	}
}