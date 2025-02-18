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

namespace App\App\Controller\Swagger;

/**
 * 文档汇总.
 *
 * @author Name Your <your@mail.com>
 *
 * @codeCoverageIgnore
 */
class Apis
{
    /**
     * 响应.
     */
    public function handle(): array
    {
        return [
            [
                'name' => 'User Api',
                'url'  => '/swagger/user',
            ],
            [
                'name' => 'QueryPHP API',
                'url'  => '/swagger',
            ],
        ];
    }
}
