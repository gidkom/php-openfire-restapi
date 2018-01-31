<?php
	
namespace Gidkom\OpenFireRestApi;

use \Gidkom\OpenFireRestApi\RestClient;

class OpenFireRestApi extends RestClient
{


    public function __construct()
    {
        parent::__construct();

    }   

    /**
     * Make the request and analyze the result
     *
     * @param   string          $type           Request method
     * @param   string          $endpoint       Api request endpoint
     * @param   array           $params         Parameters
     * @return  array|false                     Array with data or error, or False when something went fully wrong
     */
    

    /**
     * Get all registered users
     *
     * @return json|false       Json with data or error, or False when something went fully wrong
     */
    public function getUsers()
    {
    	$endpoint = '/users';        
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
     * @param   string[]|false  $groups     Groups  (Optional)
     * @return  json|false                 Json with data or error, or False when something went fully wrong
     */
    public function addUser($username, $password, $name=false, $email=false, $groups=false)
    {
        $endpoint = '/users'; 
        return $this->doRequest('POST', $endpoint, compact('username', 'password','name','email', 'groups'));
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
     * @param   string          $username   Username
     * @param   string|false    $password   Password (Optional)
     * @param   string|false    $name       Name (Optional)
     * @param   string|false    $email      Email (Optional)
     * @param   string[]|false  $groups     Groups (Optional)
     * @return  json|false                 Json with data or error, or False when something went fully wrong
     */
    public function updateUser($username, $password, $name=false, $email=false, $groups=false)
    {
        $endpoint = '/users/'.$username; 
        return $this->doRequest('PUT', $endpoint, compact('username', 'password','name','email', 'groups'));
    }

     /**
     * locks/Disables an OpenFire user
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
     * Adds to this OpenFire user's roster
     *
     * @param   string          $username       	Username
     * @param   string          $jid            	JID
     * @param   string|false    $name           	Name         (Optional)
     * @param   int|false       $subscriptionType   	Subscription (Optional)
     * @return  json|false                     		Json with data or error, or False when something went fully wrong
     */
    public function addToRoster($username, $jid, $name=false, $subscriptionType=false)
    {
        $endpoint = '/users/'.$username.'/roster';
        return $this->doRequest('POST', $endpoint, compact('jid','name','subscriptionType'));
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
        return $this->doRequest('DELETE', $endpoint, $jid);
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
        return $this->doRequest('PUT', $endpoint, $jid, compact('jid','username','subscriptionType'));     
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
        $endpoint = '/groups/';
        return $this->doRequest('POST', $endpoint, compact('name','description'));
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
    public function updateGroup($name,  $description)
    {
        $endpoint = '/groups/'.$name;
        return $this->doRequest('PUT', $endpoint, compact('name','description'));
    }

    /**
     * Gell all active sessions
     *
     * @return json|false       Json with data or error, or False when something went fully wrong
     */
    public function getSessions()
    {
        $endpoint = '/sessions';
        return $this->doRequest('GET', $endpoint);
    }

    public function getChatRoom($name)
    {
        return $this->doRequest('GET', '/chatrooms/'.$name);
    }
	
    public function getAllChatRooms()
    {
        return $this->doRequest('GET', '/chatrooms?type=all');
    }

    public function createChatRoom($naturalName, $roomName, $description, $maxUsers = '30', $persistent = 'false', $publicRoom = 'false')
    {
        $broadcastPresenceRoles = $this->bcastRoles;
        return $this->doRequest('POST', '/chatrooms', compact('naturalName', 'roomName', 'description', 'maxUsers', 'persistent', 'publicRoom', 'broadcastPresenceRoles'));
    }

    public function deleteChatRoom($name)
    {
        return $this->doRequest('DELETE', '/chatrooms/'.$name);
    }
}
