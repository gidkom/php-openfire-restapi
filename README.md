openfire-userservice-php
=====================

A simple PHP class designed to work with Openfire UserService plugin. It is used to remote manage the Openfire server.

## LICENSE
openfire-userservice-php is licensed under MIT style license, see LICENCE for further information.

## REQUIREMENTS
- PHP 5.4+

## INSTALLATION

### With Composer
-------------
The easiest way to install is via [composer](http://getcomposer.org/). Create the following `composer.json` file and run the `composer.phar` install command to install it.

```json
{
    "require": {
        "gidkom/openfire-userservice-php": "dev-master"
    }
}
```

## USAGE
```php
include "vendor/autoload.php";

// Create the OpenfireUserservice object
$opuservice = new Gidkom\UserService\UserService

// Set the required config parameters
$opuservice->secret = "MySecret";
$opuservice->host = "jabber.myserver.com";
$opuservice->port = "9090";  // default 9090

// Optional parameters (showing default values)
$opuservice->useCurl = true;
$opuservice->useSSL = false;
$opuservice->plugin = "/plugins/userService/userservice";  // plugin folder location

// Add a new user to OpenFire and add to a group
$result = $opuservice->addUser('Username', 'Password', 'Real Name', 'johndoe@domain.com', array('Group 1'));

// Check result if command is succesful
if($result) {
    // Display result, and check if it's an error or correct response
    echo ($result['result']) ? 'Success: ' : 'Error: ';
    echo $result['message'];
} else {
    // Something went wrong, probably connection issues
}

//Delete a user from OpenFire
$result = $opuservice->deleteUser($username);


//Disable a user
$result = $opuservice->disableUser($username);


//Enable a user
$result = $opuservice->EnableUser($username);

/**
 * Update a user
 *
 * The $password, $name, $email, $groups arguments are optional
 * 
 */
$result = $opuservice->updateUser($username, $password, $name, $email, $groups)

```

## CONTACT
- gidkom <yoroumah@gmail.com>