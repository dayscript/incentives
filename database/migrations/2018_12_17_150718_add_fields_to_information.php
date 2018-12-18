<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('information', function (Blueprint $table) {

          $table->string('product_code')->after('zoho_lead_to_contact')->nullable();
          $table->string('restaurant_code')->after('zoho_lead_to_contact')->nullable();
          $table->string('sale_type')->after('zoho_lead_to_contact')->nullable();
          $table->string('quantity')->after('zoho_lead_to_contact')->nullable();
          $table->string('value')->after('zoho_lead_to_contact')->nullable();
          $table->string('invoice_date_up')->after('zoho_lead_to_contact')->nullable();


          // $invoice->identification  = $new_invoice['identification'];
          // $invoice->restaurant_code = $new_invoice['restaurant_code'];
          // $invoice->product_code    = $new_invoice['product_code'];
          // $invoice->sale_type       = $new_invoice['sale_type'];
          // $invoice->quantity        = $new_invoice['quantity'];
          // $invoice->value           = $new_invoice['value'];
          // $invoice->points          = round($new_invoice['value']/1000);
          // $invoice->invoice_date_up = $new_invoice['invoice_date_up'];
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('information', function (Blueprint $table) {
            $table->dropColumn('product_code');
            $table->dropColumn('invoice_date_up');
            $table->dropColumn('restaurant_code');
            $table->dropColumn('sale_type');
            $table->dropColumn('quantity');
            $table->dropColumn('value');
        });
    }
}
