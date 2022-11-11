<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('code',20)->unique();
            $table->decimal('rate',8,2)->default(0.00);
            $table->decimal('fees',8,2)->default(0.00);
            $table->decimal('commission_discount')->default(0.00);
            $table->integer('minimum_order_amount')->default(0);
            $table->integer('maximum_order_amount')->default(0);
            $table->string('status',10)->default('active');
            $table->dateTime('from')->nullable();
            $table->dateTime('to')->nullable();
            $table->text('notice')->nullable();
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
        Schema::dropIfExists('services');
    }
}
