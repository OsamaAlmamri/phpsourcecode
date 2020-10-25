<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests\BasicRequest;
use App\Libraries\Bank\EBank;
use App\Libraries\ExportCsv;
use App\Models\FundFreeze;
use App\Models\FundMerchant;
use App\Models\FundPurseType;
use App\Models\FundWithdraw;
use App\Models\FundWithdrawAlipay;
use App\Models\FundWithdrawWechat;
use Illuminate\Support\Facades\DB;

class WithdrawController extends CommonController {
	
	/**
	 * 提现到银行卡
	 * @return array
	 */
	public function bank(BasicRequest $request){
		$data['merchant'] = FundMerchant::pluck('name','id');
		$data['purse_type'] = FundPurseType::pluck('name','alias');
		$model = FundWithdraw::when($request->input('user_id'),function($query) use ($request){
			$query->where('user_id','like','%'.$request->input('user_id').'%');
		})
			->when($request->input('merchant_id'),function($query) use ($request){
				$query->where('merchant_id',$request->input('merchant_id'));
			})
			->when($request->input('realname'),function($query) use ($request){
				$query->where('realname','like','%'.$request->input('realname').'%');
			})
			->when($request->input('date'),function($query) use ($request){
				$query->where('created_at','>',$request->input('date.0').' 00:00:00')->where('created_at','<=',$request->input('date.1').' 23:59:59');
			})
			->orderBy('id','desc');
		if($request->input('export')){
			$model2 = clone $model;
//			dd($model2);
			(new ExportCsv())->name('导出用户银行卡提现')->field(['id'=>'id','user_id'=>'提现用户ID','pay_type'=>'提现方式','amount'=>'申请提现金额(分)','fee'=>'手续费(分)','amount_actual'=>'实际到账金额(分)','realname'=>'银行卡户名','bank_name'=>'银行名称','bank_no'=>'银行卡号','status'=>'状态，0申请中，1提现成功，2提现失败','created_at'=>'创建时间'])->data($model2)->save();
		}
		$data['list'] = $model->pages();
		return json_success('OK',$data);
	}
	
	/**
	 * 提现到支付宝
	 * @param BasicRequest $request
	 * @return array
	 */
	public function alipay(BasicRequest $request){
		$data['merchant'] = FundMerchant::pluck('name','id');
		$data['purse_type'] = FundPurseType::pluck('name','alias');
		$model = FundWithdrawAlipay::when($request->input('user_id'),function($query) use ($request){
			$query->where('user_id','like','%'.$request->input('user_id').'%');
		})
			->when($request->input('merchant_id'),function($query) use ($request){
				$query->where('merchant_id',$request->input('merchant_id'));
			})
			->when($request->input('realname'),function($query) use ($request){
				$query->where('realname','like','%'.$request->input('realname').'%');
			})
			->when($request->input('date'),function($query) use ($request){
				$query->where('created_at','>',$request->input('date.0').' 00:00:00')->where('created_at','<=',$request->input('date.1').' 23:59:59');
			})
			->orderBy('id','desc');
		if($request->input('export')){
			$model2 = clone $model;
			(new ExportCsv())->name('导出用户支付宝提现')->field(['id'=>'id','user_id'=>'提现用户ID','pay_type'=>'提现方式','amount'=>'申请提现金额(分)','fee'=>'手续费(分)','amount_actual'=>'实际到账金额(分)','realname'=>'支付宝实名','account'=>'支付宝账户','status'=>'状态，0申请中，1提现成功，2提现失败','created_at'=>'创建时间'])->data($model2)->save();
		}
		$data['list'] = $model->pages();
		return json_success('OK',$data);
	}
	
