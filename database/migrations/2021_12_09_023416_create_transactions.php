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
            $table->decimal('amount', 8, 1);
            $table->enum('type', ['debit', 'credit']);
            $table->decimal('balance_before', 8, 1);
            $table->decimal('balance_after', 8, 1);
            $table->morphs('fromable');
            $table->timestamps();

            // composite index
            $table->unique(['type', 'fromable_type', 'fromable_id']);
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
