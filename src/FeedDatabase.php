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

		$result = 0;
		if (!$db->tableExists($this->tableName)){
			$fields = $this->tableFields;
			$query = 'CREATE TABLE '.$this->tableName.' ( id int AUTO_INCREMENT,';
		
			foreach ($fields as $field_name => $fieldType) {
				$query.= ' '.$field_name.' '.$fieldType. ',';
			}
			$query .= ' PRIMARY KEY (id)';
			$query .= ' );';
			$result = $db->rawQuery($query);
		}
		
		$db->disconnect();
		return is_array($result) ? 1 : $result;
	}

	/** 
	* Populates the database based on the feed defined in the configuration file using the Feed object
	*
	* @return (array) return the ids inserted, will return -1 on an insert failed error
	*/
	public function populateTable() {
		$db = $this->connectDb();
		$feed_ob = new Feed($this->config);
		$feed_data_as_simple_xml = $feed_ob->getXMLFeedObject();

		//gathering xml data into an array to later insert in db
		$jobs_feed_data = Array();
		foreach($feed_data_as_simple_xml as $feed_data_point){
			array_push($jobs_feed_data, Array(
		          'job_id'  => (string)$feed_data_point->JobID,
		          'title' => (string)$feed_data_point->JobTitle,
		          'city' => (string)$feed_data_point->City,
		          'state' => (string)$feed_data_point->State,
		          'country' => (string)$feed_data_point->Country,
		          'category' => (string)$feed_data_point->Job_Function,
		          'description' => (string)$feed_data_point->JobDescription,
		          'apply_url' => (string)$feed_data_point->applyURL,
		          'employment_type' => (string)$feed_data_point->JobType,
		          'start_date' => (string)$feed_data_point->startDate.' 00:00',
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
		function returnJobID($arrayItem){
			return $arrayItem['job_id'];
		}

		$db = $this->connectDb();
		$feed_ob = new Feed($this->config);
		$updated_feed_data_as_simple_xml = $feed_ob->getXMLFeedObject();
		$job_ids_in_db_query_results = $db->get($this->tableName, null, array('job_id'));
		$job_ids_in_db = array_map("returnJobID", $job_ids_in_db_query_results);
		$updated_jobs_feed_data = Array();

		foreach($updated_feed_data_as_simple_xml as $feed_data_point){
			if(!in_array((string)$feed_data_point->JobID, $job_ids_in_db)){
				array_push($updated_jobs_feed_data, Array(
			          'job_id'  => (string)$feed_data_point->JobID,
			          'title' => (string)$feed_data_point->JobTitle,
			          'city' => (string)$feed_data_point->City,
			          'state' => (string)$feed_data_point->State,
			          'country' => (string)$feed_data_point->Country,
			          'category' => (string)$feed_data_point->Job_Function,
			          'description' => (string)$feed_data_point->JobDescription,
			          'apply_url' => (string)$feed_data_point->applyURL,
			          'employment_type' => (string)$feed_data_point->JobType,
			          'start_date' => (string)$feed_data_point->startDate.' 00:00',
			          'date_created' => $db->now(),
			          'date_updated' => $db->now()
				));
			}
		}

		$query_result = $db->insertMulti($this->tableName, $updated_jobs_feed_data);
		$db->disconnect();
		return !$query_result ? -1 : $query_result;
	}
}