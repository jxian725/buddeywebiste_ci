<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->string('phone')->unique();            
            $table->string('license_number')->unique();
            $table->string('car_plate_number')->nullable();
            $table->string('car_model_number')->nullable();
            $table->string('road_tax_number')->nullable();
            $table->date('road_tax_expiry_date')->nullable();
            $table->string('insurance_number')->nullable();
            $table->date('insurance_expiry_date')->nullable();
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
        Schema::dropIfExists('drivers');
    }
}
