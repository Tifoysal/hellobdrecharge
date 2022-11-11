<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('trxno');
            $table->text('details');
            $table->double('debit',10,2)->default(0.00);
            $table->double('credit',10,2)->default(0.00);
            $table->double('balance',10,2)->default(0.00);
            $table->double('commission',10,2)->nullable()->default(0.00);
            $table->double('request_amount',10,2)->nullable()->default(0.00);
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
        Schema::dropIfExists('statements');
    }
}
