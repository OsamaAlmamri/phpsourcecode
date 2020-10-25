<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundMerchantTable extends Migration
{
    /**
	 * API商户表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_merchant', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pay_config_id')->comment('支付参数配置ID');
            $table->string('name',50)->comment('商户名称');
            $table->char('appid',32)->comment('商户接口唯一appid');
            $table->char('secret',32)->comment('商户接口唯一秘钥');
			$table->tinyInteger('status')->comment('0无效，1有效');
			$table->string('remarks')->nullable();
			
			$table->unique('name','name');
			$table->unique('appid','appid');
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
        Schema::dropIfExists('fund_merchant');
    }
}
