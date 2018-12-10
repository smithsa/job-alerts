<?php

class Interest {
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
     *  gets the interest by id field
     *
     * param interest_id is a string representing the interest id for mailchimp
     * @return array of mailchimp interests
     */
    public function getInterest($interest_id) {
        $api_key = $this->mailChimp_config['API_key'];
        $list_id = $this->mailChimp_config['list_id'];
        $dataCenter = substr($api_key,strpos($api_key,'-')+1);

        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/'.$list_id.'/interest-categories/'.$interest_id.'/interests?count=100';

        $result = $this->mailchimp_curl_request($url, $api_key, 'GET');

        $interest_array = array();

        foreach (json_decode($result)->interests as $interest) {
            $interest_array[$interest->id] = $interest->name;
        }

        return $interest_array;
    }

    /**
     *  gets the interest by id field
     *
     * @param array of strings represting interest ids
     * @return array of mailchimp interests
     */
    public function getInterests($interest_ids = array()) {
        $all_interests_array = array();
        foreach($interest_ids as $interest_id){
            $interest = $this->getInterest($interest_id);
            array_push($all_interests_array, $interest);
        }

        return $all_interests_array;

    }

    /**
     *  helper array to submit an api get request to mailchimp
     *
     * @param url of endpoint to submit to
     * @param apikey for mailchimp
     * @param request type GET, POST, PUT, PATCH, DELETE
     *
     * @return string formatted in json from mailchimp  response
     */
    private function mailchimp_curl_request($url, $apiKey, $requesttype){
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requesttype);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            var_dump($httpCode);
            return $result;
    }

}