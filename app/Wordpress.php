<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use App\Parameter;
use App\Log;

class Wordpress extends Model
{
	protected $WORDPRESS_CONECTION;
	protected $WORDPRESS_URL;
    protected $WORDPRESS_USER;
    protected $WORDPRESS_KEY;
    protected $WORDPRESS_AMBIENTE;
    

    public function __construct()
    {        
        $Parameter = new Parameter();
        $this->ambiente = $Parameter->GetParameter('ambiente',1)[0]->valor;
        $this->WORDPRESS_URL = ($Parameter->GetParameter('WORDPRESS_URL',1)->isEmpty()) ? null : $Parameter->GetParameter('WORDPRESS_URL',1)[0]->valor ;
        $this->WORDPRESS_USER = ($Parameter->GetParameter('WORDPRESS_USER',1)->isEmpty()) ? null : $Parameter->GetParameter('WORDPRESS_USER',1)[0]->valor ;
        $this->WORDPRESS_KEY = ($Parameter->GetParameter('WORDPRESS_KEY',1)->isEmpty()) ? null : $Parameter->GetParameter('WORDPRESS_KEY',1)[0]->valor ;
        if (is_null($this->WORDPRESS_KEY) or is_null($this->WORDPRESS_USER) or is_null($this->WORDPRESS_URL)) {
        	$this->WORDPRESS_CONECTION = null;
        }
        else
        {	
	        $arrayName = array('WORDPRESS_URL' => $this->WORDPRESS_URL,'WORDPRESS_USER' => $this->WORDPRESS_USER,'WORDPRESS_KEY' => $this->WORDPRESS_KEY,'WORDPRESS_AMBIENTE'=>$this->WORDPRESS_AMBIENTE);
	        $this->WORDPRESS_CONECTION = $arrayName;
        }
        $this->Log = new Log();
    }
    public function createUserWordpres($datos = array(),$textxml = null)
    {
        $salto ="\r\n\r\n";
        $outputFile = fopen("log/novopaimetsoap/wordpresslog.txt", "a");
        fwrite($outputFile, $textxml.PHP_EOL);
        $token = $this->tokenWordpres();
        fwrite($outputFile, 'token creado'.json_encode($token).' '.$salto);
        if (isset($token->token)) {
            try {
                $client = new \GuzzleHttp\Client(
                    ['headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer '.$token->token
                        ]
                    ]);
                $url = "http://millaextra.co/wp-json/wp/v2/users";
                $request = $client->post($url,  [
                    'body' =>  json_encode($datos),
                ]);
                $data = $request->getBody()->getContents();
                fwrite($outputFile, $data.$salto);
                fclose($outputFile);
                return json_decode($data);
            }
            catch (\Exception $e) {
                $pos = strpos($e->getMessage(), '{');
                fwrite($outputFile, substr($e->getMessage(), $pos, 10000));
                fclose($outputFile);
                return json_decode(substr($e->getMessage(), $pos, 10000));
            }
        }
        
    }
    public function tokenWordpres()
    {
        $client = new \GuzzleHttp\Client(
            ['headers' => [
                'Content-Type' => 'application/json'
                ]
            ]);
        $url = "http://millaextra.co/wp-json/jwt-auth/v1/token";
        $body['username'] = "admin";
        $body['password'] = "crear123";
        $request = $client->post($url,  [
            'body' =>  json_encode($body),
        ]);
        $data = $request->getBody()->getContents();
        return json_decode($data);
    }
    public function validateToken(Request $request, $localCall = false) {
         // First, we will convert the Symfony request to a PSR-7 implementation which will
        // be compatible with the base OAuth2 library. The Symfony bridge can perform a
        // conversion for us to a Zend Diactoros implementation of the PSR-7 request.
        $psr = (new DiactorosFactory)->createRequest($request);
        try {
            $psr = $this->server->validateAuthenticatedRequest($psr);

            // Next, we will assign a token instance to this user which the developers may use
            // to determine if the token has a given scope, etc. This will be useful during
            // authorization such as within the developer's Laravel model policy classes.
            $token = $this->tokens->find(
                $psr->getAttribute('oauth_access_token_id')
            );

            $currentDate = new DateTime();
            $tokenExpireDate = new DateTime($token->expires_at);

            $isAuthenticated = $tokenExpireDate > $currentDate ? true : false;

            if($localCall) {
                return $isAuthenticated;
            }
            else {
                return json_encode(array('authenticated' => $isAuthenticated));
            }
        } catch (OAuthServerException $e) {
            if($localCall) {
                return false;
            }
            else {
                return json_encode(array('error' => 'Something went wrong with authenticating. Please logout and login again.'));
            }
        }
    }
}
