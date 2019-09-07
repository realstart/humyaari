<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCopmanyContactpersonAddressEmailToVendors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('vendors', function($table) {

            $table->string('company',100)->nullable();
            $table->string('contact_person',100)->nullable();
            $table->string('address',100)->nullable();
            $table->string('email',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down($table)
    {
        //
        $table->dropColumn('company','contact_person','address','email');
    }
}
