# Shaker Job Alerts

Library to send job alerts of custom job feeds using MailChimp and Mandrill

## Dependencies

*	[Composer](https://getcomposer.org/download/)

## Installation

1. Clone the repository
```
	git clone git@github.com:smithsa/job-alerts.git
```

2. Navigate the repository folder

3. Install Dependencies with Composer

```
	composer install
```

4. rename `config.example.php` to `config.php` and fill out appropriate configuration fields.

For the `feed=>map_fields` array field  please note that active date is considered to be the `date_updated` field so add an additional array field 
```
	'date_updated' => ''
```

If using an augmented feed, then `feed=>map_fields` is required. The fields required for `map_fields` entries are:
    - job_id
    - title
    - apply_url
 
In addition set `is_augmented_feed` to true if supplementing the feed.
```
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
```

	
## Considerations

Augmented feeds are feeds that need a date attached. The Feed Class can generate a json feed.
An example of hoe to generate the feed can be found in `examples/augmented-feeds/generate-feed.php` and the feed itself
can be found in `examples/augmented-feeds/feed`


## Built With

*	[PHP-MySQLi-Database-Class](https://github.com/ThingEngineer/PHP-MySQLi-Database-Class)
*   PHP
*   Mandrill API
*   Mailchimp API