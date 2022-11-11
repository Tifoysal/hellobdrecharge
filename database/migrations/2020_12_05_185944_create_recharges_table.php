<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRechargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('system_trx',128)->unique();
            $table->string('transaction_id',128)->nullable();
            $table->string('type',64)->comment('mobile or bank');
            $table->string('deposit_account',64)->comment('mobile or bank account');
            $table->integer('amount');
            $table->decimal('received_amount',10,2)->default(0.00);
            $table->string('sent_from',64)->nullable();
            $table->text('receipt')->nullable();
            $table->string('status',16)->default('pending');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('recharges');
    }
}
