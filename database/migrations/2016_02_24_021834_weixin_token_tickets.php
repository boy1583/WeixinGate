<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WeixinTokenTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weixin_token_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('weixin_id');
            $table->string('token');
            $table->string('ticket');
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
