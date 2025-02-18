<?php
/**
 * 销售管理
 * @author 齐迹  email:smpss2012@gmail.com
 *
 */
class c_sales extends base_c {
	function __construct($inPath) {
		parent::__construct ();
		if (self::isLogin () === false) {
			$this->ShowMsg ( "请先登录！", $this->createUrl ( "/main/index" ) );
		}
		if (self::checkRights ( $inPath ) === false) {
			//$this->ShowMsg("您无权操作！",$this->createUrl("/system/index"));
		}
		$this->params ['inpath'] = $inPath;
		$this->params ['head_title'] = "销售管理-" . $this->params ['head_title'];
	}
	
	function pageindex($inPath) {
		$url = $this->getUrlParams ( $inPath );
		$page = $url ['page'] ? ( int ) $url ['page'] : 1;
		$ymd = date ( "Y-m-d", time () );
		if ($_POST) {
			$post = base_Utils::shtmlspecialchars ( $_POST );
			$key = base_Utils::getStr ( $_POST ['key'] );
			$stime = base_Utils::getStr ( $_POST ['stime'] );
			$etime = base_Utils::getStr ( $_POST ['etime'] );
			$type = base_Utils::getStr ( $_POST ['type'] );
			$condi = '';
			switch($type)
			{
				case 'yesid':
				$condi='mid!=0  AND sales_type=0 ';
				break;
				case 'noid':
				$condi='mid=0 AND sales_type=0 ';
				break;
				case 'return':
				$condi='sales_type=1 ';
				break;
				default:
				$condi='';
				}
			if ($key) {
				$condi = $condi ? $condi . " and " : "";
				$condi .= "(order_id like '%{$key}%' or goods_name like '%{$key}%' or realname like '%{$key}%' or membercardid like '{$key}')";
			}
			if ($stime) {
				$etime = $etime ? $etime : $ymd;
				$condi = $condi ? $condi . " and" : "";
				$condi .= " dateymd between '{$stime}' and '{$etime}'";
			}
					$getPath='?key='.$key.'&type='.$type.'&stime='.$stime.'&etime='.$etime;

		}
		$saleObj = new m_sales ();
		$saleObj->setCount ( true );
		$saleObj->setPage ( $page );
		$saleObj->setLimit ( base_Constant::PAGE_SIZE );
		$rs = $saleObj->select ( $condi, "", "", "order by sid desc" );
		$this->params ['sales'] = $rs->items;
		$this->params ['key'] = $key;
		$this->params ['type'] = $type;
		$this->params ['stime'] = $stime;
		$this->params ['etime'] = $etime;
		$this->params ['pagebar'] = $this->PageBar ( $rs->totalSize, base_Constant::PAGE_SIZE, $page, $inPath, 'style1',$getPath );
		return $this->render ( 'sales/index.html', $this->params );
	}
	
