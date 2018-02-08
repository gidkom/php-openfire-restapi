<?php

namespace Gidkom\OpenFireRestApi;

use GuzzleHttp\Client;
/**
* 
*/
class RestClient
{
	public $host = 'localhost';
    public $port = '9090';
    public $plugin = '/plugins/restapi/v1';
    public $secret = 'SuperSecret';
    public $useSSL = false;
    protected $params  = array();
    private $client;
    public $bcastRoles = array();
    public $useBasicAuth = false;
    public $basicUser = 'admin';
    public $basicPwd = '1234';

	function __construct()
	{
		$this->client = new Client();
	}

    /**
     * Make the request and analyze the result
     *
     * @param   string          $type           Request method
     * @param   string          $endpoint       Api request endpoint
     * @param   array           $params         Parameters
     * @return  array|false                     Array with data or error, or False when something went fully wrong
     */
	protected function doRequest($type, $endpoint, $params=[])
    {
    	$base = ($this->useSSL) ? "https" : "http";
    	$url = $base . "://" . $this->host . ":" .$this->port.$this->plugin.$endpoint;
	    
		if ($this->useBasicAuth)
            $auth = 'Basic ' . base64_encode($this->basicUser . ':' . $this->basicPwd);
        else
            $auth = $this->secret;
	    
    	$headers = array(
  			'Accept' => 'application/json',
  			'Authorization' => $auth,
            'Content-Type'=>'application/json'
  		);

        $body = json_encode($params);

        try {
        	$result = $this->client->request($type, $url, compact('headers','body'));
        } catch (\Exception $e) {
        	return  ['status'=>false, 'data'=>['message'=>$e->getMessage()]];
        }
	        
        
        if ($result->getStatusCode() == 200 || $result->getStatusCode() == 201) {
            return array('status'=>true, 'data'=>json_decode($result->getBody()));
        }
        return array('status'=>false, 'data'=>json_decode($result->getBody()));
    	
    }
    

}