<?php
	
namespace Gidkom\OpenFireRestApi;

class OpenFireRestApi extends RestClient
{


    public function __construct()
    {
        parent::__construct();
    }

    protected function filterPayload(array $params)
    {
        return array_filter(
            $params,
            function ($v) { return $v !== false && $v !== null; }
        );
    }

    /**
     * Get all registered users
     *
     * @return json|false       Json with data or error, or False when something went fully wrong
     */
    public function getUsers($opts = [])
    {
        $query = '';

        if (isset($opts['search'])) {
            $query .= '?search=' . rawurlencode($opts['search']);
        }

        $endpoint = '/users' . $query;
        return $this->doRequest('GET', $endpoint);
    }

    /**
     * Get information for a specified user
     *
     * @return json|false       Json with data or error, or False when something went fully wrong
     */
    public function getUser($username)
    {
        $endpoint = '/users/'.$username; 
        return $this->doRequest('GET', $endpoint);
    }


    /**
     * Creates a new OpenFire user
     *
     * @param   string          $username   Username
     * @param   string          $password   Password
     * @param   string|false    $name       Name    (Optional)
     * @param   string|false    $email      Email   (Optional)
     * @return  json|false                 Json with data or error, or False when something went fully wrong
     */
    public function addUser($username, $password, $name=false, $email=false)
    {
        $endpoint = '/users';
        $payload = $this->filterPayload(compact('username', 'password', 'name', 'email'));
        return $this->doRequest('POST', $endpoint, $payload);
    }


    /**
     * Deletes an OpenFire user
     *
     * @param   string          $username   Username
     * @return  json|false                 Json with data or error, or False when something went fully wrong
     */
    public function deleteUser($username)
    {
        $endpoint = '/users/'.$username; 
        return $this->doRequest('DELETE', $endpoint);
    }

    /**
     * Updates an OpenFire user
     *
     * Note: Openfire's UserEntity does not accept a `groups` field. Use
     * {@see addToGroup()} / {@see addToGroups()} to manage group membership.
     *
     * @param   string          $username   Username
     * @param   string|false    $password   Password (Optional)
     * @param   string|false    $name       Name (Optional)
     * @param   string|false    $email      Email (Optional)
     * @return  json|false                 Json with data or error, or False when something went fully wrong
     */
    public function updateUser($username, $password=false, $name=false, $email=false)
    {
        $endpoint = '/users/'.$username;
        $payload = $this->filterPayload(compact('password', 'name', 'email'));
        return $this->doRequest('PUT', $endpoint, $payload);
    }


    /**
     * Retrieve all user groups
     *
     * @param   string          $username   Username
     * @return  json|false                 Json with data or error, or False when something went fully wrong
     */
    public function userGroups($username)
    {
        $endpoint = '/users/'.$username.'/groups'; 
        return $this->doRequest('GET', $endpoint);
    }

    /**
     * Add user to groups
     *
     * @param   string          $username   Username
     * @param   Array           $groups   Groups to add user
     * @return  json|false                 Json with data or error, or False when something went fully wrong
     */
    public function addToGroups($username, $groups)
    {
        $endpoint = '/users/'.$username.'/groups'; 
        return $this->doRequest('POST', $endpoint, $groups);
    }

    /**
     * Add user to a group
     *
     * @param   string          $username   Username
     * @param   string          $groupName  Group to add user
     * @return  json|false                 Json with data or error, or False when something went fully wrong
     */
    public function addToGroup($username, $groupName)
    {
        $endpoint = '/users/'.$username.'/groups/'.$groupName;
        return $this->doRequest('POST', $endpoint);
    }


    /**
     * Delete user from a group
     *
     * @param   string          $username   Username
     * @param   string          $groupName  Group to remove user from
     * @return  json|false                 Json with data or error, or False when something went fully wrong
     */
    public function deleteFromGroup($username, $groupName)
    {
        $endpoint = '/users/'.$username.'/groups/'.$groupName; 
        return $this->doRequest('DELETE', $endpoint );
    }