	function pagesales($inPath) {
		$url = $this->getUrlParams ( $inPath );
				$categoryObj = new m_category ();
		$this->params['catelist'] = $categoryObj->getOrderCate('&nbsp;&nbsp;&nbsp;&nbsp;');

		session_start ();
		$order_id = $_SESSION ['order_id'];
		$tempsales = new m_tempsales();
		if (! $order_id) {
					
			$order_id = date ( "mdHis", time () ) . base_Utils::random ( 4, 1 );
			$_SESSION ['order_id'] = $order_id;
		}else{
			$info = $tempsales->select("order_id='{$order_id}'")->items;
		}
		if ($url ['ac'] == "del") {
			if($order_id){
				if(!$tempsales->delOrder($order_id)){
					$this->ShowMsg("清空出错！".$tempsales->getError());
				}
			}
			$this->ShowMsg ( "操作成功！", $this->createUrl ( "/sales/sales" ), 1, 1 );
		}
		if ($_POST) {
			$post = base_Utils::shtmlspecialchars ( $_POST );
			$goods_sn = base_Utils::getStr ( $_POST ['goods_sn'] );
			$num = ( float ) $_POST ['num'] ? ( float ) $_POST ['num'] : 1;
			$ishas = 0;
			if (is_array ( $info )) {
				foreach ( $info as $k => $v ) {
					if ($v ['goods_sn'] == $goods_sn) {
						$info [$k] ['num'] += $num;
						$ishas = 1;
						if ($info [$k] ['num'] > $v ['stock']) {
							$info [$k] ['num'] -= $num;
							$this->ShowMsg ( "该商品库存不足！", $this->createUrl ( "/sales/sales" ) );
						}
						$data['order_id'] = $order_id;
						$data['goods_id'] = $v['goods_id'];
						$data['num'] = $info [$k] ['num'];
						if(!$tempsales->updateOrder($data)){
							$this->ShowMsg("插入订单失败！");
						}
					}
				}
			}
			if (! $ishas) {
				$goodsObj = new m_goods ();
				$goods = $goodsObj->getSalePrice ( $goods_sn );
				if (! $goods)
					$this->ShowMsg ( "商品信息不存在", $this->createUrl ( "/sales/sales" ), 1 );
				if ($num > $goods ['stock'])
					$this->ShowMsg ( "该商品库存不足！", $this->createUrl ( "/sales/sales" ) );
				$goods ['num'] = $num;
				if(!$tempsales->insertOrder($goods,$order_id)){
					$this->ShowMsg("插入订单失败！".$tempsales->getError());
				}
			}
			//$_SESSION ['goodsInfo'] = $info;
			$this->redirect ( $this->createUrl ( "/sales/sales" ) );
		}
		$total = $discount = 0;
		if (is_array ( $info )) {
			foreach ( $info as $v ) {
				$total += $v ['num'] * $v ['out_price'];
				$discount += $v ['num'] * $v ['p_discount'];
			}
		}

		$this->params ['total'] = $total;
		$this->params ['order_id'] = $order_id;
		$this->params ['discount'] = $discount;
		$this->params ['info'] = $info;
		 strlen($info)!=0?$this->params ['order'] =false:$this->params ['order'] =true;
		//print_r($info);
		return $this->render ( 'sales/sales.html', $this->params );
	}
	function pageajaxSales(){
		$tempsales = new m_tempsales();
				if ($_POST) {
					$id= $_POST['Pid'];
					$type= $_POST['Ptype'];
					$val= ( float )$_POST['Pval'];
					$info = $tempsales->select("id='{$id}'")->items;
					$info=$info[0];
					$data['id']=$id;
					if($type=='num'){
						if($val>$info['stock']){echo '该商品库存不足！';exit;}
						$data['num']=$val;
							if(!$tempsales->changeNum($data)){
								echo "修改数量失败！";
							}
					}else{
					$data['out_price']=$val;
							if(!$tempsales->changePrice($data)){
								echo "修改价格失败！".$data['id'];
							}else{echo "修改价格OK！";}
					}
				}
		}
	function pageOut($inPath) {
		$url = $this->getUrlParams ( $inPath );
		$order_id = $url['oid'];
		session_start ();
		$tempsales = new m_tempsales();
		$info = $tempsales->select("order_id='{$order_id}'")->items;
		//$info = $_SESSION ['goodsInfo'];
		$saleObj = new m_sales ();
		$sales = $mem_rs = array ();
		$purchaseObj = new m_purchase ();
		if (is_array ( $info )) {
			$url['ac']='';
			$dateline = time();
			$goodsObj = new m_goods ();
			$cardid = base_Utils::getStr ( $_POST ['cardid'] );
			if ($cardid) {
				$memberObj = new m_member ();
				$mem_rs = $memberObj->getMemberPrice ( $cardid );
				if (! $mem_rs ['mid'])
					$this->ShowMsg ( "会员卡不存在！" );
				$sales ['mid'] = $mem_rs ['mid'];
				$sales ['membercardid'] = $mem_rs ['membercardid'];
				$sales ['realname'] = $mem_rs ['realname'];
			}
			//$order_id = date ( "mdHis", time () ) . base_Utils::random ( 4, 1 );
			$mem_amount = 0;
			$pro_amount = 0;
			foreach ( $info as $k => $v ) {
				$out_amount += sprintf ( "%01.2f", $v ['out_price'] * $v ['num'] ); //总价
				$pro_amount += sprintf ( "%01.2f", $v ['p_discount'] * $v ['num'] ); //促销优惠的总价
				$sales ['order_id'] = $order_id;
				$sales ['goods_id'] = $v ['goods_id'];
				$sales ['cat_id'] = $v ['cat_id'];
				$sales ['goods_sn'] = $v ['goods_sn'];
				$sales ['goods_name'] = $v ['goods_name'];
				$sales ['num'] = $v ['num'];
				$sales ['out_price'] = $v ['out_price'];
				$sales ['in_price'] = $goodsObj->getAvgPrice ( $v ['goods_id'] );
				$sales ['p_discount'] = $v ['p_discount']; //促销优惠的金额
				$sales ['price'] = $sales ['out_price'] - $sales ['p_discount'];
				if ($v ['ismemberprice'] == 1 and $mem_rs ['mid']) {
					$sales ['m_discount'] = ($v ['out_price'] - $v ['p_discount']) * (100-$mem_rs ['discount'])/ 100; //会员+促销优惠
					$sales ['m_discount'] = sprintf ( "%01.2f", $sales ['m_discount'] );
					$sales ['price'] = $sales ['out_price'] - $sales ['m_discount'];
					$mem_amount += sprintf ( "%01.2f", $sales ['m_discount'] * $v ['num'] ); //会员+促销总价
				}
				$sales ['dateymd'] = date ( "Y-m-d", time () );
				$sales ['dateline'] = time ();
				if (! $saleObj->insert ( $sales )) {
					$this->ShowMsg ( "添加销售记录错误！" . $saleObj->getError () );
				}
				$purchaseObj->outStock ( $sales ['goods_id'], $v ['num'], sprintf ( "%01.2f", $sales ['price'] * $v ['num'] ) );
			}
			//计算应收金额
			$real_amount = $out_amount-$mem_amount-$pro_amount;
			if ($sales ['mid']) {
				$memberObj->setCredit ( $sales ['mid'],$real_amount*base_Constant::RATIO);
				$m_cacheObj = new m_membercache();
				$m_cacheObj->updatecache($sales ['mid'],'buyfre=buyfre+1,totalspanding=totalspanding+'.$real_amount);
			}
			$tempsales->delOrder($order_id);//清除临时销售记录
		}
		$goods = $saleObj->select ( "order_id={$order_id}" )->items;
		if($url['ac']=='p'){//独立打印
			if(!is_array($goods)){
				$this->ShowMsg ( "订单中没有任何商品！" );
			}
			foreach ( $goods as $k => $v ) {
				$out_amount += sprintf ( "%01.2f", $v ['out_price'] * $v ['num'] ); //应收金额
				$pro_amount += sprintf ( "%01.2f", $v ['p_discount'] * $v ['num'] ); //促销优惠的总价
				$mem_amount += sprintf ( "%01.2f", $v ['m_discount'] * $v ['num'] ); //会员优惠的总价
				$real_amount += sprintf ( "%01.2f", $v ['price'] * $v ['num'] ); //实收金额 减去会员优惠和促销优惠
				$dateline = $v['dateline'];
			}
		}
		$this->params ['goods'] = $goods;
		$this->params ['order_id'] = $order_id;
		$this->params ['out_amount'] = $out_amount;
		$this->params ['real_amount'] = $real_amount;
		$this->params ['pro_amount'] = $pro_amount;
		$this->params ['mem_amount'] = $mem_amount;
		$this->params ['dateline'] = $dateline;
		$_SESSION ['order_id'] = "";
		return $this->render ( 'sales/out.html', $this->params );
	}
	/**
	 * 打印小票
	 * @param array $inPath
	 */
	function pageprint($inPath){
		$url = $this->getUrlParams ( $inPath );
		$page = $url ['page'] ? ( int ) $url ['page'] : 1;
		$ymd = date ( "Y-m-d", time () );
		$condi = '';
		if ($_POST) {
			$post = base_Utils::shtmlspecialchars ( $_POST );
			$key = base_Utils::getStr ( $_POST ['key'] );
			$stime = base_Utils::getStr ( $_POST ['stime'] );
			$etime = base_Utils::getStr ( $_POST ['etime'] );
			if ($key) {
				$condi = "order_id ='{$key}' or goods_name like '%{$key}%' or realname like '%{$key}%' or membercardid ='{$key}'";
			}
			if ($stime) {
				$etime = $etime ? $etime : $ymd;
				$condi = $condi ? $condi . " and" : "";
				$condi .= " dateymd between '{$stime}' and '{$etime}'";
			}
		}
		$saleObj = new m_sales ();
		$saleObj->setCount ( true );
		$saleObj->setPage ( $page );
		$saleObj->setLimit ( base_Constant::PAGE_SIZE );
		$rs = $saleObj->select ( $condi, "order_id,sum(price*num) as allprice,dateymd,sum(p_discount+m_discount) as discount,sum(refund_amount) as refund", "group by order_id", "order by sid desc" );
		$this->params ['sales'] = $rs->items;
		$this->params ['key'] = $key;
		$this->params ['stime'] = $stime;
		$this->params ['etime'] = $etime;
		$this->params ['pagebar'] = $this->PageBar ( $rs->totalSize, base_Constant::PAGE_SIZE, $page, $inPath );
		return $this->render ( 'sales/print.html', $this->params );
	}
	/**
	 * 处理退货
	 * @param array $inPath
	 */
	function pagereturn($inPath) {
		$url = $this->getUrlParams ( $inPath );
			    if($url['ac']=='member'){								
					$this->params ['ac'] ='member';
					$this->params ['mid'] =$url['mid'];
					$this->params ['orderid'] =$url['orderid'];
				}
			if ($_POST||$url['ac']=='member') {	
			if($url['ac']=='member'){								
					$order_id =$url['orderid'];
					$this->params ['ac'] ='member';
					$formurl="/member/memberinfo-mid-{$url['mid']}.html";
				}else{
					$order_id =base_Utils::getStr ( $_POST ['order_id'] );
					$formurl='/sales/return';
			}
			$salesObj = new m_sales ();
			$this->params ['order_id'] = $order_id;
			if ($_POST ['ac'] == 'del') {
				$post = base_Utils::shtmlspecialchars ( $_POST );
				$sidArr = ( array ) $_POST ['sid'];
				$numArr = ( array ) $_POST ['num'];
				$returnArr = array ();
				$i = 0;
				if ($sidArr) {
					foreach ( $sidArr as $k => $v ) {
						$rs = $salesObj->selectOne ( "sid={$v} and order_id={$order_id} and refund_type=0", "num,goods_id,goods_name,price,mid" ); //退过款的商品不能够二次退款
						if (! $rs)
							$this->ShowMsg ( "该订单中没有该商品！" );
						$mid = $rs ['mid'];
						if ($numArr [$k] > 0 and $numArr [$k] <= $rs ['num']) {
							$returnArr [$i] ['sid'] = ( int ) $v;
							$returnArr [$i] ['goods_id'] = $rs ['goods_id'];
							$returnArr [$i] ['num'] = ( float ) $numArr [$k];
							$returnArr [$i] ['refund_type'] = 2;
							$returnArr [$i] ['refund_amount'] = sprintf ( "%01.2f", $rs ['price'] * $numArr [$k] );
							if ($numArr [$k] == $rs ['num']) {
								$returnArr [$i] ['refund_type'] = 1;
							}
							$i ++;
						} else {
							$this->ShowMsg ( "{$rs['goods_name']} 退货数量不正确" );
						}
					}
				}
				$purchaseObj = new m_purchase ();
				//退货操作 1修改库存 2 修改商品销售总价 3更新会员卡积分
				foreach ( $returnArr as $v ) {
					if (! $purchaseObj->backStock ( $v ['goods_id'], $v ['num'], $v ['refund_amount'] )) {				
						$this->ShowMsg ( "商品{$v['goods_id']}退款出错" . $purchaseObj->getError () );
					}
					$salesObj->update ( "order_id={$order_id} and goods_id = {$v['goods_id']}", "refund_type={$v['refund_type']},refund_amount={$v['refund_amount']},refund_num={$v['num']},sales_type=1" );
				}
				if ($mid) {
					$memberObj = new m_member ();
					$memberObj->setCredit ( $mid,-$v['refund_amount']*base_Constant::RATIO);
				$m_cacheObj = new m_membercache();
				$m_cacheObj->updatecache($mid,"buyfre=buyfre-1,returnfre=returnfre+1,totalrefund=totalrefund+{$v['refund_amount']},totalspanding=totalspanding-{$v['refund_amount']}");
				}
				$this->ShowMsg ( "退货成功！", $this->createUrl ($formurl), 20, 1 );
			}
			$this->params ['list'] = $salesObj->select ( "order_id='{$order_id}'" )->items;
		}
		return $this->render ( 'sales/return.html', $this->params );
	}
}