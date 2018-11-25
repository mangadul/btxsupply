
<?php

/*
https://stellar.expert/explorer/public/asset/BTX-GBBAMI2WU6WJHDL3CQKT4LPXUC76WCEMQJMJIVQGL2G5IKJ2JHEVHG3G
https://horizon.stellar.org/accounts/GBBAMI2WU6WJHDL3CQKT4LPXUC76WCEMQJMJIVQGL2G5IKJ2JHEVHG3G/payments?limit=200
*/

error_reporting(0);
ini_set('max_execution_time', 7200);
ini_set('default_socket_timeout', 7200);

require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise;
use Psr\Http\Message\ResponseInterface;


class getSupplyBtx
{

    function __construct(){      

        $ret = $this->getRequest("https://horizon.stellar.org/accounts/GBBAMI2WU6WJHDL3CQKT4LPXUC76WCEMQJMJIVQGL2G5IKJ2JHEVHG3G/payments?limit=200");
        $res = json_decode($ret, true);
        //$this->_dump($res);
        $token_burn = 0;
        if(count($res['_embedded']['records']) > 0) {
	        foreach ($res['_embedded']['records'] as $key => $val) {
	        	if(isset($val['asset_code']) && isset($val['to'])) {
	        		if(($val['asset_code'] === 'BTX') && ($val['to'] === 'GBBAMI2WU6WJHDL3CQKT4LPXUC76WCEMQJMJIVQGL2G5IKJ2JHEVHG3G')) {
						$token_burn = $token_burn + $val['amount'];
	        		}
	        	}         	
	        }
        }        	
        //echo "Token Burn: ".$token_burn.PHP_EOL;
        $total_supply = 21000000;
        $remain_supply = $total_supply - $token_burn;  
        echo number_format($remain_supply, 7, '.', '');
    }

    private function getHorizonRequest($req){
        $client = new Client();
        try {
            $response = $client->get($req, ['future' => true, 'timeout' => 120, 'connect_timeout' => 120, 'headers' => ['Cache-Control' => 'no-cache, no-store']]);
            if($response->getStatusCode() === 200){
                $body = $response->getBody()->getContents();   
                if($body !== null){
                return $body;                    
                } else return null;        

            }
        } catch (RequestException $e) {
            //echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
                //echo $e->getMessage() . "\n";
                //echo $e->getRequest()->getMethod();
            }
        }
    }

    private function getRequest($req){
        $client = new Client();
        try {
            $response = $client->get($req, ['future' => true, 'timeout' => 120, 'connect_timeout' => 120, 'headers' => ['Cache-Control' => 'no-cache, no-store, must-revalidate, max-age=0']]);
            if($response->getStatusCode() === 200){
        $body = $response->getBody()->getContents();
                if($body !== null){
                    return $body;                    
                } else return null;  
            }
        } catch (RequestException $e) {
            // echo $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                //echo $e->getResponse() . "\n";
                //echo $e->getMessage() . "\n";
                //echo $e->getRequest()->getMethod();
            }
        }
    }

    private function _dump($str){
		print('<pre>');
        print_r($str);
		print('</pre>');
    }

}

new getSupplyBtx();
