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

4. rename config.example.php to config.php and fill out appropriate configuration fields.

For the feed=>map_fields array field  please note that active date is considered to be the 'date_updated' field so add an additional array field 
```
	'date_updated' => ''
```
	



## Built With

*	[PHP-MySQLi-Database-Class](https://github.com/ThingEngineer/PHP-MySQLi-Database-Class)