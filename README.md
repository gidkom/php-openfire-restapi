php-openfire-restapi
=====================

[![Build Status](https://scrutinizer-ci.com/g/gidkom/php-openfire-restapi/badges/build.png?b=master)](https://scrutinizer-ci.com/g/gidkom/php-openfire-restapi/build-status/master)


A simple PHP class designed to work with Openfire Rest Api plugin. It is used to remote manage the Openfire server.

## LICENSE
php-openfire-restapi is licensed under MIT style license, see LICENCE for further information.

## REQUIREMENTS
- PHP 5.4+

## INSTALLATION

### With Composer
-------------
The easiest way to install is via [composer](http://getcomposer.org/). Create the following `composer.json` file and run the `composer.phar` install command to install it.

```json
{
    "require": {
        "gidkom/php-openfire-restapi": "dev-master"
    }
}
```

## USAGE
```php
include "vendor/autoload.php";

// Create the OpenfireUserservice object
$api = new Gidkom\OpenFireRestApi\OpenFireRestApi

// Set the required config parameters
$api->secret = "MySecret";
$api->host = "jabber.myserver.com";
$api->port = "9090";  // default 9090

// Optional parameters (showing default values)

$api->useSSL = false;
$api->plugin = "/plugins/restapi/v1";  // plugin 

// Add a new user to OpenFire and add to a group
$result = $api->addUser('Username', 'Password', 'Real Name', 'johndoe@domain.com', array('Group 1'));

// Check result if command is succesful
if($result['status']) {
    // Display result, and check if it's an error or correct response
    echo 'Success: ';
    echo $result['message'];
} else {
    // Something went wrong, probably connection issues
    echo 'Error: ';
    echo $result['message'];
}

//Delete a user from OpenFire
$result = $api->deleteUser($username);


//Disable a user
$result = $api->lockoutUser($username);


//Enable a user
$result = $api->unlockUser($username);

/**
 * Update a user
 *
 * The $password, $name, $email, $groups arguments are optional
 * 
 */
$result = $api->updateUser($username, $password, $name, $email, $groups)

//Add to roster
$api->addToRoster($username, $jid);

//Delete from roster
$api->addToRoster($username, $jid);

//Update user roster
$api->updateRoster($username, $jid, $nickname, $subscription]);

```

## CONTACT
- gidkom <yoroumah@gmail.com>