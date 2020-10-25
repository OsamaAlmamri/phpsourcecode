<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundUserPurseTable extends Migration
{
    /**
	 * 钱包余额表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_user_purse', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchant_id')->comment('商户ID，每个商户钱包不共享');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('user_type_id')->comment('user_type表的id，区分身份类型');
            $table->integer('purse_type_id')->comment('purse_type表的ID，区分钱包类型');
            $table->decimal('balance',30,0)->comment('用户钱包余额，单位分，可用余额需减去freeze字段才是真正可以使用的金额');
            $table->decimal('freeze',30,0)->comment('冻结余额，单位分');
			$table->tinyInteger('status')->comment('0无效，1有效');
			$table->string('remarks')->nullable();
            $table->timestamps();
	
			$table->unique(['merchant_id','user_id', 'user_type_id', 'purse_type_id'],'钱包唯一');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fund_user_purse');
    }
}
