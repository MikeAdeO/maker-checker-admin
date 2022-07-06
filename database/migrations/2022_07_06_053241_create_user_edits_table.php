<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_edits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->morphs('editable');
            $table->unsignedBigInteger('maker_id')->nullable();
            $table->unsignedBigInteger('checker_id')->nullable();
            $table->enum('status',['pending', 'reject', 'success'])->default('pending');
            $table->enum('request_type', ['create', 'update', 'destroy']);
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
        Schema::dropIfExists('user_edits');
    }
}
