<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_no', 20);
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('vendor_id')->unsigned()->nullable();
            $table->date('invoice_date');
            $table->date('invoice_due_date');
            $table->float('base_fare', 10, 2);
            $table->float('tax', 10, 2);
            $table->float('discount', 10, 2);
            $table->float('other_agent', 10, 2);
            $table->float('service_agent', 10, 2);
            $table->float('iata_comission', 10, 2);
            $table->float('govt_tax', 10, 2);
            $table->float('airline_payment', 10, 2);
            $table->float('total_receivable', 10, 2);
            $table->float('profit', 10, 2);
            $table->string('status', 20);
            $table->string('payment_method', 20);
            $table->string('note', 200)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');;
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