	/**
	 * 提现到微信
	 * @param BasicRequest $request
	 * @return array
	 */
	public function wechat(BasicRequest $request){
		$data['merchant'] = FundMerchant::pluck('name','id');
		$data['purse_type'] = FundPurseType::pluck('name','alias');
		$model = FundWithdrawWechat::when($request->input('user_id'),function($query) use ($request){
			$query->where('user_id','like','%'.$request->input('user_id').'%');
		})
			->when($request->input('merchant_id'),function($query) use ($request){
				$query->where('merchant_id',$request->input('merchant_id'));
			})
			->when($request->input('realname'),function($query) use ($request){
				$query->where('realname','like','%'.$request->input('realname').'%');
			})
			->when($request->input('date'),function($query) use ($request){
				$query->where('created_at','>',$request->input('date.0').' 00:00:00')->where('created_at','<=',$request->input('date.1').' 23:59:59');
			})
			->orderBy('id','desc');
		if($request->input('export')){
			$model2 = clone $model;
			(new ExportCsv())->name('导出用户支付宝提现')->field(['id'=>'id','user_id'=>'提现用户ID','pay_type'=>'提现方式','amount'=>'申请提现金额(分)','fee'=>'手续费(分)','amount_actual'=>'实际到账金额(分)','realname'=>'支付宝实名','account'=>'支付宝账户','status'=>'状态，0申请中，1提现成功，2提现失败','created_at'=>'创建时间'])->data($model2)->save();
		}
		$data['list'] = $model->pages();
		return json_success('OK',$data);
	}
	
	/**
	 * 提现卡成功（财务打款，手动点击成功）
	 * @param BasicRequest $request
	 * @return array
	 */
	public function success(BasicRequest $request){
		ignore_user_abort(true);
		set_time_limit(3600);
		$ids = $request->input('id');
		$type = ucfirst($request->input('type'));
		if(!is_array($ids)){
			$ids = [$ids];
		}
		$withdraw_class = '\\App\\Models\\FundWithdraw'.$type;
		$withdraw_object = new $withdraw_class();
		$EBank = new EBank();
		DB::transaction(function() use ($EBank, $withdraw_object, $ids){
			foreach($ids as $k => $id){
				$withdraw = $withdraw_object->findOrFail($id);
				// 如果已冻结,就跳过
				if(FundFreeze::isFreeze($withdraw->freeze_id)){
					continue;
				}
				$EBank->unfreeze($withdraw->freeze_id);
				$purse_type = ucfirst($withdraw->purse);
				$transfer_alias_user = 'user'.$purse_type.'ToUserWithdraw';
				$transfer_alias_system = 'userWithdrawToSystemWithdraw';
				$EBank->$transfer_alias_user($withdraw->merchant_id,$withdraw->user_id,$withdraw->user_id,$withdraw->amount,$withdraw->id,'提现成功，钱包中转',6);
				$transfer_id = $EBank->$transfer_alias_system($withdraw->merchant_id,$withdraw->user_id,0,$withdraw->amount,$withdraw->id,'提现成功，资金回收',7);
				$withdraw->status = 1;
				$withdraw->transfer_id = $transfer_id;
				$withdraw->save();
			}
		});
		
		return json_success('提现数据处理成功，资金已处理完毕');
	}
	
	/**
	 * 提现标记为失败，钱包余额解冻
	 * @param BasicRequest $request
	 * @return array
	 */
	public function fail(BasicRequest $request){
		ignore_user_abort(true);
		set_time_limit(3600);
		$id = $request->input('id');
		$remarks = $request->input('remarks');
		$type = ucfirst($request->input('type'));
		
		$withdraw_class = '\\App\\Models\\FundWithdraw'.$type;
		$withdraw_object = new $withdraw_class();
		$EBank = new EBank();
		DB::transaction(function() use ($EBank,$id,$withdraw_object,$remarks){
			$withdraw = $withdraw_object->findOrFail($id);
			$EBank->unfreeze($withdraw->freeze_id);
			$withdraw->status = 2;
			$withdraw->remarks = $remarks;
			$withdraw->save();
		});
		
		return json_success('已标记为失败，资金已解冻');
	}
}
