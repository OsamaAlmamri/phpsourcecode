<?php
/**
 * DBERP 进销存系统
 *
 * ==========================================================================
 * @link      http://www.dberp.net/
 * @copyright 北京珑大钜商科技有限公司，并保留所有权利。
 * @license   http://www.dberp.net/license.html License
 * ==========================================================================
 *
 * @author    静静的风 <baron@loongdom.cn>
 *
 */

namespace Purchase\Validator;

use Purchase\Entity\Order;
use Zend\Validator\AbstractValidator;

class OrderCodeValidator extends AbstractValidator
{
    const NOT_SCALAR        = 'notScalar';
    const ORDER_CODE_EXISTS = 'orderCodeExists';

    protected $messageTemplates = [
        self::NOT_SCALAR        => "这不是一个标准输入值",
        self::ORDER_CODE_EXISTS => "该采购订单号已经存在"
    ];

    private $entityManager;
    private $order;

    public function __construct($options = null)
    {
        if(is_array($options)) {
            if(isset($options['entityManager']))    $this->entityManager= $options['entityManager'];
            if(isset($options['order']))            $this->order        = $options['order'];
        }

        parent::__construct($options);
    }

    public function isValid($value)
    {
        if(!is_scalar($value)) {
            $this->error(self::NOT_SCALAR);
            return false;
        }

        $orderInfo = $this->entityManager->getRepository(Order::class)->findOneByPOrderSn($value);

        if($this->order == null) {
            $isValid = ($orderInfo==null);
        } else {
            if($this->order->getPOrderSn() != $value && $orderInfo != null)
                $isValid = false;
            else
                $isValid = true;
        }

        if(!$isValid) $this->error(self::ORDER_CODE_EXISTS);

        return $isValid;
    }
}