    /**
     * lockout/Disable an OpenFire user
     *
     * @param   string          $username   Username
     * @return  json|false                 Json with data or error, or False when something went fully wrong
     */
    public function lockoutUser($username)
    {
        $endpoint = '/lockouts/'.$username; 
        return $this->doRequest('POST', $endpoint);
    }


    /**
     * unlocks an OpenFire user
     *
     * @param   string          $username   Username
     * @return  json|false                 Json with data or error, or False when something went fully wrong
     */
    public function unlockUser($username)
    {
        $endpoint = '/lockouts/'.$username; 
        return $this->doRequest('DELETE', $endpoint);
    }


    /**
     * List user rosters
     *
     * @param   string          $username           Username
     * @return  json|false                          Json with data or error, or False when something went fully wrong
     */
    public function userRosters($username)
    {
        $endpoint = '/users/'.$username.'/roster';
        return $this->doRequest('GET', $endpoint);
    }


    /**
     * Adds to this OpenFire user's roster
     *
     * @param   string          $username           Username
     * @param   string          $jid                JID
     * @param   string|false    $nickname           Nickname     (Optional)
     * @param   int|false       $subscriptionType   Subscription (Optional)
     * @return  json|false                          Json with data or error, or False when something went fully wrong
     */
    public function addToRoster($username, $jid, $nickname=false, $subscriptionType=false)
    {
        $endpoint = '/users/'.$username.'/roster';
        $payload = array_filter(
            compact('jid', 'nickname', 'subscriptionType'),
            function ($v) { return $v !== false && $v !== null; }
        );
        return $this->doRequest('POST', $endpoint, $payload);
    }


    /**
     * Removes from this OpenFire user's roster
     *
     * @param   string          $username   Username
     * @param   string          $jid        JID
     * @return  json|false                 Json with data or error, or False when something went fully wrong
     */
    public function deleteFromRoster($username, $jid)
    {
        $endpoint = '/users/'.$username.'/roster/'.$jid;
        return $this->doRequest('DELETE', $endpoint);
    }

    /**
     * Updates this OpenFire user's roster
     *
     * @param   string          $username           Username
     * @param   string          $jid                 JID
     * @param   string|false    $nickname           Nick Name (Optional)
     * @param   int|false       $subscriptionType   Subscription (Optional)
     * @return  json|false                          Json with data or error, or False when something went fully wrong
     */
    public function updateRoster($username, $jid, $nickname=false, $subscriptionType=false)
    {
        $endpoint = '/users/'.$username.'/roster/'.$jid;
        $payload = $this->filterPayload(compact('nickname', 'subscriptionType'));
        return $this->doRequest('PUT', $endpoint, $payload);
    }


    /**
     * Push a roster event to an OpenFire user
     *
     * Triggers Openfire to re-push the roster to this user's connected
     * resources. Useful after bulk roster updates so clients pick up
     * the changes without reconnecting.
     *
     * @param   string          $username           Username
     * @return  json|false                          Json with data or error, or False when something went fully wrong
     */
    public function pushRosterEvent($username)
    {
        $endpoint = '/users/'.$username.'/roster/push';
        return $this->doRequest('POST', $endpoint);
    }




    /**
     * Get a chat room by name
     *
     * @param   string          $name               Name of the chat room
     * @return json|false       Json with data or error, or False when something went fully wrong
     */
    public function getChatRoom($name)
    {
        return $this->doRequest('GET', '/chatrooms/'.$name);
    }
	
    /**
     * Get all chat rooms
     *
     * @return json|false       Json with data or error, or False when something went fully wrong
     */
    public function getAllChatRooms()
    {
        return $this->doRequest('GET', '/chatrooms?type=all');
    }


    /**
     * Create a chat room
     *
     * @param   string          $params        Params
     * @return  json|false                     Json with data or error, or False when something went fully wrong
     */
    public function createChatRoom($params = [])
    {
        return $this->doRequest('POST', '/chatrooms', $params);
    }


