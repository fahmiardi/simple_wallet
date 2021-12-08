<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTopups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_topups', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount');
            $table->enum('status', ['pending', 'canceled', 'done'])->default('pending');
            $table->foreignId('user_id');
            $table->timestamps();

            // foregin key constraints
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_topups');
    }
}
