<?php

namespace App;

require_once __DIR__  . '/../vendor/autoload.php';

use Exception;
use App\Utils\MyLogger;
use JsonRPC\Client;
use JsonRPC\HttpClient;


/* A class that implements core logic for jsonrpc-cli
 */
class AppCore
{

    // Contains options we care about.
    protected $params;
    
    public function __construct($params)
    {
        $this->params = $params;
    }

    /* Getter for params
     */
    private function get_params()
    {
        return $this->params;
    }    
    
    /* Performs json-rpc request.
     */
    public function request()
    {
        MyLogger::getInstance()->log( "initiating request", MyLogger::info );
                
        $params = $this->get_params();
        
        $client = new Client( $params['url'] );
        $client->getHttpClient()
            ->withUsername($params['user'])
            ->withPassword($params['pass']);        
        
        // record debug events.
        $client->getHttpClient()->debug_log = [];
        
        if( $params['httpfile'] ) {
            $httpout_fh = fopen($params['httpfile'], 'w');
            $client->getHttpClient()->request_fh = $httpout_fh;
            $client->getHttpClient()->response_fh = $httpout_fh;
        }
        
        $args = $params['params'];
        if($args === null) {
            $args = [];
        }
        else {
            $args = is_array($args) ? $args : [$args];
        }
        
        $is_raw = $params['format'] == 'raw';        
        $result = $client->execute($params['method'], $args, $reqattrs = [], $requestId = null, $headers = [], $is_raw);
        
        $map = [HttpClient::level_info => MyLogger::info,
                HttpClient::level_debug => MyLogger::debug,
                HttpClient::level_warn => MyLogger::warning];

        foreach( $client->getHttpClient()->debug_log as $event ) {
            MyLogger::getInstance()->log( $event['msg'], $map[$event['level']], $event['time'] );
        }
        
        @fclose($client->request_fd);
        
        return $result;        
    }
    
}

