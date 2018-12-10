<?php

require_once dirname(__FILE__).'/../vendor/autoload.php';

class Subscriber{
    public 	$mailChimp_config 	=  array();
    /**
     * Class constructor
     * @param associative array the configuration variable in the config.php file
     */
    function __construct($config) {
        $this->config = $config;
        $this->mailChimp_config = $this->config['mailChimp'];
    }

    /**
     *  gets count of mailchimp members also known as the subscribers
     *
     * @return int which is the of members/subscribers
     */
    public function count(){
        $api_key = $this->mailChimp_config['API_key'];
        $list_id = $this->mailChimp_config['list_id'];
        $dc = substr($api_key,strpos($api_key,'-')+1);
        $url = 'https://'.$dc.'.api.mailchimp.com/3.0/lists/'.$list_id;
        $data = array(
            'offset' => 0,
            'count'  => 50
        );
        $results = json_decode($this->rudr_mailchimp_curl_connect( $url, 'GET', $api_key, $data ));

        $count = $results->stats->member_count;
        return $count;
    }

    /**
     *  gets mailchimp members also known as the subscribers
     *
     * @param offset of the call
     * @param count of the subscribers/members you want to revieve
     *
     * @return array of objects for members/subscribers
     */
    public function get($offset=0, $count = 50){
        $api_key = $this->mailChimp_config['API_key'];
        $list_id = $this->mailChimp_config['list_id'];
        $dc = substr($api_key,strpos($api_key,'-')+1);
        $url = 'https://'.$dc.'.api.mailchimp.com/3.0/lists/'.$list_id.'/members';
        $data = array(
            'offset' => $offset,
            'count'  => $count
        );
        $results = json_decode($this->rudr_mailchimp_curl_connect( $url, 'GET', $api_key, $data ));
        $members = $results->members;
        return $members;
    }

    /**
     *  gets all mailchimp members also known as the subscribers
     *
     * @return array of objects for members/subscribers
     */
    public function getAll(){
        $member_count = $this->count();
        $count = 50;
        $members = array();
        for( $offset = 0; $offset < $member_count; $offset += $count) {
            $requestMembers = $this->get($offset, $count);
            foreach ($requestMembers as $member) {
                $members[] = $member;
            }
        }
        return $members;
    }

    /**
     *  a helper function that makes a curl request to mailchimp's api to get the members/subscibers
     *
     * @param url of the api endpoint
     * @param request type, GET, POST, PUT, PATCH, DELETE
     * @param api key the mailchimp api key
     * @param data to end over to the request
     *
     * @return string, the request in json format
     */
    private function rudr_mailchimp_curl_connect( $url, $request_type, $api_key, $data = array() ) {
        if( $request_type == 'GET' )
            $url .= '?' . http_build_query($data);

        $mch = curl_init();
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Basic '.base64_encode( 'user:'. $api_key )
        );
        curl_setopt($mch, CURLOPT_URL, $url );
        curl_setopt($mch, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($mch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($mch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($mch, CURLOPT_CUSTOMREQUEST, $request_type);
        curl_setopt($mch, CURLOPT_TIMEOUT, 10);
        curl_setopt($mch, CURLOPT_SSL_VERIFYPEER, false);

        if( $request_type != 'GET' ) {
            curl_setopt($mch, CURLOPT_POST, true);
            curl_setopt($mch, CURLOPT_POSTFIELDS, json_encode($data) ); // send data in json
        }

        return curl_exec($mch);
    }
}