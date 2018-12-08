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
	* @param associative array the configuration variable in the config.php file
	*/
	function __construct($config) {
		$this->config = $config;
		$this->database_config = $this->config['database'];
		$this->feed_config = $this->config['feed'];
    }

    /** 
	* Connects to the database
	* @return object an instance of MysqliDb to handle the db operations
	*/
    public function connectDb() {
            try{
                $db = new MysqliDb (
                    Array (
                        'host' => $this->database_config['host'],
                        'username' => $this->database_config['username'],
                        'password' => $this->database_config['password'],
                        'db'=> $this->database_config['name'],
                        'port' => $this->database_config['port'],
                        'charset' => 'utf8'));
            }catch(\Exception $e){
                throw new Exception('Unable to establish connection with database.'.$e->getMessage());
                return;
            }
    		return $db;
    }

	/** 
	* Creates the feed table
	* @return int 1 if the creation is successful, otherwise 0
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
	* @return array return the ids inserted, will return -1 on an insert failed error
	*/
	public function populateTable() {
		$db = $this->connectDb();
		$feed_ob = new Feed($this->config);

		$map_fields = $this->config['feed']['map_fields'];
		$feed_data_as_simple_xml = $feed_ob->getXMLFeedObject();

		//gathering xml data into an array to later insert in db
		$jobs_feed_data = Array();
		foreach($feed_data_as_simple_xml as $feed_data_point){
            $job_id = (!empty($map_fields['job_id'])) ?  (string)$feed_data_point->{$map_fields['job_id']} : '';
            $title = (!empty($map_fields['title'])) ?  (string)$feed_data_point->{$map_fields['title']} : '';
            $city = (!empty($map_fields['city'])) ?  (string)$feed_data_point->{$map_fields['city']} : '';
            $state = (!empty($map_fields['state'])) ?  (string)$feed_data_point->{$map_fields['state']} : '';
            $country = (!empty($map_fields['country'])) ?  (string)$feed_data_point->{$map_fields['country']} : '';
            $category = (!empty($map_fields['category'])) ?  (string)$feed_data_point->{$map_fields['category']} : '';
            $description = (!empty($map_fields['description'])) ?  (string)$feed_data_point->{$map_fields['description']} : '';
            $apply_url = (!empty($map_fields['apply_url'])) ?  (string)$feed_data_point->{$map_fields['apply_url']} : '';
            $employment_type = (!empty($map_fields['employment_type'])) ?  (string)$feed_data_point->{$map_fields['employment_type']} : '';
            $start_date = (!empty($map_fields['start_date'])) ? new Datetime((string)$feed_data_point->{$map_fields['start_date']}) : '';
            $start_date = (!empty($start_date)) ? $start_date->format('Y-m-d h:i:s') : '';

            array_push($jobs_feed_data, Array(
                'job_id'  => $job_id,
                'title' => $title,
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'category' => $category,
                'description' => $description,
                'apply_url' => $apply_url,
                'employment_type' => $employment_type,
                'start_date' => $start_date,
                'date_created' => $db->now(),
                'date_updated' => $db->now()
            ));
		}

		$query_result = $db->insertMulti($this->tableName, $jobs_feed_data);
		$db->disconnect();
		return !$query_result ? -1 : $query_result;
	}

	/** 
	* Deletes entries in database but not in the feed. Jobs removed from feed.
	*
	* @return array return the ids deleted entries
	*/
	public function deleteJobEntriesTable() {
		function addStringQoutes($job_id){
			return "'".$job_id."'";
		}
		$db = $this->connectDb();
		$feed_ob = new Feed($this->config);
		$ids_in_feed = $feed_ob->getJobIDsFromFeed();
		$ids_in_feed = array_map("addStringQoutes", $ids_in_feed );
		$query = "DELETE FROM ".$this->tableName." WHERE job_id NOT IN (".implode(', ', $ids_in_feed).");";
		$result = $db->rawQuery($query);
		$db->disconnect();
		return $result;
	}

	/** 
	* Updates the database based on new jobs added to the feed
     *
	* @return array return the ids inserted, will return -1 on an insert failed error
	*/
	public function updateTable() {
		function returnJobID($arrayItem){
			return $arrayItem['job_id'];
		}

		$db = $this->connectDb();
		$feed_ob = new Feed($this->config);

		$map_fields = $this->config['feed']['map_fields'];
		$updated_feed_data_as_simple_xml = $feed_ob->getXMLFeedObject();
		$job_ids_in_db_query_results = $db->get($this->tableName, null, array('job_id'));
		$job_ids_in_db = array_map("returnJobID", $job_ids_in_db_query_results);
		$updated_jobs_feed_data = Array();

		foreach($updated_feed_data_as_simple_xml as $feed_data_point){
		    if(empty($map_fields['job_id'])){
                throw new Exception('The field job_id needs to be defined in config.php');
            }
			if(!in_array((string)$feed_data_point->{$map_fields['job_id']}, $job_ids_in_db)){
                $job_id = (!empty($map_fields['job_id'])) ?  (string)$feed_data_point->{$map_fields['job_id']} : '';
                $title = (!empty($map_fields['title'])) ?  (string)$feed_data_point->{$map_fields['title']} : '';
                $city = (!empty($map_fields['city'])) ?  (string)$feed_data_point->{$map_fields['city']} : '';
                $state = (!empty($map_fields['state'])) ?  (string)$feed_data_point->{$map_fields['state']} : '';
                $country = (!empty($map_fields['country'])) ?  (string)$feed_data_point->{$map_fields['country']} : '';
                $category = (!empty($map_fields['category'])) ?  (string)$feed_data_point->{$map_fields['category']} : '';
                $description = (!empty($map_fields['description'])) ?  (string)$feed_data_point->{$map_fields['description']} : '';
                $apply_url = (!empty($map_fields['apply_url'])) ?  (string)$feed_data_point->{$map_fields['apply_url']} : '';
                $employment_type = (!empty($map_fields['employment_type'])) ?  (string)$feed_data_point->{$map_fields['employment_type']} : '';
                $start_date = (!empty($map_fields['start_date'])) ? new Datetime((string)$feed_data_point->{$map_fields['start_date']}) : '';
                $start_date = (!empty($start_date)) ? $start_date->format('Y-m-d h:i:s') : '';

                array_push($updated_jobs_feed_data, Array(
                    'job_id'  => $job_id,
                    'title' => $title,
                    'city' => $city,
                    'state' => $state,
                    'country' => $country,
                    'category' => $category,
                    'description' => $description,
                    'apply_url' => $apply_url,
                    'employment_type' => $employment_type,
                    'start_date' => $start_date,
                    'date_created' => $db->now(),
                    'date_updated' => $db->now()
                ));
			}
		}

		$query_result = $db->insertMulti($this->tableName, $updated_jobs_feed_data);
		$db->disconnect();
		return !$query_result ? -1 : $query_result;
	}

    /**
     * Will get the jobs feed in XML from the database
     *
     * @return array the jobs feed as array
     */
    public function getFeedFromDatabase() {
        $db = $this->connectDb();
        $jobsResult = $db->get($this->tableName);
        return $jobsResult;
    }
}