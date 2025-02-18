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

namespace Common\Domain\Service\User\User;

use Common\Domain\Entity\User\User;
use Leevel\Database\Ddd\UnitOfWork;

/**
 * 用户权限查询.
 */
class UserPermission
{
    private UnitOfWork $w;

    public function __construct(UnitOfWork $w)
    {
        $this->w = $w;
    }

    public function handle(array $input): array
    {
        $user = $this->findUser((int) $input['user_id']);
        $data = $this->parsePermission($user);

        return $this->normalizePermission($data);
    }

    /**
     * 格式化权限数据.
     */
    private function normalizePermission(array $data): array
    {
        $permission = ['static' => [], 'dynamic' => []];
        foreach ($data as $v) {
            if ('*' !== $v && false !== strpos($v, '*')) {
                $permission['dynamic'][] = $v;
            } else {
                $permission['static'][] = $v;
            }
        }

        return $permission;
    }

    /**
     * 查询权限数据.
     */
    private function parsePermission(User $user): array
    {
        $data = [];
        if (\count($roles = $user->role) > 0) {
            foreach ($roles as $r) {
                if (\count($permissions = $r->permission) > 0) {
                    foreach ($permissions as $p) {
                        if (\count($resources = $p->resource) > 0) {
                            $resourceData = array_unique(array_column($resources->toArray(), 'num'));
                            $data = array_merge($data, $resourceData);
                        }
                    }
                }
            }
        }

        return $data;
    }

    /**
     * 查找用户实体.
     */
    private function findUser(int $id): User
    {
        return $this->w
            ->repository(User::class)
            ->eager(['role.permission.resource'])
            ->where('status', 1)
            ->findOrFail($id);
    }
}
