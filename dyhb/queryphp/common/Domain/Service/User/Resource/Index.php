<?php

declare(strict_types=1);

/*
 * This file is part of the your app package.
 *
 * The PHP Application For Code Poem For You.
 * (c) 2018-2099 http://yourdomian.com All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Common\Domain\Service\User\Resource;

use Closure;
use Common\Domain\Entity\User\Resource;
use Common\Domain\Service\Support\Read;
use Common\Infra\Support\WorkflowService;
use Leevel\Database\Ddd\Select;
use Leevel\Database\Ddd\UnitOfWork;

/**
 * 资源列表.
 */
class Index
{
    use Read;
    use WorkflowService;

    private UnitOfWork $w;

    private array $workflow = [
        'filterSearchInput',
        'allowedInput',
        'defaultInput',
        'filterInput',
    ];

    private array $allowedInput = [
        'key',
        'status',
        'page',
        'size',
        'column',
        'order_by',
    ];

    public function __construct(UnitOfWork $w)
    {
        $this->w = $w;
    }

    public function handle(array $input): array
    {
        return $this->workflow($input);
    }

    /**
     * 准备资源数据.
     */
    private function prepareItem(Resource $resource): array
    {
        return $resource->toArray();
    }

    /**
     * 查询条件.
     */
    private function condition(array $input): Closure
    {
        return function (Select $select) use ($input) {
            $this->spec($select, $input);
        };
    }

    /**
     * 过滤输入数据.
     */
    private function main(array &$input): array
    {
        $repository = $this->w->repository(Resource::class);

        return $this->findPage($input, $repository);
    }

    /**
     * 默认数据填充.
     */
    private function defaultInput(array &$input): void
    {
        $defaultInput = [
            'column'   => 'id,name,num,status,create_at',
            'order_by' => 'id DESC',
            'page'     => 1,
            'size'     => 10,
        ];
        $this->fillDefaultInput($input, $defaultInput);

        $input['key_column'] = ['id', 'name', 'num'];
    }

    /**
     * 过滤数据规则.
     */
    private function filterInputRules(): array
    {
        return [
            'status'   => ['intval'],
            'page'     => ['intval'],
            'size'     => ['intval'],
        ];
    }
}
