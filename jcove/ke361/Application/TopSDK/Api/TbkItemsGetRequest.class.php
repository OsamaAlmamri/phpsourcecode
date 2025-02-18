<?php
namespace TopSDK\Api;

class TbkItemsGetRequest
{
	/** 
	 * 商品所在地
	 **/
	private $area;
	
	/** 
	 * 是否自动发货
	 **/
	private $autoSend;
	
	/** 
	 * 是否支持抵价券，设置为true表示该商品支持抵价券，设置为false或不设置表示不判断这个属性
	 **/
	private $cashCoupon;
	
	/** 
	 * 是否支持货到付款，设置为true表示该商品是支持货到付款，设置为false或不设置表示不判断这个属性
	 **/
	private $cashOndelivery;
	
	/** 
	 * 标准商品后台类目id。该ID可以通过taobao.itemcats.get接口获取到。
	 **/
	private $cid;
	
	/** 
	 * 30天累计推广量（与返回数据中的commission_num字段对应）上限.
	 **/
	private $endCommissionNum;
	
	/** 
	 * 佣金比率上限，如：2345表示23.45%。注：start_commissionRate和end_commissionRate一起设置才有效。
	 **/
	private $endCommissionRate;
	
	/** 
	 * 可选值和start_credit一样.start_credit的值一定要小于或等于end_credit的值。注：end_credit与start_credit一起使用才生效
	 **/
	private $endCredit;
	
	/** 
	 * 最高价格
	 **/
	private $endPrice;
	
	/** 
	 * 商品总成交量（与返回字段volume对应）上限。
	 **/
	private $endTotalnum;
	
	/** 
	 * 需返回的字段列表.可选值:num_iid,seller_id,nick,title,volume,pic_url,item_url,shop_url;字段之间用","分隔
	 **/
	private $fields;
	
	/** 
	 * 是否查询消保卖家
	 **/
	private $guarantee;
	
	/** 
	 * 标识一个应用是否来在无线或者手机应用,如果是true则会使用其他规则加密点击串.如果不传值,则默认是false.
	 **/
	private $isMobile;
	
	/** 
	 * 商品标题中包含的关键字. 注意:查询时keyword,cid至少选择其中一个参数
	 **/
	private $keyword;
	
	/** 
	 * 是否商城的商品，设置为true表示该商品是属于淘宝商城的商品，设置为false或不设置表示不判断这个属性
	 **/
	private $mallItem;
	
	/** 
	 * 是否30天维修，设置为true表示该商品是支持30天维修，设置为false或不设置表示不判断这个属性
	 **/
	private $onemonthRepair;
	
	/** 
	 * 是否海外商品，设置为true表示该商品是属于海外商品，默认为false
	 **/
	private $overseasItem;
	
	/** 
	 * 结果页数.1~10
	 **/
	private $pageNo;
	
	/** 
	 * 每页返回结果数.最大每页40
	 **/
	private $pageSize;
	
	/** 
	 * 是否如实描述(即:先行赔付)商品，设置为true表示该商品是如实描述的商品，设置为false或不设置表示不判断这个属性
	 **/
	private $realDescribe;
	
	/** 
	 * 是否支持7天退换，设置为true表示该商品支持7天退换，设置为false或不设置表示不判断这个属性
	 **/
	private $sevendaysReturn;
	
	/** 
	 * 默认排序:default

price_desc(价格从高到低) 
price_asc(价格从低到高) 
credit_desc(信用等级从高到低) 
commissionRate_desc(佣金比率从高到低) 
commissionRate_asc(佣金比率从低到高) 
commissionNum_desc(成交量成高到低) 
commissionNum_asc(成交量从低到高) 
commissionVolume_desc(总支出佣金从高到低) 
commissionVolume_asc(总支出佣金从低到高) 
delistTime_desc(商品下架时间从高到低) 
delistTime_asc(商品下架时间从低到高)
	 **/
	private $sort;
	
	/** 
	 * 30天累计推广量（与返回数据中的commission_num字段对应）下限.注：该字段要与end_commissionNum一起使用才生效
	 **/
	private $startCommissionNum;
	
