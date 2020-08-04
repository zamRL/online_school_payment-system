<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignedStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigned_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('section_id');
            $table->timestamps();

            $table->unique(['user_id', 'section_id']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('section_id')->references('id')->on('sections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assigned_students', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'section_id']);
        });

        Schema::dropIfExists('assigned_students');
    }
}
