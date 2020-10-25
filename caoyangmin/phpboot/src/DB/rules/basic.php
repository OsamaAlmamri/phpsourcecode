<?php
namespace PhpBoot\DB\rules\basic;

use PhpBoot\DB\Context;
use PhpBoot\DB\impls\ExecImpl;
use PhpBoot\DB\impls\LimitImpl;
use PhpBoot\DB\impls\OrderByImpl;
use PhpBoot\DB\impls\ExecResult;
use PhpBoot\DB\impls\WhereImpl;
require_once dirname(__DIR__).'/impls.php';

class BasicRule
{
    public function __construct(Context $context){
        $this->context = $context;
    }


    /**
     * @var Context
     */
    public $context;
}

class ExecRule extends BasicRule
{
    /**
     * Execute sql
     * @return ExecResult
     */
    public function exec() {
        return ExecImpl::exec($this->context);
    }
}

class LimitRule extends ExecRule
{
    /**
     * limit(1) => "LIMIT 1"
     * @param int $size
     * @return \PhpBoot\DB\rules\basic\ExecRule
     */
    public function limit($size) {
        LimitImpl::limit($this->context, $size);
        return new ExecRule($this->context);
    }
}

class OrderByRule extends LimitRule
{
    public function __construct($context){
        parent::__construct($context);
        $this->impl = new OrderByImpl();
    }
//    /**
//     * orderByArgs(['column0', 'column1'=>Sql::ORDER_BY_ASC]) => "ORDER BY column0,column1 ASC"
//     * @param array $orders
//     * @return \PhpBoot\DB\rules\basic\LimitRule
//     */
//    public function orderByArgs($orders) {
//        $this->impl->orderByArgs($this->context, $orders);
//        return new LimitRule($this->context);
//    }
    /**
     * 
     * orderBy('column') => "ORDER BY column"
     * orderBy('column', Sql::ORDER_BY_ASC) => "ORDER BY column ASC"
     * orderBy('column0')->orderBy('column1') => "ORDER BY column0, column1"
     * orderBy(['column0', 'column1'=>Sql::ORDER_BY_ASC]) => "ORDER BY column0,column1 ASC"
     * @param string $column
     * @param string $order Sql::ORDER_BY_ASC or Sql::ORDER_BY_DESC
     * 
     * @return \PhpBoot\DB\rules\basic\LimitRule
     */
    public function orderBy($column, $order=null) {
        $this->impl->orderBy($this->context, $column, $order);
        return new LimitRule($this->context);
    }
    private $impl;
}

class WhereRule extends OrderByRule
{
    /**
     * 
     * where('a=?', 1) => "WHERE a=1"
     * where('a=?', Sql::raw('now()')) => "WHERE a=now()"
     * where('a IN (?)',  [1, 2]) => "WHERE a IN (1,2)"
     *
     * where([
     *      'a'=>1,
     *      'b'=>['IN'=>[1,2]]
     *      'c'=>['BETWEEN'=>[1,2]]
     *      'd'=>['<>'=>1]
     *      ])
     *      =>
     *      "WHERE a=1 AND b IN(1,2) AND c BETWEEN 1 AND 2 AND d<>1"
     *
     * @param string|array $expr
     * @param mixed $_
     * @return \PhpBoot\DB\rules\basic\OrderByRule
     */
    public function where($expr, $_= null) {
        WhereImpl::where($this->context, $expr, array_slice(func_get_args(), 1));
        return new OrderByRule($this->context);
    }
}