	/** 
	 * 佣金比率下限，如：1234表示12.34%
	 **/
	private $startCommissionRate;
	
	/** 
	 * 卖家信用:

1heart(一心) 
2heart (两心) 
3heart(三心) 
4heart(四心) 
5heart(五心) 
1diamond(一钻) 
2diamond(两钻) 
3diamond(三钻) 
4diamond(四钻) 
5diamond(五钻) 
1crown(一冠) 
2crown(两冠) 
3crown(三冠) 
4crown(四冠) 
5crown(五冠) 
1goldencrown(一黄冠) 
2goldencrown(二黄冠) 
3goldencrown(三黄冠) 
4goldencrown(四黄冠) 
5goldencrown(五黄冠)
	 **/
	private $startCredit;
	
	/** 
	 * 起始价格.传入价格参数时,需注意起始价格和最高价格必须一起传入,并且 start_price <= end_price
	 **/
	private $startPrice;
	
	/** 
	 * 商品总成交量（与返回字段volume对应）下限。
	 **/
	private $startTotalnum;
	
	/** 
	 * 是否支持VIP卡，设置为true表示该商品支持VIP卡，设置为false或不设置表示不判断这个属性
	 **/
	private $vipCard;
	
	private $apiParas = array();
	
	public function setArea($area)
	{
		$this->area = $area;
		$this->apiParas["area"] = $area;
	}

	public function getArea()
	{
		return $this->area;
	}

	public function setAutoSend($autoSend)
	{
		$this->autoSend = $autoSend;
		$this->apiParas["auto_send"] = $autoSend;
	}

	public function getAutoSend()
	{
		return $this->autoSend;
	}

	public function setCashCoupon($cashCoupon)
	{
		$this->cashCoupon = $cashCoupon;
		$this->apiParas["cash_coupon"] = $cashCoupon;
	}

	public function getCashCoupon()
	{
		return $this->cashCoupon;
	}

	public function setCashOndelivery($cashOndelivery)
	{
		$this->cashOndelivery = $cashOndelivery;
		$this->apiParas["cash_ondelivery"] = $cashOndelivery;
	}

	public function getCashOndelivery()
	{
		return $this->cashOndelivery;
	}

	public function setCid($cid)
	{
		$this->cid = $cid;
		$this->apiParas["cid"] = $cid;
	}

	public function getCid()
	{
		return $this->cid;
	}

	public function setEndCommissionNum($endCommissionNum)
	{
		$this->endCommissionNum = $endCommissionNum;
		$this->apiParas["end_commissionNum"] = $endCommissionNum;
	}

	public function getEndCommissionNum()
	{
		return $this->endCommissionNum;
	}

	public function setEndCommissionRate($endCommissionRate)
	{
		$this->endCommissionRate = $endCommissionRate;
		$this->apiParas["end_commissionRate"] = $endCommissionRate;
	}

	public function getEndCommissionRate()
	{
		return $this->endCommissionRate;
	}

	public function setEndCredit($endCredit)
	{
		$this->endCredit = $endCredit;
		$this->apiParas["end_credit"] = $endCredit;
	}

	public function getEndCredit()
	{
		return $this->endCredit;
	}

	public function setEndPrice($endPrice)
	{
		$this->endPrice = $endPrice;
		$this->apiParas["end_price"] = $endPrice;
	}

	public function getEndPrice()
	{
		return $this->endPrice;
	}

	public function setEndTotalnum($endTotalnum)
	{
		$this->endTotalnum = $endTotalnum;
		$this->apiParas["end_totalnum"] = $endTotalnum;
	}

	public function getEndTotalnum()
	{
		return $this->endTotalnum;
	}

	public function setFields($fields)
	{
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}

	public function getFields()
	{
		return $this->fields;
	}

	public function setGuarantee($guarantee)
	{
		$this->guarantee = $guarantee;
		$this->apiParas["guarantee"] = $guarantee;
	}

	public function getGuarantee()
	{
		return $this->guarantee;
	}

	public function setIsMobile($isMobile)
	{
		$this->isMobile = $isMobile;
		$this->apiParas["is_mobile"] = $isMobile;
	}

