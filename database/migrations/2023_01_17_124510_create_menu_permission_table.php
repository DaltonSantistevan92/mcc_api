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
        Schema::create('menu_permission', function (Blueprint $table) {
            //$table->unsignedBigInteger('menu_id');
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onUpdate('cascade')->onDelete('cascade');

            //$table->unsignedBigInteger('permission_id');
            $table->foreignId('permission_id')->constrained('permissions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_permission');
    }
};
