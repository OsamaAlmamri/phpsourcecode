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

namespace App\App\Controller\Restful;

use Leevel\Router\IRouter;

/**
 * store.
 *
 * @codeCoverageIgnore
 */
class Store
{
    public function handle(): string
    {
        return 'hello for restful '.IRouter::RESTFUL_STORE;
    }
}
