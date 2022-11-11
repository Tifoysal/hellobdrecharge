<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('sender', 16)->nullable()->comment('response from API');
//            $table->string('trans_type', 32)->nullable();
            $table->string('trx_id', 128)->nullable()->comment('actual transaction id from API');
            $table->string('tmp_trxid', 128)->nullable()->comment('local transaction id');
            $table->string('mobile', 32);
            $table->double('amount', 8,2)->comment('as vendor charge');
            $table->double('user_charge', 8,2)->comment('as a user charge');
            $table->string('type', 32)->comment('prepaid or postpaid');
            $table->string('trx_type', 32)->comment('RE=recharge or MB=mobile banking,DR=Data Recharge');
            $table->string('telco', 32);
            $table->dateTime('s_datetime')->nullable();
            $table->string('status');
            $table->text('remarks')->nullable();
            $table->text('message')->nullable()->comment('response from API');
            $table->decimal('fees')->nullable()->comment('from service');
            $table->decimal('rate')->nullable()->comment('from service');
            $table->decimal('commission_discount')->nullable()->comment('from service');
            $table->decimal('total_deduction')->nullable()->comment('from service');
            $table->integer('service_id')->nullable()->comment('from service');

            $table->softDeletes();
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
        Schema::dropIfExists('transactions');
    }
}
