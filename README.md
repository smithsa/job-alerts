# Shaker Marketing Job Alerts

Library to send job alerts of custom job feeds using MailChimp and Mandrill.

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

4. rename `config.example.php` to `config.php` and fill out appropriate configuration fields. Some of the fields will have to be added during setup of your Mailchimp and Mandrill components. See configuration section below for more information:

| Property | Description |required
| --- | --- |--- |
| **feed** | | |
|    url | url of the jobs feed you are using (str)  | true |
|    username | username for jobs feed you are using (str) | true |
|   password |  password for the jobs feed you are using (str) | true |
|   job_url_base | base url where the job listing lives (str) | true |
|   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ***map_fields*** |  |  |
|   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; job_id |  | true |
|   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; title |  | true |
|   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; city |  |  |
|   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; state |  |  |
|   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; country |  |  |
|   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; category |  |  |
|   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; description |  |  |
|   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; apply_url |  |  |
|   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; employment_type |  | true |
|   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; start_date |  |  |
|   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; date_created |  |  |
|   is_augmented_feed | true if the feed needs to be augumented with a job posted date (boolean) |  |
| **database** |  | |
host | hostname of the database (str)  | true, if is_augmented_feed is true |
port | port of the database (str)  | true, if is_augmented_feed is true |
name | database name (str)  | true, if is_augmented_feed is true |
username | database username (str)  | true, if is_augmented_feed is true |
password | database password (str)  | true, if is_augmented_feed is true |
| **mail_chimp** |  | |
list_id | mailchimp list id (str)  | true |
API_key | mailchimp API key (str)  | true |
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ***interests*** |   |  |
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; id | id of mail chimp list interest   |  |
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; name | name of mail chimp list interest  |  |
| **mandrill** |  | |
API_key | mandrill api key (str)  | true |
template_name | the template name you are using for the mandreill email (str)  | true |
| **mail** |  | |
email_subject  | subject of the job alert email (str)  | true |
from_email_address  | from adress of the job alert email (str)  | true |
from_name  | from name of the job alert email (str)  | true |
reply_to  | the reply to of the job alert email (str)  | true |
website_url  | the main website of the organization you are sending from for purposes of metadata(str)  | true |
subaccount  | the mandrill subacount (str)  | true |
update_preference_link  | link you will send the user for updating email preferences (str)  | true |
unsubscribe_link  | link you will send the user for unsubscribing from the job alert (str)  | true |
add_subscriber  | true if you want to add the subscriber id in the unsubscribe and update preference link, the subscriber id is hashed (str)  | true |
entity  | what the email representss, typically will be set to 'Job Alert' (str)  | true |
| **mail_styles** |  | |
primary_color  | hex for the primary color of the email (str)  | true |
body_font_color  | hex for the body's font color of the email (str)  | true |
footer_background_color  | the footer's background color (str)  | true |
footer_font_color  | the footer's font color (str)  | true |

## Usage
For more examples of how to use the library you can access the `examples` folder at the root project.

