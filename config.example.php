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
          'start_date' => '',
          'date_created' => ''
        ],
        'is_augmented_feed' => True
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
        'email_subject' => '',
        'from_email_address'=> '',
        'from_name' => '',
        'reply_to' => '',
        'website_url' => '',
        'subaccount' => '',
        'update_preference_link' => '',
        'unsubscribe_link' => '',
        'entity' => 'Talent Community'
    ],
    'mail_styles' => [
        'primary_color' => '',
        'secondary_color' => '',
        'font_color' => '',
    ]
];

