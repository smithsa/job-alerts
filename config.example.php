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
        ],
        'is_augmented_feed' => false
    ],
   	'database' => [
        'host' => '',
        'port' => '',
        'name' => '',
        'username' => '',
        'password' => ''
   	],
    'mailChimp' => [
        'list_id' => '',
        'API_key' => '',
        'interests' => [
            [
                'id' => '',
                'name' => ''
            ]
        ]
    ],
    'mandrill' => [
    	'API_key'=> ''
    ]
];

