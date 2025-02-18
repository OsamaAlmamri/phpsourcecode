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

namespace Tests\Example;

use Tests\TestCase;

/**
 * 继承框架基础示例.
 */
class ExampleTest extends TestCase
{
    public function testBaseUse(): void
    {
        $this->assertSame('QueryPHP', 'QueryPHP');
    }
}
