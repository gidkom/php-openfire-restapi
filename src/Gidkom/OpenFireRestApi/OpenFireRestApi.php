<?php
	
namespace Gidkom\OpenFireRestApi;

use Requests;
use GuzzleHttp\Client;

class OpenFireRestApi
{
    public $host		= 'localhost';
	public $port		= '9090';
	public $plugin		= '/plugins/restapi/v1';
	public $secret		= 'SuperSecret';
	public $useSSL		= false;
    protected $params   = array();

    /**
     * Make the request and analyze the result
     *
     * @param   string          $type           Request method
     * @param   string          $endpoint       Api request endpoint
     * @param   array           $params         Parameters
     * @return  array|false                     Array with data or error, or False when something went fully wrong
     */
    public function __construct()
    {
        $this->client = new Client();
       
    }
    private function doRequest($type, $endpoint, $params=array())
    {
    	$base = ($this->useSSL) ? "https" : "http";
    	$url = $base . "://" . $this->host . ":" .$this->port.$this->plugin.$endpoint;
    	$headers = array(
  			'Accept' => 'application/json',
  			'Authorization' => $this->secret
  		);
       // $response = $client->get('http://httpbin.org/get', [
       //      'headers' => ['X-Foo-Header' => 'value']
       //  ]);
        switch ($type) {
            case 'get':
                // $result = Requests::get($url, $headers);
                $result = $this->client->get($url, compact('headers'));
                break;
            case 'post':
                $headers += ['Content-Type'=>'application/json'];                
                $body = json_encode($params);
                // $result = Requests::post($url, $headers, $params);
                $result = $this->client->post($url, compact('headers','body'));
                break;
            case 'delete':
                $headers += ['Content-Type'=>'application/json'];                
                $body = json_encode($params);
                // $result = Requests::delete($url, $headers);
                $result = $this->client->delete($url, compact('headers','body'));
                break;
            case 'put':
                $headers += ['Content-Type'=>'application/json'];                
                $body = json_encode($params);
                // $result = Requests::put($url, $headers, $params);
                $result = $this->client->put($url, compact('headers','body'));
                break;
            default:
                $result = null;
                return $result;
                break;
        }
        
        if ($result->getStatusCode() == 200 || $result->getStatusCode() == 201) {
            return array('status'=>true, 'message'=>$result->getBody());
        }
        return array('status'=>false, 'message'=>$result->getBody());
    	
    }
    

    /**
     * Get all registered users
     *
     * @return json|false       Json with data or error, or False when something went fully wrong
     */
    public function getUsers()
    {
    	$endpoint = '/users';        
    	return $this->doRequest('get',$endpoint);
    }


    /**
     * Get information for a specified user
     *
     * @return json|false       Json with data or error, or False when something went fully wrong
     */
    public function getUser($username)
    {
        $endpoint = '/users/'.$username; 
        return $this->doRequest('get', $endpoint);
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
        return $this->doRequest('post', $endpoint, compact('username', 'password','name','email', 'groups'));
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
        return $this->doRequest('delete', $endpoint);
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
        return $this->doRequest('put', $endpoint, compact('username', 'password','name','email', 'groups'));
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
        return $this->doRequest('post', $endpoint);
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
        return $this->doRequest('delete', $endpoint);
    }


    /**
     * Adds to this OpenFire user's roster
     *
     * @param   string          $username       Username
     * @param   string          $jid            JID
     * @param   string|false    $name           Name         (Optional)
     * @param   int|false       $subscription   Subscription (Optional)
     * @return  json|false                     Json with data or error, or False when something went fully wrong
     */
    public function addToRoster($username, $jid, $name=false, $subscription=false)
    {
        $endpoint = '/users/'.$username.'/roster';
        return $this->doRequest('post', $endpoint, compact('jid','name','subscription'));
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
        return $this->doRequest('delete', $endpoint, $jid);
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
        return $this->doRequest('put', $endpoint, $jid, compact('jid','username','subscriptionType'));     
    }
}