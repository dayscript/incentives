<?php

namespace App\Http\Controllers;

use App\Kokoriko\Invoice;
use App\Kokoriko\File;

use App\Incentives\Core\Entity;
use Illuminate\Http\Request;
use Log;

use Storage;

class InvoiceController extends Controller
{
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
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }

    /**
     * Create entities from file FTP
     * @param
     * @return array
     */
    public function createFromFile(){

      $file = new File;
      foreach( $file->getFolder('ventas/') as $key => $name ){ 
        $search_file = File::firstOrCreate(['name' => $name]);
        $search_file->getContentsFile($name,'|');
        $search_file->process('Invoice');
      }

      /*$limit_process_invoices = env('LIMIT_IMPORT_ENTITIES',0);
      $file_keys = array('identification','restaurant_code','invoice_code','product_code','sale_type','quantity','value','invoice_date_up','break_line');
      $message = '';
      $return = [];


      $files = Storage::disk('ftp')->allFiles('ventas/');

      foreach ($files as $num_file => $file) {
        if( count(File::where('name',$file)->get()) ) {$message= 'no new files'; continue; };

        $file_contents = Storage::disk('ftp')->get($file);

        if( $num_file > $limit_process_invoices ) break; # limita el numero de archivos a procesar

        $data_contents = explode(PHP_EOL,$file_contents);

        foreach( $data_contents as $key => $invoice){

          if(strlen($invoice) == 0) continue;

          $invoice = explode('|', $invoice);

          foreach ($invoice as $row => $value) {

            $new_invoice[$file_keys[$row]] = $value;
          }

          if( strpos($new_invoice['identification'], '.') != false ) {

              $new_invoice['identification'] = explode('.',$new_invoice['identification'])[0];
          }
          if( strpos($new_invoice['quantity'], '.') != false ) {

              $new_invoice['quantity'] = explode('.',$new_invoice['quantity'])[0];
          }
          unset($new_invoice['break_line']);

          $invoice = Invoice::firstOrCreate(['invoice_code' => $new_invoice['invoice_code']]);
          $invoice->identification  = $new_invoice['identification'];
          $invoice->restaurant_code = $new_invoice['restaurant_code'];
          $invoice->product_code    = $new_invoice['product_code'];
          $invoice->sale_type       = $new_invoice['sale_type'];
          $invoice->quantity        = $new_invoice['quantity'];
          $invoice->value           = $new_invoice['value'];
          $invoice->invoice_date_up = $new_invoice['invoice_date_up'];
          $invoice->save();
          $invoice->createZoho('Invoices');
        }

        $file_save = File::firstOrCreate(['name' => $file,'status' => 1]);

        $message .= 'Process File: '. $file_save ."\n";
      }
      Log::info('Impot invoices : '.$message);
      $return['status'] = 'success';
      $return['message'] = $message;
      return $return;*/
    }
}