### Getting Started
#### Mailchimp Setup
##### 1. Create a list in your Mailchimp account
[Instructions for setting up a Mailchimp List](https://mailchimp.com/help/create-list/)

##### 2. Add Merge Fields to Your Mailchimp List

Add the following required merge tags

| Property | Description |required
| --- | --- |--- |
| FNAME | subscriber first name | true |
| LNAME | subscriber last name |  |
| ALERTTYPE | the type of alert, either 'Daily' or 'Weekly' (weekly sent on Tuesdays) | true |
| CAT1 | The job category or job function | true |
| CAT2 | The job category or job function |  |
| STATE | State preference for the user |  true |

#### Mandrill Setup

##### 1. Create Email Template
The first step will be creating a template in Mandrill. To create a Mandrill template, go to: Outbound > Templates. Publish the template.

An example of how the configuration file should look can be found in `email-template-base/example-job-alerts-mandril-template.html`. Please note the Mailchimp merge tags exmaple below. Add them as neccessary but these are the only two needed.
```
*|SUBJECT|*
*|MC_PREVIEW_TEXT|*
```
Fill those in as appropriate.

Other varaibles are for the template parts that are required you will recieve from the email templates of this library. For more information view the `src/Email.php` and `src/EmailTemplate.php` files. The div with `mc:edit="body"` will be the body area of the email template, `mc:edit="body"` the jobs area, and `mc:edit="footer"` the area for the footer.
```
mc:edit="jobs"
mc:edit="body"
mc:edit="footer"
```
Make sure the name of the template you just created is added to the configuration file in `config.php`. The field will be `mandrill => template_name`.
##### 2. Add Blocks
Please note, Mandrill account will need blocks to send these email job alerts. Make sure bloks are allocated. You can add these through Mailchimp or Mandrill.

### Import Configuration
```
$config = include('your-path/config.php);
```
### Augmented Feeds

#### Initialize Database Table for Feeds
```
require(dirname(__FILE__).'/src/FeedDatabase.php');

$feedDb = new FeedDatabase($config);
$dbCreateResult = $feedDb->createTable();
```
#### Populate the Database with Job Positings from the Feed
```
require(dirname(__FILE__).'/src/FeedDatabase.php');

$feedDb = new FeedDatabase($config);
$dbUpdateResult = $feedDb->populateTable();
```

#### Search Database Feed by Location and Categories
```
require(dirname(__FILE__).'/src/FeedDatabase.php');
$feedDb = new FeedDatabase($config);
// first parameter is the location, second is the categories.
$searchResults = $feedDb->searchFeedDatabase(array('*'), array('*'));
```

#### Remove Old Job Entries in Database
```
require(dirname(__FILE__).'/src/FeedDatabase.php');
$feedDb = new FeedDatabase($config);
$dbDeletedResult = $feedDb->deleteJobEntriesTable();
```

#### Add New Job Entries in Database
```
require(dirname(__FILE__).'/src/FeedDatabase.php');
$feedDb = new FeedDatabase($config);
$dbPopulateResult = $feedDb->updateTable();
```

#### Overall Update of Jobs in the Database
This will delete older jobs first then run an update
```
require(dirname(__FILE__).'/src/FeedDatabase.php');

$feedDb = new FeedDatabase($config);
$dbDeletedResult = $feedDb->deleteJobEntriesTable();
$dbPopulateResult = $feedDb->updateTable();
```

#### Get Augmented Feed as a JSON
```
require(dirname(__FILE__).'/src/FeedDatabase.php');

$feedDb = new FeedDatabase($config);
$feed = new Feed($config);
$dbGetResult = $feedDb->getFeedFromDatabase();
$feedResult = $feed->getJSONString($dbGetResult);
```

### Non-Augmented Feed and Augmented Feed

#### Get Mailchimp Interests
```
require(dirname(__FILE__).'/src/Interest.php');

$interest = new Interest($config);
$interestResult = $interest->get();
```

#### Get Mailchimp Subscibers
```
require(dirname(__FILE__).'/src/Subscriber.php');

$subscriber = new Subscriber($config);
$members = $subscriber->getAll();
```
#### Send Job Alerts to Subscribers
If you want to change the email template partials for the jobs section, body or footer of the email Mandrill Email template, then create a child class of `EmailTemplate`.
```
require(dirname(__FILE__).'/src/JobAlert.php');
require(dirname(__FILE__).'/src/Subscriber.php');
require(dirname(__FILE__).'/examples/ExampleEmailTemplate.php');

$subscriberObject = new Subscriber($config);
$subscribers = $subscriberObject->getAll();
$jobAlertObject = new JobAlert($config);

//can create an object which is a child of EmailTemplate or just add a regular EmailTemplate object
$emailTemplate = new ExampleEmailTemplate($config);
$emailTemplate->description_is_sanitized = true;

$jobAlertResult = $jobAlertObject->sendAlerts($subscribers, $emailTemplate, $config['mail']['email_subject'], True);
```
### Email Template Class
The email template class of this library is used to create the body, jobs, and footer partial of the Mandrill email temple. You want to create a child class if you need to change the default structure or sanitize the content you recieved from the feed. An example of this can be seen in `examples/ExampleEmailTemplate.php`

## Considerations
- Unsubscribe and update preference for the job alert email page must be built outside of this library.
- Mandrill account will need blocks to send these email job alerts. Make sure bloks are allocated. (See above in Mandrill Setup)[#mandrill-setup]
- If you want to change the email template partials for the jobs section, body or footer of the email Mandrill Email template, then create a child class of `EmailTemplate`. See the above [Email Template Class](#email-template-class)

## Built With

*   MYSQL
*   PHP
*   Mandrill
*   Mailchimp
*	[PHP-MySQLi-Database-Class](https://github.com/ThingEngineer/PHP-MySQLi-Database-Class)
