<?php

namespace Gidkom\UserService;

class UserService
{
    /**
     * Stores all the default values.
     * @var		array	$settings
     */
    private $settings = array(
        'host'			=> 'localhost',
        'port'			=> '9090',
        'plugin'		=> '/plugins/userService/userservice',
        'secret'		=> 'SuperSecret',

        'useCurl'		=> true,
        'useSSL'		=> false,

        'subscriptions'	=> array(-1, 0, 1, 2)
    );


    /**
     * Forward the POST request and analyze the result
     *
     * @param	string[]		$parameters		Parameters
     * @return	array|false						Array with data or error, or False when something went fully wrong
     */
    private function doRequest($parameters = array())
    {
        $base = ($this->useSSL) ? "https" : "http";
        $url = $base . "://" . $this->host;

        if($this->useCurl) {
            $result = $this->doRequestCurl($url, $parameters);
        } else {
            $result = $this->doRequestFopen($url, $parameters);
        }

        if($result === false) {
            return false;
        } else {
            return $this->analyzeResult($result);
        }

    }

    /**
     * Analyze the result for errors, and reorder the result
     *
     * @param	string			$result		Result
     * @return	array|false					Array with data or error, or False when something went fully wrong
     */
    private function analyzeResult($result)
    {
        if(preg_match('#^<error>([A-Za-z0-9 ]+)</error>#', $result, $matches)) {
            return array(
                'result'	=> false,
                'message'	=> $matches[1]
            );
        } elseif(preg_match('#^<result>([A-Za-z0-9 ]+)</result>#', $result, $matches)) {
            return array(
                'result'	=> true,
                'message'	=> $matches[1]
            );
        } else {
            return false;
        }
    }

    /**
     * Sends the actual POST request to OpenFire's UserService using cURL
     *
     * @param	string		$url			URL
     * @param	string[]	$parameters		Parameters
     * @return	string|false				Callback data from cURL request
     */
    private function doRequestCurl($url, $parameters)
    {
        try {
            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL				=> $url . $this->plugin,
                CURLOPT_PORT			=> $this->port,
                CURLOPT_POST			=> true,
                CURLOPT_POSTFIELDS		=> http_build_query($parameters),
                CURLOPT_RETURNTRANSFER	=> true
            ));

