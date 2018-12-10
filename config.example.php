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
        'is_augmented_feed' => False
    ],
   	'database' => [
        'host' => '',
        'port' => '',
        'name' => '',
        'username' => '',
        'password' => ''
   	],
    'mail_chimp' => [
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
    	'API_key'=> '',
        'template_name'=> ''
    ],
    'mail' => [
        'from_email_address'=> '',
        'from_name' => '',
        'reply_to' => '',
        'website_url' => '',
        'subaccount' => ''
    ],
    'mail_styles' => [
        'primary_color' => '',
        'secondary_color' => '',
        'font_color' => '',
    ]
];

