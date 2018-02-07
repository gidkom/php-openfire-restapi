php-openfire-restapi
=====================

[![Join the chat at https:#gitter.im/gidkom/php-openfire-restapi](https:#badges.gitter.im/Join%20Chat.svg)](https:#gitter.im/gidkom/php-openfire-restapi?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https:#scrutinizer-ci.com/g/gidkom/php-openfire-restapi/badges/build.png?b=master)](https:#scrutinizer-ci.com/g/gidkom/php-openfire-restapi/build-status/master)


A simple PHP class designed to work with Openfire Rest Api plugin. It is used to remote manage the Openfire server.

## LICENSE
php-openfire-restapi is licensed under MIT style license, see LICENCE for further information.

## DEPENDENCIES
The REST API plugin need to be installed and configured on the Openfire server.  
- [how to install REST API](https:#www.igniterealtime.org/projects/openfire/plugins/restapi/readme.html#installation)  
- [How to configure REST API](https:#www.igniterealtime.org/projects/openfire/plugins/restapi/readme.html#authentication)  

## REQUIREMENTS
- PHP 5.4+

## INSTALLATION

### With Composer
-------------
The easiest way to install is via [composer](http:#getcomposer.org/). Create the following `composer.json` file and run the `composer.phar` install command to install it.

```json
{
    "require": {
        "gidkom/php-openfire-restapi": "dev-master"
    }
}
```


# EXAMPLE


## SETUP
```php
include "vendor/autoload.php";

```

## SET PARAMETERS
```
# Create the Openfire Rest api object
$api = new Gidkom\OpenFireRestApi\OpenFireRestApi;

# Set the required config parameters
$api->secret = "MySecret";
$api->host = "jabber.myserver.com";
$api->port = "9090";  # default 9090

# Optional parameters (showing default values)

$api->useSSL = false;
$api->plugin = "/plugins/restapi/v1";  # plugin 
```

### Response format
```
# Check result if command is succesful
if($result['status']) {
    # Display result, and check if it's an error or correct response
    echo 'Success: ';
    echo $result['data'];
} else {
    # Something went wrong, probably connection issues
    echo 'Error: ';
    echo $result['data'];
}

```

### User related examples

```
# Retrieve users
$options = ['search'=> 'John']; # optional
$result = $api->getUsers($options);

# Retrieve a user
$result = $api->getUser($username);

# Add a new user to OpenFire and add to a group
$result = $api->addUser('Username', 'Password', 'Real Name', 'johndoe@domain.com', array('Group 1'));

#Delete a user from OpenFire
$result = $api->deleteUser($username);

# Update a user
# The $password, $name, $email, $groups arguments are optional
$result = $api->updateUser($username, $password, $name, $email, $groups);

# Add user to a group
$result = $api->addToGroup($username, $groupName);

# Delete user from a group
$result = $api->deleteFromGroup($username, $groupName);

# Disable/lockout a user
$result = $api->lockoutUser($username);

# Enable a user
$result = $api->unlockUser($username);

# Retrieve a user roster
$api->userRosters($username);

# Create a user roster entry
$api->addToRoster($username, $jid);

# Delete from roster
$api->deleteFromRoster($username, $jid);

# Update user roster
$api->updateRoster($username, $jid, $nickname, $subscription]);


```

### Chat room related Endpoints
```
# Get all chat rooms
$api->getAllChatRooms();

# Retrieve a chat room
$api->getChatRoom($name);

# Create a chat room
# $params  = ['naturalName'=>'myroom', 'roomName'=>'myroom', 'description'=>'my chat room']; 
$api->createChatRoom($params);

# Delete a chat room
$api->deleteChatRoom($roomName);

# Update a chat room
# $params  = ['naturalName'=>'myroom', 'roomName'=>'myroom', 'description'=>'my fav chat room'];  
$api->createChatRoom($roomName =>$params);

# Add user with role to chat room
$api->addUserRoleToChatRoom($roomName, $name, $role);
```

### System related Endpoints
```
# Retrieve all system properties
$api->getSystemProperties();

# Retrieve a system property
$api->getSystemProperty('plugin.restapi.httpAuth');

# Create a system property
$api->createSystemProperty(['key'=>'test', 'value'=>'testname']);

# Update a system property
$api->updateSystemProperty(['key'=>'test', 'value'=>'testname']);

# Delete a system property
$api->deleteSystemProperty('test');
```












```
# Get all groups
$api->getGroups();

# Retrieve group 
$api->getGroup($name);

# Create a group
$api->createGroup($group_name, $description);

# Update a group description
$api->updateGroup($group_name, $description);

# Delete a group
$api->deleteGroup($group_name);

```

## CONTACT
- gidkom <yoroumah@gmail.com>
