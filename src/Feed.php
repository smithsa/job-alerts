<?php
require_once dirname(__FILE__).'/../vendor/autoload.php';

$config = include('config.php');
$database_config = $config['database'];
$feed_config = $config['feed'];

class Feed {
	function __construct($config) {
		$this->feed_config = $this->config['feed'];
    }
	public getXMLFeed(){
		$feed_url = $this->feed_config['url'];
		$feed_username = $this->feed_config['username'];
		$feed_password = $this->feed_config['password'];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

		if(!empty($feed_username) && !empty($feed_username)){
			$auth_info = ;
			curl_setopt($ch, CURLOPT_USERPWD, "ISU_INT010_Linkedin:Workday2016!!");
		}
		
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	public convertXMLStringtoPHPObject($xml_string){
		return simplexml_load_string($xml_string);
	}
	public getXMLFeedObject(){
		$xml_string = $this->getXMLFeed();
		return $this->convertXMLStringtoPHPObject($xml_string);
	}
}