            $result = curl_exec ($ch);
            curl_close ($ch);

        } catch (Exception $ex) {
            $result = false;
        }

        return $result;
    }

    /**
     * Sends the actual POST request to OpenFire's UserService using cURL
     *
     * @param	string		$url			URL
     * @param	string[]	$parameters		Parameters
     * @return	string|false				Callback data from FOpen request
     */
    private function doRequestFopen($url, $parameters)
    {
        try {
            $fopen = fopen($url . ":" . $this->port . $this->plugin . "?" . http_build_query($parameters), 'r');

            $result = fread($fopen, 1024);
            fclose($fopen);

        } catch (Exception $ex) {
            $result = false;
        }

        return $result;
    }

    /**
     * Creates a new OpenFire user
     *
     * @param	string			$username	Username
     * @param	string			$password	Password
     * @param	string|false	$name		Name	(Optional)
     * @param	string|false	$email		Email	(Optional)
     * @param	string[]|false	$groups		Groups	(Optional)
     * @return	array|false					Array with data or error, or False when something went fully wrong
     */
    public function addUser($username, $password, $name = false, $email = false, $groups = false)
    {
        $parameters = array(
            'type'		=> 'add',
            'secret'	=> $this->secret,
            'username'	=> $username,
            'password'	=> $password
        );

        // Name add request
        $this->addString($parameters, 'name', $name);

        // Email add request
        $this->addEmail($parameters, $email);

        // Groups add request
        $this->addGroups($parameters, $groups);

        return $this->doRequest($parameters);
    }

    /**
     * Deletes an OpenFire user
     *
     * @param	string			$username	Username
     * @return	array|false					Array with data or error, or False when something went fully wrong
     */
    public function deleteUser($username)
    {
        return $this->doRequest(array(
            'type'		=> 'delete',
            'secret'	=> $this->secret,
            'username'	=> $username
        ));
    }

    /**
     * Disables an OpenFire user
     *
     * @param	string			$username	Username
     * @return	array|false					Array with data or error, or False when something went fully wrong
     */
    public function disableUser($username)
    {
        return $this->doRequest(array(
            'type'		=> 'disable',
            'secret'	=> $this->secret,
            'username'	=> $username
        ));
    }

    /**
     * Enables an OpenFire user
     *
     * @param	string			$username	Username
     * @return	array|false					Array with data or error, or False when something went fully wrong
     */
    public function enableUser($username)
    {
        return $this->doRequest(array(
            'type'		=> 'enable',
            'secret'	=> $this->secret,
            'username'	=> $username
        ));
    }

    /**
     * Updates an OpenFire user
     *
     * @param	string			$username	Username
     * @param	string|false	$password	Password (Optional)
     * @param	string|false	$name		Name (Optional)
     * @param	string|false	$email		Email (Optional)
     * @param	string[]|false	$groups		Groups (Optional)
     * @return	array|false					Array with data or error, or False when something went fully wrong
     */
    public function updateUser($username, $password = false, $name = false, $email = false, $groups = false)
    {
        $parameters = array(
            'type'		=> 'update',
            'secret'	=> $this->secret,
            'username'	=> $username
        );

        // Password change request
        $this->addString($parameters, 'password', $password);

        // Name change request
        $this->addString($parameters, 'name', $name);

        // Email change request
        $this->addEmail($parameters, $email);

        // Groups change request
        $this->addGroups($parameters, $groups);

        return $this->doRequest($parameters);
    }

    /**
     * Adds to this OpenFire user's roster
     *
     * @param	string			$username		Username
     * @param	string			$itemJid		Item JID
     * @param	string|false	$name			Name		 (Optional)
     * @param	int|false		$subscription	Subscription (Optional)
     * @return	array|false						Array with data or error, or False when something went fully wrong
     */
    public function addToRoster($username, $itemJid, $name = false, $subscription = false)
    {
        $parameters = array(
            'type'			=> 'add_roster',
            'secret'		=> $this->secret,
            'username'		=> $username,
            'item_jid'		=> $itemJid
        );

        // Name update request
        $this->addString($parameters, 'name', $name);

        // Subscription update request
        $this->addSubscription($parameters, $subscription);

        return $this->doRequest($parameters);
    }

    /**
     * Updates this OpenFire user's roster
     *
     * @param	string			$username		Username
     * @param	string			$itemJid		Item JID
     * @param	string|false	$name			Name		 (Optional)
     * @param	int|false		$subscription	Subscription (Optional)
     * @return	array|false						Array with data or error, or False when something went fully wrong
     */
    public function updateRoster($username, $itemJid, $name = false, $subscription = false)
    {
        $parameters = array(
            'type'			=> 'update_roster',
            'secret'		=> $this->secret,
            'username'		=> $username,
            'item_jid'		=> $itemJid
        );

        // Name update request
        $this->addString($parameters, 'name', $name);

        // Subscription update request
        $this->addSubscription($parameters, $subscription);

        return $this->doRequest($parameters);
    }

    /**
     * Removes from this OpenFire user's roster
     *
     * @param	string			$username	Username
     * @param	string			$itemJid	Item JID
     * @return	array|false					Array with data or error, or False when something went fully wrong
     */
    public function deleteFromRoster($username, $itemJid)
    {
        return $this->doRequest(array(
            'type'			=> 'delete_roster',
            'secret'		=> $this->secret,
            'username'		=> $username,
            'item_jid'		=> $itemJid
        ));
    }

    /**
     * Add a possible parameter
     *
     * @param	string[]					$parameters		Parameters
     * @param	string						$paramName		Parameter name
     * @param	string|int|bool|string[]	$paramValue		Parameter value
     * @return	void
     */
    private function addParameter(&$parameters, $paramName, $paramValue)
    {
        $parameters = array_merge($parameters, array(
            $paramName => $paramValue
        ));
    }

    /**
     * Add a possible string parameter
     *
     * @param	string[]		$parameters		Parameters
     * @param	string			$paramName		Parameter name
     * @param	string|false	$paramValue		Parameter value
     * @return	void
     */
    private function addString(&$parameters, $paramName, $paramValue)
    {
        if(!empty($paramValue) && is_string($paramValue)) {
            $this->addParameter($parameters, $paramName, $paramValue);
        }
    }

    /**
     * Add a possible email parameter
     *
     * @param	string[]		$parameters		Parameters
     * @param	string|false	$paramValue		Parameter value
     * @return	void
     */
    private function addEmail(&$parameters, $paramValue)
    {
        if(filter_var($paramValue, FILTER_VALIDATE_EMAIL) !== false) {
            $this->addParameter($parameters, 'email', $paramValue);
        }
    }

    /**
     * Add a possible subscription parameter
     *
     * @param	string[]	$parameters		Parameters
     * @param	int|false	$paramValue		Parameter value
     * @return	void
     */
    private function addSubscription(&$parameters, $paramValue)
    {
        if($paramValue !== false && in_array($paramValue, $this->subscriptions)) {
            $this->addParameter($parameters, 'subscription', $paramValue);
        }
    }

    /**
     * Add a possible groups parameter
     *
     * @param	string[]		$parameters		Parameters
     * @param	string[]|false	$paramValue		Parameter value
     * @return	void
     */
    private function addGroups(&$parameters, $paramValue)
    {
        if(is_array($paramValue) && !empty($paramValue)) {
            $this->addParameter($parameters, 'groups', implode(',', $paramValue));
        }
    }

    /**
     * Simple construct (unused)
     */
    public function __construct() {	}

    /**
     * Stores a configuration parameter
     *
     * @param	string					$name	Name
     * @return	string|bool|int|null			Get parameter
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->settings)) {
            return $this->settings[$name];
        }

        return null;
    }

    /**
     * Grabs a configuration parameter
     *
     * @param	string				$name	Name
     * @param	string|bool|int		$value	Value
     * @return	void
     */
    public function __set($name, $value)
    {
        $this->settings[$name] = $value;
    }
}
