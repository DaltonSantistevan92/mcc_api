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
        Schema::table('user_people', function (Blueprint $table) {
            $table->after('id', function ($table) {
                $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
                $table->foreignId('people_id')->constrained('people')->onUpdate('cascade')->onDelete('cascade');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_people', function (Blueprint $table) {
            $table->dropForeign('user_people_user_id_foreign');
            $table->dropColumn('user_id');

            $table->dropForeign('user_people_people_id_foreign');
            $table->dropColumn('people_id');
        });
    }
};
