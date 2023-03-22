<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->string('phone')->unique(); 
            $table->string('company_name')->nullable();
            $table->string('company_email')->unique();
            $table->string('company_phone')->unique(); 
            $table->string('product_category')->nullable(); 
            $table->string('site_address')->nullable(); 
            $table->string('product_images')->nullable(); 
            $table->string('branch_address')->nullable(); 
            $table->string('no_of_sales_person')->nullable();
            $table->integer('status')->default(0);
            $table->string('password')->nullable();
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
        Schema::dropIfExists('merchants');
    }
}
