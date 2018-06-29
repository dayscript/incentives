<?php

namespace App\Http\Controllers\Core;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Parameter;
use App\Agile;
use App\Log;
use Carbon\Carbon;


class BaseController extends Controller
{
    protected $Agile;
    protected $Log;
    protected $dateModify;

    public function __construct()
    {    
        $this->Log = new Log();
        $date = Carbon::now();
        if ($dateModifi = Parameter::GetParameter('dateModify',1)) {
            $this->dateModify = $date->addHour($dateModifi[0]->valor);
        }
        else{
            $this->dateModify = $date->addHour();
        }
    }

    public function generarCodigo($longitud,$tipo = 'alfaNumerico') {
         $key = '';
         switch ($tipo) {
            case 'alfaNumerico':
                 $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
            break;
            case 'numerico':
                 $pattern = '1234567890';
            break;
            case 'alfa':
                 $pattern = 'abcdefghijklmnopqrstuvwxyz';
            break;
            case 'clave':
                 $pattern = '123456789';
            break;
             default:
                 $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
                 break;
         }
         $max = strlen($pattern)-1;
         for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
         return $key;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Incentives\Core\Entity $entity
     * @return \Illuminate\Http\Response
     */
    public function respondsoap($returnedMsg) {
         print '<?xml version="1.0" encoding="UTF-8"?>
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                <soapenv:Body>
                <notifications xmlns="http://soap.sforce.com/2005/09/outbound">
                <Ack>' . $returnedMsg . '</Ack>
                </notifications>
                </soapenv:Body>
                </soapenv:Envelope>';
    }
    public function utf16_2_utf8($nowytekst) {
        $nowytekst = str_replace('%u0104','Ą',$nowytekst);    //Ą
        $nowytekst = str_replace('%u0106','Ć',$nowytekst);    //Ć
        $nowytekst = str_replace('%u0118','Ę',$nowytekst);    //Ę
        $nowytekst = str_replace('%u0141','Ł',$nowytekst);    //Ł
        $nowytekst = str_replace('%u0143','Ń',$nowytekst);    //Ń
        $nowytekst = str_replace('%u00D3','Ó',$nowytekst);    //Ó
        $nowytekst = str_replace('%u015A','Ś',$nowytekst);    //Ś
        $nowytekst = str_replace('%u0179','Ź',$nowytekst);    //Ź
        $nowytekst = str_replace('%u017B','Ż',$nowytekst);    //Ż
       
        $nowytekst = str_replace('%u0105','ą',$nowytekst);    //ą
        $nowytekst = str_replace('%u0107','ć',$nowytekst);    //ć
        $nowytekst = str_replace('%u0119','ę',$nowytekst);    //ę
        $nowytekst = str_replace('%u0142','ł',$nowytekst);    //ł
        $nowytekst = str_replace('%u0144','ń',$nowytekst);    //ń
        $nowytekst = str_replace('%u00F3','ó',$nowytekst);    //ó
        $nowytekst = str_replace('%u015B','ś',$nowytekst);    //ś
        $nowytekst = str_replace('%u017A','ź',$nowytekst);    //ź
        $nowytekst = str_replace('%u017C','ż',$nowytekst);    //ż

        $nowytekst = str_replace('\u00e1','á',$nowytekst);    //ż
        $nowytekst = str_replace('\u00e9','é',$nowytekst);    //ż
        $nowytekst = str_replace('\u00ed','í',$nowytekst);    //ż
        $nowytekst = str_replace('\u00f3','ó',$nowytekst);    //ż
        $nowytekst = str_replace('\u00fa','ú',$nowytekst);    //ż
        
        $nowytekst = str_replace('Ã³','ó',$nowytekst);    //ż


        
        return ($nowytekst);
    } 
}
