<?php
namespace App\Http\Controllers\Api;

use App\Http\Requests\OrderUnifiedRequest;
use App\Libraries\Bank\OrderPayments;
use App\Libraries\Bank\OrderUnified;
use App\Libraries\Bank\PayFieldsConfig;
use App\Models\FundMerchant;
use App\Models\FundPayConfig;
use App\Models\FundOrder;
use App\Models\FundOrderPayment;
use App\Models\FundPurseType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrderController extends CommonController {
	
	
	/**
	 * 包含了基本的支付请求参数
	 * @param OrderUnifiedRequest $request
	 * @return mixed
	 */
	public function unified(OrderUnifiedRequest $request){
		$basic_param = $request->only(['user_id','ebank_appid','order_no','order_type','product_name','return_url','notify_url','pay_type_group']);
		$order_no = $basic_param['order_no'];
		$product_name = $basic_param['product_name'];
		$pay_type_group = array_filter($basic_param['pay_type_group']);
		$pay_type_thread = '';
		$pay_type_thread_count = 0;
		$pay_type_wallet_count = 0;
		$amount = 0;
		$amount_wallet = 0;
		$param_all = json_encode($request->all());
		
		// 钱包类型列表
		$purse_type = collect();
		FundPurseType::active()->pluck('alias')->each(function($v) use (&$purse_type){
			$purse_type->push('wallet_'.$v);
		});
		$purse_type = $purse_type->flip();

		foreach($pay_type_group as $pay_type=>$price){
			// 如果是内部支付，否则第三方支付
			if($purse_type->has($pay_type)){		// 如果是内部钱包支付
				$pay_type_wallet_count++;
				$amount_wallet += $price;
			}else{
				$pay_type_thread_count++;
				$pay_type_thread = $pay_type;
			}
			$amount += $price;
		}
		// 计算三方支付金额，以总金额减去背部钱包金额为准
		$amount_thread = $amount - $amount_wallet;
		
		// 如果第三方支付+内部钱包支付不登录组合支付数量，就是参数有误
		if($pay_type_thread_count + $pay_type_wallet_count != count($pay_type_group)){
			exception('组合支付参数中存在不支持的扣款类型');
		}
		if($amount < 1){
			exception('订单金额不能少于 0.01 元');
		}

		if($pay_type_thread && $pay_type_thread_count != 1){
			exception('存在多个第三方支付，请修改组合支付方式');
		}
		
		// 获取商户信息
		$merchant = FundMerchant::where(['appid'=>$basic_param['ebank_appid']])->first(['id','pay_config_id']);
		$merchant_id = $merchant->id;
		$pay_config = $merchant_group_id = FundPayConfig::where(['id'=>$merchant->pay_config_id])->value('pay_config');
		
		$exist = FundOrder::where(['order_no'=>$order_no])->first();
		// 如果已支付，订单就不用再支付了
		if($exist && $exist->pay_status != 0){
			exception('该订单非待支付状态，无法唤醒支付');
		}
		
		// 如果是已存在订单则判断参数是否统一，不统一无法继续支付
		if($exist && $param_all !== $exist->param){
			exception('该订单号已存在但参数不同，无法继续支付');
		}
		
		// 三方支付，格式化配置为键值对
		$PayFieldsConfig = new PayFieldsConfig();
		config(['pay' => $PayFieldsConfig->parseConfig($pay_config)]);
		
		if(!$exist){
			DB::transaction(function() use ($order_no,$product_name,$amount,$basic_param,$merchant_id,$param_all,$pay_type_group){
				$add = [
					'user_id'		=> $basic_param['user_id'],
					'merchant_id'	=> $merchant_id,
					'order_no'		=> $order_no,
					'order_type'	=> $basic_param['order_type'],
					'product_name'	=> $product_name,
					'amount'		=> $amount,
					'return_url'	=> $basic_param['return_url'],
					'notify_url'	=> $basic_param['notify_url'],
					'param'			=> $param_all,
					'pay_status'	=> 0,
					'notify_status'	=> 0,
					'status'		=> 1,
				];
				$order_id = FundOrder::create($add)->id;
				
				// 存入支付方式表
				foreach($pay_type_group as $pay_type => $price){
					$add_payment = [
						'order_id'	=> $order_id,
						'type'		=> $pay_type,
						'amount'	=> $price,
					];
					FundOrderPayment::create($add_payment);
				}
			});
		}
		
		if($pay_type_thread){
			$pay_type_unified = $pay_type_thread;
		}else{
			$pay_type_unified = 'wallet';
		}
		$order_payments = new OrderPayments($merchant_id, $order_no,$amount_thread,$amount_wallet,$product_name);
		$result = $order_payments->$pay_type_unified();
		
		return json_success('下单成功',$result);
	}
}
