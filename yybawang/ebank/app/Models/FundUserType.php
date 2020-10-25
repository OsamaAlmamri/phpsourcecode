<?php

namespace App\Models;


class FundUserType extends CommonModel
{
    //
	protected $table = 'fund_user_type';
	
	public function scopeActive($query){
		return $query->where(['status'=>1]);
	}
}
