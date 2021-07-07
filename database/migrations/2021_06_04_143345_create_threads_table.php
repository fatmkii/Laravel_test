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
            $table->id()->startingValue(100001);
            $table->integer('forum_id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('title');
            $table->string('content');
            $table->string('title_color')->nullable(); //自定义标题颜色
            $table->string('created_IP')->nullable()->default(null);
            $table->string('created_binggan')->nullable()->default(null);
            $table->boolean('anti_jingfen')->default('0');
            $table->tinyInteger('sub_id')->unsigned()->default('0');
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
