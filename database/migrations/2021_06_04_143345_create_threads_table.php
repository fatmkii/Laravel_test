<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->id()->startingValue(10001);
            $table->tinyInteger('sub_id')->unsigned()->default('0')->index(); //用来排序的
            $table->integer('forum_id')->index();
            $table->timestamps();
            $table->tinyInteger('is_deleted')->default(0); //0=正常；1=被用户删除；2=被管理员删除
            $table->string('nickname');
            $table->string('title');
            $table->string('sub_title')->default('[闲聊]');
            $table->integer('posts_num')->default('1'); //回帖数
            $table->string('title_color')->nullable(); //自定义标题颜色
            $table->boolean('is_anonymous')->default('0');
            $table->string('created_IP')->nullable()->default(null);
            $table->string('created_binggan')->nullable()->default(null);
            $table->boolean('anti_jingfen')->default('0');
            $table->integer('nissin_time')->unsigned()->default('86400');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}