    /**
     * Update a chat room
     *
     * @param   string          $params        Params
     * @return  json|false                     Json with data or error, or False when something went fully wrong
     */
    public function updateChatRoom($roomName, $params = [])
    {
        return $this->doRequest('PUT', '/chatrooms/'.$roomName, $params);
    }


    /**
     * Delete a chat room
     *
     * @param   string      $name               Name of the Group to delete
     * @return  json|false                       Json with data or error, or False when something went fully wrong
     */
    public function deleteChatRoom($roomName)
    {
        return $this->doRequest('DELETE', '/chatrooms/'.$roomName);
    }

    /**
     * Get chat room participants
     *
     * @param   string      $name               Name of the chatroom
     * @return  json|false                       Json with data or error, or False when something went fully wrong
     */
    public function getChatRoomParticipants($roomName)      
    {
        return $this->doRequest('GET', '/chatrooms/'.$roomName.'/participants');
    }

    /**
     * Get chat room occupants
     *
     * @param   string      $name               Name of the chatroom
     * @return  json|false                       Json with data or error, or False when something went fully wrong
     */
    public function getChatRoomOccupants($roomName)      
    {
        return $this->doRequest('GET', '/chatrooms/'.$roomName.'/occupants');
    }

    /**
     * Add user with role to chatroom
     *
     * @param   string      $roomName           Name of the chatroom
     * @param   string      $name               Name of the user or JID
     * @param   string      $roles              Role to assign (e.g. owners, admins, members)
     * @return  json|false                       Json with data or error, or False when something went fully wrong
     */
    public function addUserRoleToChatRoom($roomName, $name, $roles)
    {
        return $this->doRequest('POST', '/chatrooms/'.$roomName.'/'.$roles.'/'.$name);
    }


    /**
     * Add group with role to chatroom
     *
     * @param   string      $roomName           Name of the chatroom
     * @param   string      $name               Name of the group
     * @param   string      $roles              Role to assign (e.g. owners, admins, members)
     * @return  json|false                       Json with data or error, or False when something went fully wrong
     */
    public function addGroupRoleToChatRoom($roomName, $name, $roles)
    {
        return $this->doRequest('POST', '/chatrooms/'.$roomName.'/'.$roles.'/group/'.$name);
    }


    /**
     * Delete a user from a chat room
     *
     * @param   string      $roomName           Name of the chatroom
     * @param   string      $name               Name of the user or JID
     * @param   string      $roles              Role segment in the endpoint
     * @return  json|false                       Json with data or error, or False when something went fully wrong
     */
    public function deleteChatRoomUser($roomName, $name, $roles)
    {
        return $this->doRequest('DELETE', '/chatrooms/'.$roomName.'/'.$roles.'/'.$name);
    }


    /**
     * Retrieve all system properties
     *
     * @return  json|false                       Json with data or error, or False when something went fully wrong
     */
    public function getSystemProperties()
    {
        return $this->doRequest('GET', '/system/properties');
    }

    /**
     * Retrieve a system property
     *
     * @param   string      $name                Name of property
     * @return  json|false                       Json with data or error, or False when something went fully wrong
     */
    public function getSystemProperty($propertyName)
    {
        return $this->doRequest('GET', '/system/properties/'.$propertyName);
    }


    /**
     * Create a system property
     *
     * @param   array      $data                new property with value
     * @return  json|false                       Json with data or error, or False when something went fully wrong
     */
    public function createSystemProperty($data)
    {
        return $this->doRequest('POST', '/system/properties', $data);
    }


    /**
     * Update a system property
     *
     * @param   string     $propertyName        name of property to update
     * @param   array      $data                new property with value
     * @return  json|false                       Json with data or error, or False when something went fully wrong
     */
    public function updateSystemProperty($propertyName, $data)
    {
        return $this->doRequest('POST', '/system/properties/'.$propertyName, $data);
    }


    /**
     * Delete a system property
     *
     * @param   array      $data                new property with value
     * @return  json|false                       Json with data or error, or False when something went fully wrong
     */
    public function deleteSystemProperty($propertyName)
    {
        return $this->doRequest('DELETE', '/system/properties/'.$propertyName);
    }