	public function getIsMobile()
	{
		return $this->isMobile;
	}

	public function setKeyword($keyword)
	{
		$this->keyword = $keyword;
		$this->apiParas["keyword"] = $keyword;
	}

	public function getKeyword()
	{
		return $this->keyword;
	}

	public function setMallItem($mallItem)
	{
		$this->mallItem = $mallItem;
		$this->apiParas["mall_item"] = $mallItem;
	}

	public function getMallItem()
	{
		return $this->mallItem;
	}

	public function setOnemonthRepair($onemonthRepair)
	{
		$this->onemonthRepair = $onemonthRepair;
		$this->apiParas["onemonth_repair"] = $onemonthRepair;
	}

	public function getOnemonthRepair()
	{
		return $this->onemonthRepair;
	}

	public function setOverseasItem($overseasItem)
	{
		$this->overseasItem = $overseasItem;
		$this->apiParas["overseas_item"] = $overseasItem;
	}

	public function getOverseasItem()
	{
		return $this->overseasItem;
	}

	public function setPageNo($pageNo)
	{
		$this->pageNo = $pageNo;
		$this->apiParas["page_no"] = $pageNo;
	}

	public function getPageNo()
	{
		return $this->pageNo;
	}

	public function setPageSize($pageSize)
	{
		$this->pageSize = $pageSize;
		$this->apiParas["page_size"] = $pageSize;
	}

	public function getPageSize()
	{
		return $this->pageSize;
	}

	public function setRealDescribe($realDescribe)
	{
		$this->realDescribe = $realDescribe;
		$this->apiParas["real_describe"] = $realDescribe;
	}

	public function getRealDescribe()
	{
		return $this->realDescribe;
	}

	public function setSevendaysReturn($sevendaysReturn)
	{
		$this->sevendaysReturn = $sevendaysReturn;
		$this->apiParas["sevendays_return"] = $sevendaysReturn;
	}

	public function getSevendaysReturn()
	{
		return $this->sevendaysReturn;
	}

	public function setSort($sort)
	{
		$this->sort = $sort;
		$this->apiParas["sort"] = $sort;
	}

	public function getSort()
	{
		return $this->sort;
	}

	public function setStartCommissionNum($startCommissionNum)
	{
		$this->startCommissionNum = $startCommissionNum;
		$this->apiParas["start_commissionNum"] = $startCommissionNum;
	}

	public function getStartCommissionNum()
	{
		return $this->startCommissionNum;
	}

	public function setStartCommissionRate($startCommissionRate)
	{
		$this->startCommissionRate = $startCommissionRate;
		$this->apiParas["start_commissionRate"] = $startCommissionRate;
	}

	public function getStartCommissionRate()
	{
		return $this->startCommissionRate;
	}

	public function setStartCredit($startCredit)
	{
		$this->startCredit = $startCredit;
		$this->apiParas["start_credit"] = $startCredit;
	}

	public function getStartCredit()
	{
		return $this->startCredit;
	}

	public function setStartPrice($startPrice)
	{
		$this->startPrice = $startPrice;
		$this->apiParas["start_price"] = $startPrice;
	}

	public function getStartPrice()
	{
		return $this->startPrice;
	}

	public function setStartTotalnum($startTotalnum)
	{
		$this->startTotalnum = $startTotalnum;
		$this->apiParas["start_totalnum"] = $startTotalnum;
	}

	public function getStartTotalnum()
	{
		return $this->startTotalnum;
	}

	public function setVipCard($vipCard)
	{
		$this->vipCard = $vipCard;
		$this->apiParas["vip_card"] = $vipCard;
	}

	public function getVipCard()
	{
		return $this->vipCard;
	}

	public function getApiMethodName()
	{
		return "taobao.tbk.items.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkMaxValue($this->cid,2147483647,"cid");
		RequestCheckUtil::checkNotNull($this->fields,"fields");
		RequestCheckUtil::checkMaxValue($this->pageNo,10,"pageNo");
		RequestCheckUtil::checkMaxValue($this->pageSize,400,"pageSize");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
