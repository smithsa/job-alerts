<?php

return [
    'feed' => [
      'url' => '',
    	'username' => '',
    	'password' => '',
      'map_fields' => [ 
          'job_id'  => '',
          'title' => '',
          'city' => '',
          'state' => '',
          'country' => '',
          'category' => '',
          'description' => '',
          'apply_url' => '',
          'employment_type' => '',
          'start_date' => ''
      ]
    ],
   	'database' => [
   		'host' => '',
   		'port' => '',
   		'username' => '',
   		'password' => ''
   	],
    'mailChimp' => [
    	'username' => '',
      'list_id' => '',
    	'API_key' => ''
    ],
    'mandrill' => [
    	'username'=> '',
    	'API_key'=> ''
    ]
    'alerts' => [
      'createFeed' =>  false
    ]
];

