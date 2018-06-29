<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use App\Lead;
use App\State;
use App\Incentives\Rules\Goal;
use App\Incentives\Core\Entity;
use App\Indicator;

class LeadController extends Core\BaseController
{
    protected $validator = array(array('name' => 'Cotizado','Indicatorcator'=>2),array('name' => 'Contratos','Indicatorcator'=>3),array('name' => 'Cerrada Ganada','Indicatorcator'=>4));
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function addleads(Request $request)
    {
    }
    public function addleadsSOAP(Request $request)
    {
        $this->Log = new Log();
        header("Content-Type: text/xml\r\n");
        ob_start();
        $capturedData = fopen('php://input', 'rb');
        $xml = fread($capturedData,5000);
        fclose($capturedData);
        ob_end_clean();
        if ($xml != '') {
            $xml = str_replace('soapenv:', '', str_replace('sf:', '', $xml));
            $xml = <<<XML
$xml 
XML;
            libxml_use_internal_errors(true);
            $elem = simplexml_load_string($xml);
            if($elem !== false)
            {
                $textxml = $xml;
                $xml = new \SimpleXMLElement($xml);
                //leads
                $Id = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Id)));
                //security
                $key = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->novolink__c)));
                //funcionario
                $Cedula_Identidad_de_usuario__c = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Cedula_Identidad_de_usuario__c)));
                $Email = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Email)));
                $Status = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Status)));
                $LastName = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->LastName)));
                $FirstName = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->FirstName)));
                $checkToken = false;
                $check = false; 
                $check_Status = false;
                $id_incentive = false;
                $msj ='';

                $pos = strpos($key, 'PC0000002'.date('Ymd'));
                if ($pos == 0) {
                    $rest = substr($key, 17, 10000);
                }
                if($pos !== false and !$checkToken)
                {
                    
                    foreach (Entity::All() as $key => $value) {
                        if( strtolower($value->identification)  == strtolower($this->utf16_2_utf8($Cedula_Identidad_de_usuario__c)) ) {
                            $id_incentive =  $value->id;
                        }
                    }
                    if ($id_incentive != false ) {
                        foreach (State::All() as $key1 => $value1) {
                            if( strtolower($value1->name)  == strtolower($this->utf16_2_utf8($Status)) ) {
                                $Status_id = $value1->id;
                                $check_Status = true;
                            }
                        }
                        if ( $check_Status == false ) {
                            $Status_id = State::create([ 'name'  =>  ucwords($this->utf16_2_utf8($Status)) ]);
                            $Status_id = $Status_id->id;
                        }
                        $msj = Lead::create([
                                'entity_id'  => $id_incentive,
                                'state_id'  =>  $Status_id,
                                'number_lead'  =>  substr($Id, 0,-3)
                        ]);
                        //agregar tag a agile de lead insertado 
                        $envio = array('dataSend' => $xml,'msn' => 'Carga de leads','error'=>$msj, 'status'=> true);
                    }
                    else
                    {
                        $envio = array('dataSend' => $xml,'msn' => 'No existe el funcionario en incentive','error'=>null, 'status'=> false);
                    }
                }
                else
                {
                    $envio = array('dataSend' => $xml,'msn' => 'Error Autenticacion','error'=>null, 'status'=> false);
                }
            }
            else
            {
                $errorText = '';
                foreach(libxml_get_errors() as $error)
                {
                    $errorText = $errorText.$error->message;
                }
                $envio = array('dataSend' => $xml,'msn' => 'no es xml','error'=>$errorText, 'status'=> false);
            }
        }
        else
        {
            $envio = array('dataSend' => $xml,'msn' => 'no hay cadena de xml', 'status'=> false,'error'=>null);
        }
        $this->Log->createLog(array('table' => 'addleadsSOAP','type'=>$envio['status'],'user_id'=>1,'message'=>json_encode($envio),'client_id'=>1));
        $this->respondsoap(($envio['status']) ? 'true' : 'false');
        /*
        <?xml version="1.0" encoding="UTF-8"?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchem$">
         <soapenv:Body>
          <notifications xmlns="http://soap.sforce.com/2005/09/outbound">
           <OrganizationId>00Do0000000e0vQEAQ</OrganizationId>
           <ActionId>04k1N0000008pXOQAY</ActionId>
           <SessionId xsi:nil="true"/>
           <EnterpriseUrl>https://novopayment.my.salesforce.com/services/Soap/c/42.0/00Do0000000e0vQ</EnterpriseUrl>
           <PartnerUrl>https://novopayment.my.salesforce.com/services/Soap/u/42.0/00Do0000000e0vQ</PartnerUrl>
           <Notification>
            <Id>04l1N00000HVEMpQAP</Id>
            <sObject xsi:type="sf:Lead" xmlns:sf="urn:sobject.enterprise.soap.sforce.com">
             <sf:Id>00Q1N00000bS2eOUAS</sf:Id>
             <sf:Cedula_Identidad_de_usuario__c>1127576689</sf:Cedula_Identidad_de_usuario__c>
             <sf:Company>NovoPayment</sf:Company>
             <sf:Email>kflores@tebca.com</sf:Email>
             <sf:FirstName>Kevin</sf:FirstName>
             <sf:LastName>Flores</sf:LastName>
             <sf:Status>Abierto</sf:Status>
             <sf:novolink__c>PC0000002201803278FIgREISTqXghB2Y8Pl3rmncuhuI8ueJYL3uJhnOR0</sf:novolink__c>
            </sObject>
           </Notification>
          </notifications>
         </soapenv:Body>
        </soapenv:Envelope>
        */
    }
    public function addstagesSOAP()
    {
        $this->Log = new Log();
        header("Content-Type: text/xml\r\n");
        ob_start();
        $capturedData = fopen('php://input', 'rb');
        $xml = fread($capturedData,5000);
        fclose($capturedData);
        ob_end_clean();
        if ($xml != '') {
            $xml = str_replace('soapenv:', '', str_replace('sf:', '', $xml));
            $xml = <<<XML
$xml 
XML;
            libxml_use_internal_errors(true);
            $elem = simplexml_load_string($xml);
            if($elem !== false)
            {
                $textxml = $xml;
                $xml = new \SimpleXMLElement($xml);
                //leads
                $Id_de_Lead__c = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Id_de_Lead__c)));
                //security
                $key = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->novolink__c)));
                //funcionario
                $ID_de_funcionario__c = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->ID_de_funcionario__c)));
                $StageName = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->StageName)));
                $checkToken = false;
                $check = false; 
                $check_Status = false;
                $id_incentive = false;
                $msj ='';

                $pos = strpos($key, 'PC0000002'.date('Ymd'));
                if ($pos == 0) {
                    $rest = substr($key, 17, 10000);
                }
                if($pos !== false and !$checkToken)
                {
                    $leadCampo =  Lead::where('number_lead', $Id_de_Lead__c)->first();
                    if (!is_null($leadCampo)) {
                        foreach (State::All() as $key1 => $value1) {
                            if( strtolower($value1->name)  == strtolower($this->utf16_2_utf8($StageName)) ) {
                                $Status_id = $value1->id;
                                $check_Status = true;
                            }
                        }
                        if ( $check_Status == false ) {
                            $Status_id = State::create([ 'name'  =>  ucwords($this->utf16_2_utf8($StageName)) ]);
                            $Status_id = $Status_id->id;
                        }
                        $msj = Lead::create([
                                'entity_id'  => $leadCampo->entity_id,
                                'state_id'  =>  $Status_id,
                                'number_lead'  =>  $Id_de_Lead__c
                        ]);
                        $entity   = Entity::firstOrCreate(['id' => $leadCampo->entity_id]);
                        foreach ($this->validator as $key) {
                            if ($key['name'] == $StageName) {
                                $count = $this->stagesforleads($leadCampo->entity_id,$key['name'],$Id_de_Lead__c);
                                if ($rule = Indicator::find($key['Indicatorcator'])) {
                                    switch ($rule->type_id) {
                                        case 1:
                                            $goal = Goal::where('date_start','<=',$this->dateModify->toDateString())
                                            ->where('date_end','>=',$this->dateModify->toDateString())
                                            ->where('client_id','=',1)
                                            ->where('rol_id','=',$entity->role_id)
                                            ->where('indicator_id','=',$key['Indicatorcator'])->get();
                                            if (isset($goal[0])) {
                                                $goal= $goal[0];
                                                if ($gvalue = $entity->goals()->wherePivot('date', $this->dateModify->toDateString())->where('goals.id',$goal->id)->first()) {
                                                    $entity->goals()->wherePivot('date', $this->dateModify->toDateString())->updateExistingPivot($goal->id, ['value' => $goal->weight, 'real' => $count['conteo']]);
                                                }
                                                else
                                                {
                                                    $ids = $entity->goals()->pluck('entity_goal.id')->toArray();
                                                    $entity->goals()->attach($goal->id, ['value' => $goal->weight, 'date' => $this->dateModify->toDateString(), 'real' => $count['conteo']]);
                                                }
                                            }
                                        break;
                                        case 2:

                                        break;
                                    }
                                }
                            }
                        }
                        $envio = array('dataSend' => json_encode($xml),'msn' => 'Cargar Oportunidad','error'=>$msj, 'status'=> true);
                    }
                    else
                    {
                        $envio = array('dataSend' => json_encode($xml),'msn' => 'No existe el leads en incentive','error'=>null, 'status'=> false);
                    }
                }
                else
                {
                    $envio = array('dataSend' => json_encode($xml),'msn' => 'Error Autenticacion','error'=>null, 'status'=> false);
                }
            }
            else
            {
                $errorText = '';
                foreach(libxml_get_errors() as $error)
                {
                    $errorText = $errorText.$error->message;
                }
                $envio = array('dataSend' => json_encode($xml),'msn' => 'no es xml','error'=>$errorText, 'status'=> false);
            }
        }
        else
        {
            $envio = array('dataSend' => json_encode($xml),'msn' => 'no hay cadena de xml', 'status'=> false,'error'=>null);
        }
        $this->Log->createLog(array('table' => 'addstagesSOAP','type'=>$envio['status'],'user_id'=>1,'message'=>json_encode($envio),'client_id'=>1));
        $this->respondsoap(($envio['status']) ? 'true' : 'false');
        /*
        <?xml version="1.0" encoding="UTF-8"?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchem$
         <soapenv:Body>
          <notifications xmlns="http://soap.sforce.com/2005/09/outbound">
           <OrganizationId>00Do0000000e0vQEAQ</OrganizationId>
           <ActionId>04k1N0000008pXPQAY</ActionId>
           <SessionId xsi:nil="true"/>
           <EnterpriseUrl>https://novopayment.my.salesforce.com/services/Soap/c/42.0/00Do0000000e0vQ</EnterpriseUrl>
           <PartnerUrl>https://novopayment.my.salesforce.com/services/Soap/u/42.0/00Do0000000e0vQ</PartnerUrl>
           <Notification>
            <Id>04l1N00000HVEdbQAH</Id>
            <sObject xsi:type="sf:Opportunity" xmlns:sf="urn:sobject.enterprise.soap.sforce.com">
             <sf:Id>0061N00000WD1shQAD</sf:Id>
             <sf:ID_de_funcionario__c>1127576689</sf:ID_de_funcionario__c>
             <sf:Id_de_Lead__c>00Q1N00000bS2Zo</sf:Id_de_Lead__c>
             <sf:StageName>Pre-Calificación</sf:StageName>
             <sf:novolink__c>PC0000002201803278FIgREISTqXghB2Y8Pl3rmncuhuI8ueJYL3uJhnOR0</sf:novolink__c>
            </sObject>
           </Notification>
          </notifications>
         </soapenv:Body>
        </soapenv:Envelope>
        */
    }
    public function stagesforleads($entity_id,$validator,$number_lead)
    {
            $conteoLeads = 0;
            $conteoPrecalificado = 0;
            $entityData =  Lead::where('entity_id', $entity_id)->select('entity_id','number_lead')->groupBy('number_lead')->groupBy('entity_id')->get();
            foreach ($entityData as $key) {
                $conteoLeads = $conteoLeads+1;
                $leadsData =  Lead::where('entity_id', $entity_id)->where('number_lead',$number_lead)->select('entity_id','number_lead','state_id')->groupBy('number_lead')->groupBy('state_id')->groupBy('entity_id')->get();
                foreach ($leadsData as $key1) 
                {
                    if ($this->utf16_2_utf8($key1->state->name) == $validator) {
                        $conteoPrecalificado = $conteoPrecalificado+1;
                    }
                }
            }
            return array('leads' => $conteoLeads, 'conteo'=>$conteoPrecalificado);
    }
    public function testSOAP()
    {
        header("Content-Type: text/xml\r\n");
        ob_start();
        $capturedData = fopen('php://input', 'rb');
        $xml = fread($capturedData,5000);
        fclose($capturedData);
        ob_end_clean();
        $outputFile = fopen("log/novopaimetsoap/testSOAP.txt", "a");
        fwrite($outputFile, $xml.PHP_EOL);
        fclose($outputFile);
    }
}
