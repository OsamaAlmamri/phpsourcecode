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

namespace Common\Domain\Service\Attachment;

use Common\Infra\Exception\BusinessException;
use Leevel\Filesystem\Proxy\Filesystem;
use Leevel\Option\Proxy\Option;
use Leevel\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * 文件上传.
 */
class Upload
{
    public function handle(array $input): array
    {
        $this->closeDebug();

        return $this->save($input['file']);
    }

    /**
     * 关闭调试.
     */
    private function closeDebug(): void
    {
        Option::set('debug', false);
    }

    /**
     * 保存文件.
     *
     * @throws \Common\Infra\Exception\BusinessException
     */
    private function save(UploadedFile $file): array
    {
        if (!$file->isValid()) {
            throw new BusinessException($file->getErrorMessage());
        }

        $savePath = $this->getSavePath($file);
        $this->saveFile($file->getPathname(), $savePath);

        return [$this->savePathForUrl($savePath)];
    }

    /**
     * 获取文件保存路径.
     */
    private function getSavePath(UploadedFile $file): string
    {
        return date('Y-m-d').'/'.date('H').'/'.
            md5(uniqid().Str::randAlpha(32)).'.'.$file->getClientOriginalExtension();
    }

    /**
     * 保存文件到服务器.
     *
     * @throws \Common\Infra\Exception\BusinessException
     */
    private function saveFile(string $sourcePath, string $savePath): void
    {
        if (false === Filesystem::put($savePath, file_get_contents($sourcePath))) {
            throw new BusinessException(__('文件上传失败'));
        }
    }

    /**
     * 获取文件上传路径 URL.
     */
    private function savePathForUrl(string $savePath): string
    {
        return Option::get('storage').'/'.$savePath;
    }
}
