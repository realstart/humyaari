<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfficialInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('official_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name',100)->nullable();
            $table->string('company_address',400)->nullable();
            $table->string('company_phone_one',30)->nullable();
            $table->string('company_phone_two',30)->nullable();
            $table->string('company_email',100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('official_info');
    }
}
