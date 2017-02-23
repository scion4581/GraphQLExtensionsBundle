<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/22/17 5:01 PM
 */

namespace Youshido\GraphQlExtensionsBundle\Services\Locator\Storage;

class FileSystemStorage implements StorageInterface
{

    public function save($absolutePath, $data)
    {
        $this->preparePath($absolutePath);

        return file_put_contents($absolutePath, $data, FILE_APPEND);
    }

    public function exists($absolutePath)
    {
        return file_exists($absolutePath);
    }

    public function size($absolutePath)
    {
        return filesize($absolutePath);
    }

    public function preparePath($path)
    {
        $directory = dirname($path);

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    }

}