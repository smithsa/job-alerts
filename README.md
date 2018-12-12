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
       'start_date' => '',
       'date_created' => '',
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
     'entity' => 'Job Alerts'
 ],
 'mail_styles' => [
     'primary_color' => '',
     'secondary_color' => '',
     'font_color' => '',
 ]
```

The fields required for `map_fields` entries are:
*   job_id
*   title
*   job_url


In addition set `is_augmented_feed` to true if you are using the database to search the feed.

## Usage


## Considerations


## Built With

*   MYSQL
*   PHP
*   Mandrill
*   Mailchimp
*	[PHP-MySQLi-Database-Class](https://github.com/ThingEngineer/PHP-MySQLi-Database-Class)