    /**
     * Retrieve concurrent sessions
     *
     * @return  json|false                       Json with data or error, or False when something went fully wrong
     */
    public function getConcurrentSessions()
    {
        return $this->doRequest('GET', '/system/statistics/sessions');
    }

    /**
     * Backward-compatible alias for getConcurrentSessions().
     *
     * @return  json|false
     */
    public function getConcurrentSessons()
    {
        return $this->getConcurrentSessions();
    }


    /**
     * Get all groups
     *
     * @return  json|false      Json with data or error, or False when something went fully wrong
     */
    public function getGroups()
    {
        $endpoint = '/groups';
        return $this->doRequest('GET', $endpoint);
    }

    /**
     *  Retrieve a group
     *
     * @param  string   $name                       Name of group
     * @return  json|false                          Json with data or error, or False when something went fully wrong
     */
    public function getGroup($name)
    {
        $endpoint = '/groups/'.$name;
        return $this->doRequest('GET', $endpoint);
    }

    /**
     * Create a group
     *
     * @param   string   $name                      Name of the group
     * @param   string   $description               Some description of the group
     *
     * @return  json|false                          Json with data or error, or False when something went fully wrong
     */
    public function createGroup($name, $description = false)
    {
        $endpoint = '/groups';
        $payload = $this->filterPayload(compact('name', 'description'));
        return $this->doRequest('POST', $endpoint, $payload);
    }

    /**
     * Delete a group
     *
     * @param   string      $name               Name of the Group to delete
     * @return  json|false                          Json with data or error, or False when something went fully wrong
     */
    public function deleteGroup($name)
    {
        $endpoint = '/groups/'.$name;
        return $this->doRequest('DELETE', $endpoint);
    }

    /**
     * Update a group (description)
     *
     * @param   string      $name               Name of group
     * @param   string      $description        Some description of the group
     *
     */
    public function updateGroup($name, $description)
    {
        $endpoint = '/groups/'.$name;
        $payload = $this->filterPayload(compact('description'));
        return $this->doRequest('PUT', $endpoint, $payload);
    }


    /**
     * Retrieve all sessions
     *
     * @return json|false       Json with data or error, or False when something went fully wrong
     */
    public function getSessions()
    {
        $endpoint = '/sessions';
        return $this->doRequest('GET', $endpoint);
    }

    /**
     * Retrieve all user sessions
     *
     * @param   string      $username               Username of user
     * @return json|false       Json with data or error, or False when something went fully wrong
     */
    public function getUserSessions($username)
    {
        $endpoint = '/sessions/'.$username;
        return $this->doRequest('GET', $endpoint);
    }


    /**
     * Close all user sessions
     * @param string      $username               Username of user 
     * @return json|false       Json with data or error, or False when something went fully wrong
     */
    public function closeUserSessions($username)
    {
        $endpoint = '/sessions/'.$username;
        return $this->doRequest('DELETE', $endpoint);
    }

    /**
     * Send a broadcast message to all online users
     *
     * @param  string      $message               Message to send
     * @return json|false       Json with data or error, or False when something went fully wrong
     */
    public function broadcastMessage($message = '')
    {
        $content = ['body' => $message];
        $endpoint = '/messages/users';
        return $this->doRequest('POST', $endpoint, $content);
    }

    /**
     * Clear the archived message history between two users
     *
     * Note: this endpoint is provided by Openfire's Monitoring Service
     * plugin (exposed via the REST API plugin). It will only succeed on
     * servers where both plugins are installed and enabled.
     *
     * @param   string          $username           Username whose archive to clear
     * @param   string          $targetUsername     Conversation partner username
     * @return  json|false                          Json with data or error, or False when something went fully wrong
     */
    public function clearMessages($username, $targetUsername)
    {
        $endpoint = '/archive/messages/'.$username.'/'.$targetUsername;
        return $this->doRequest('DELETE', $endpoint);
    }

}
