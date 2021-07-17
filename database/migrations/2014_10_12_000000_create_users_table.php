<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id()->startingValue(100001);
            $table->string('binggan');
            $table->string('name')->nullable();
            $table->timestamp('binggan_verified_at')->nullable();
            $table->boolean('is_temp')->default(1);  //认证后转为非临时饼干
            $table->tinyInteger('status')->default(0); //是否被ban等状态
            $table->tinyInteger('admin')->default(0); //1=一般管理员，99=超管
            $table->integer('coin')->default(0); //通用货币
            $table->string('nickname')->nullable(); //自定义昵称
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
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
