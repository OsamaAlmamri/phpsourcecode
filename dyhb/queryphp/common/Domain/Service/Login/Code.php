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

namespace Common\Domain\Service\Login;

use Admin\Infra\Code as Codes;
use Leevel;
use Leevel\Seccode\Seccode;

/**
 * 验证码生成.
 */
class Code
{
    private Codes $code;

    public function __construct(Codes $code)
    {
        $this->code = $code;
    }

    public function handle(array $input): string
    {
        // Mac 自带 PHP 有问题
        if (!function_exists('imagettftext')) {
            return file_get_contents(Leevel::commonPath('ui/seccode/code.png')) ?: '';
        }

        $seccode = new Seccode([
            'font_path' => Leevel::commonPath('ui/seccode/font'),
            'width'     => 120,
            'height'    => 36,
        ]);

        ob_start();
        $seccode->display(4);
        if (!empty($input['id'])) {
            $this->code->set($input['id'], (string) $seccode->getCode());
        }
        $content = ob_get_contents() ?: '';
        ob_end_clean();

        return $content;
    }
}
