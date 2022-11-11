<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('phone_number',20)->unique();
            $table->string('email',64)->nullable();
            $table->text('address')->nullable();
            $table->string('password');
            $table->string('nid_passport')->nullable();
            $table->string('pin',5)->nullable();
            $table->text('image')->nullable();
            $table->double('balance',12,2)->default(0.00);
            $table->string('status')->default('active');
            $table->string('type')->default('user');
            $table->text('docs')->nullable();
            $table->integer('attempt_count')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
