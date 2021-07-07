<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->id()->startingValue(1000001);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('forum_id');
            $table->integer('thread_id');
            $table->integer('floor');
            $table->tinyInteger('random_head')->nullable(); //论坛随机头像功能用
            $table->tinyInteger('create_by_admin')->default(0); //0=一般用户 1=管理员发布，2=系统发布
            $table->string('content')->nullable();
            $table->string('nickname')->nullable();
            $table->string('created_IP')->nullable()->default(null);
            $table->string('created_binggan')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replies');
    }
}
