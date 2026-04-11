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

```
composer require gidkom/php-openfire-restapi:v1.1.0
```
#### OR
```json
{
    "require": {
        "gidkom/php-openfire-restapi": "v1.1.0"
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
    # Display result
    print_r($result['data']);
} else {
    # Something went wrong
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

# Add a new user to OpenFire
# The $name and $email arguments are optional
$result = $api->addUser('Username', 'Password', 'Real Name', 'johndoe@domain.com');

# To also add the new user to one or more groups, call addToGroup afterwards:
$api->addToGroup('Username', 'Group 1');

#Delete a user from OpenFire
$result = $api->deleteUser($username);

# Update a user
# The $password, $name, $email arguments are optional.
# Note: Openfire's UserEntity does not accept a groups field — use addToGroup
# (or deleteFromGroup) to manage group membership for an existing user.
$result = $api->updateUser($username, $password, $name, $email);

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
# The $nickname and $subscriptionType arguments are optional
$api->addToRoster($username, $jid, $nickname, $subscriptionType);

# Delete from roster
$api->deleteFromRoster($username, $jid);

# Update user roster
$api->updateRoster($username, $jid, $nickname, $subscriptionType);

# Push a roster event to a user's connected resources
$api->pushRosterEvent($username);

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

# Retrieve concurrent sessions
$api->getConcurrentSessons();
```

### Group related Endpoints
```
# Get all groups
$api->getGroups();

# Retrieve group 
$api->getGroup($groupName);

# Create a group
$api->createGroup($groupName, $description);

# Update a group description
$api->updateGroup($groupName, $description);

# Delete a group
$api->deleteGroup($groupName);

```

### Session related Endpoints
```
# Retrieve all users sessions
$api->getSessions();

# Retrieve all users sessions
$api->getUserSessions($username);

# Close all user sessions
$api->closeUserSessions($username);

```

### Message related Endpoints
```
# Send a broadcast message to all online users
$api->broadcastMessage('hello');

# Clear archived message history between two users
# Requires Openfire Monitoring Service plugin to be installed
$api->clearMessages($username, $targetUsername);
```


## CONTACT
- gidkom <yoroumah@gmail.com>
