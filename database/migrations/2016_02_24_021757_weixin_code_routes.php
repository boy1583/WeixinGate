<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WeixinCodeRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weixin_code_routes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('weixin_id');
            $table->string('state')->index();
            $table->string('route');
            $table->timestamps();

            $table->foreign('weixin_id')->references('id')->on('weixins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
