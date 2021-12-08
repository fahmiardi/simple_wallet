<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount');
            $table->enum('type', ['debit', 'credit']);
            $table->decimal('balance_before');
            $table->decimal('balance_after');
            $table->morphs('fromable');
            $table->foreignId('user_id');
            $table->timestamps();

            // composite index
            $table->unique(['fromable_type', 'fromable_id']);

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
        Schema::dropIfExists('transactions');
    }
}
