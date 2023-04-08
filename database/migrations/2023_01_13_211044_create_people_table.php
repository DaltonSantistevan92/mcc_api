<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('identification', 13)->unique();
            $table->string('first_name', 25);
            $table->string('last_name', 25);
            $table->date('birthday')->nullable();
            $table->text('address')->nullable();
            $table->string('cellphone_number', 15)->nullable();
            $table->string('phone_number', 10)->nullable();
            $table->char('status', 1)->default('A');
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
        Schema::dropIfExists('people');
    }
};
