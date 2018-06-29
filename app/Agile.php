<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Parameter;
use App\Log;

class Agile extends Model
{
	protected $conection;
	protected $AGILE_DOMAIN;
    protected $AGILE_USER_EMAIL;
    protected $AGILE_REST_API_KEY;
    protected $AGILE_JS_API_KEY;
    protected $ambiente;
    

    public function __construct()
    {        
        $Parameter = new Parameter();
        $this->ambiente = $Parameter->GetParameter('ambiente',1)[0]->valor;
        $this->AGILE_DOMAIN = ($Parameter->GetParameter('AGILE_DOMAIN',1)->isEmpty()) ? null : $Parameter->GetParameter('AGILE_DOMAIN',1)[0]->valor ;
        $this->AGILE_USER_EMAIL = ($Parameter->GetParameter('AGILE_USER_EMAIL',1)->isEmpty()) ? null : $Parameter->GetParameter('AGILE_USER_EMAIL',1)[0]->valor ;
        $this->AGILE_REST_API_KEY = ($Parameter->GetParameter('AGILE_REST_API_KEY',1)->isEmpty()) ? null : $Parameter->GetParameter('AGILE_REST_API_KEY',1)[0]->valor ;
        $this->AGILE_JS_API_KEY = ($Parameter->GetParameter('AGILE_JS_API_KEY',1)->isEmpty()) ? null : $Parameter->GetParameter('AGILE_JS_API_KEY',1)[0]->valor ;
        if (is_null($this->AGILE_DOMAIN) or is_null($this->AGILE_USER_EMAIL) or is_null($this->AGILE_REST_API_KEY) or is_null($this->AGILE_JS_API_KEY)) 
        {
            $this->conection = null;
        }
        else
        {
            $arrayName = array('AGILE_DOMAIN' => $this->AGILE_DOMAIN,'AGILE_USER_EMAIL' => $this->AGILE_USER_EMAIL,'AGILE_REST_API_KEY' => $this->AGILE_REST_API_KEY,'AGILE_JS_API_KEY' => $this->AGILE_JS_API_KEY,'ambiente'=>$this->ambiente);
            $this->conection = $arrayName;
        }
        $this->Log = new Log();
    }

	public function createUserAgile($datos = array(),$sobrecarga = null)
    {
        if (!is_null($sobrecarga)) {
            $sobrecarga = array_merge($sobrecarga,array('Salesforce',$this->ambiente));
        }
        else
        {
            $sobrecarga = array('Salesforce',$this->ambiente);
        }
        $datosUsuario = $this->crearUsuarioAgile(json_decode (json_encode ($datos), FALSE),$sobrecarga);
        try {
            $client = new \GuzzleHttp\Client(
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'auth' =>[$this->AGILE_USER_EMAIL, $this->AGILE_REST_API_KEY]
                ]
                );
            $url = "https://".$this->AGILE_DOMAIN.".agilecrm.com/dev/api/contacts";
            $request = $client->post($url,  [
                'body' =>  json_encode($datosUsuario),
            ]);
            $contact4 = $request->getBody()->getContents();
            $dataResponse = json_decode($contact4);
            if (isset($dataResponse->id)) {
                $envio = array('msn' => $contact4, 'status'=> true);
            }
            return $envio;
        }
        catch (\Exception $e) {
            $pos = strpos($e->getMessage(), 'response:');
            if($pos !== false )
            {   
                switch (substr($e->getMessage(), $pos+10, 10000)) {
                    case 'Sorry, duplicate contact found with the same email address.':
                        $envio = array('status'=> false,'msn'=> 'usuario duplicado');
                    break;
                    case '{"status":"401","exception message":"authentication issue"}':
                        $envio = array('status'=> false,'msn'=> 'error autenticando');
                    break;
                    default:
                        $envio = array('status'=> false,'msn'=> substr($e->getMessage(), $pos+10, 10000));
                    break;
                }
            }
            else
            {
                $envio = array('status'=> false,'msn'=> 'error sin definir: '.$e->getMessage());
            }
        }
        $msn = array('dataSend' => $datosUsuario);
        $envio = array_merge($msn,$envio);
        $this->Log->createLog(array('table' => 'agilelog','type'=>$envio['status'],'user_id'=>1,'message'=>json_encode($envio),'client_id'=>1));
        return $envio;
    }
    public function crearUsuarioAgile($datos,$cargamodulo,$idAgile = NULL,$etiquetaAdicional =  NULL)
    {
        $insertar = array_merge($cargamodulo,array('Carga Inicial'));
        $contact_json = array(
            "lead_score" => "0",
            "star_value" => "0",
            "tags" => $insertar,
            "properties" => array(
                array(
                    "name" => "first_name",
                    "value" => $datos->nombre,
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "last_name",
                    "value" => $datos->apellido,
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "email",
                    "value" => $datos->correo,
                    "subtype" => 'home',
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "company",
                    "value" => 'NOVOPAYMENT',
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "title",
                    "value" => '',
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "address",
                    "value" => json_encode(array(
                        "address" => '',
                        "city" => '',
                        "country" => 'Colombia',
                    )
                ),
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "phone",
                    "value" => $datos->celular,
                    "type" => "SYSTEM"
                ),
                array(
                    "name" => "habeasData",
                    "value" => 'on',
                    "type" => "CUSTOM"
                ),
                array(
                    "name" => "Actualizado",
                    "value" => (false) ? 'on' : 'off',
                    "type" => "CUSTOM"
                ),
                array(
                    "name" => "Codigo_Unico",
                    "value" => $datos->codigounico,
                    "type" => "CUSTOM"
                ),
                array(
                    "name" => "Estado_cliente",
                    "value" => $datos->estado,
                    "type" => "CUSTOM"
                ),
                array(
                    'name' => "Genero",
                    "value" => $datos->genero,
                    "type" => "CUSTOM"
                ),
                array(
                    'name' => "Documento",
                    "value" => $datos->documento,
                    "type" => "CUSTOM"
                ),
                array(
                    'name' => "Cargo",
                    "value" => $datos->cargo,
                    "type" => "CUSTOM"
                ) 
                            
            )
        );
        if (!is_null($idAgile)) {
            $merge = array('id' => $idAgile);
            $contact_json = array_merge($merge,$contact_json);
        }
        return $contact_json;
    }
}
