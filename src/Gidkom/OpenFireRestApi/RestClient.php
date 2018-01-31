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

	public function geti()
	{
		return $this->host. '  '. $this->secret;
	}



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
  			// 'Content-Type'=>'application/json',
  			'Authorization' => $auth
  		);

        $body = json_encode($params);
        // $headers += ['Content-Type'=>'application/json'];
        


        try {
        	// $result = $this->client->request($type, $url, compact('headers'));
        	switch ($type) {
        		case 'GET':
        			$result = $this->client->get($url, compact('headers'));
        			break;
	            case 'POST':
	                $headers += ['Content-Type'=>'application/json'];
	                $result = $this->client->post($url, compact('headers','body'));
	                break;
	            case 'DELETE':
	                $headers += ['Content-Type'=>'application/json'];
	                $result = $this->client->delete($url, compact('headers','body'));
	                break;
	            case 'PUT':
	                $headers += ['Content-Type'=>'application/json'];
	                $result = $this->client->put($url, compact('headers','body'));
	                break;
	            default:
	                $result = null;
	                break;
	        }
        } catch (Exception $e) {
        	$result = $e->message;
        }
	        


        
        // if ($result->getStatusCode() == 200 || $result->getStatusCode() == 201) {
        //     return array('status'=>true, 'message'=>json_decode($result->getBody()));
        // }
        // return array('status'=>false, 'message'=>json_decode($result->getBody()));
    	
    }
    

}