<?php

namespace Youshido\GraphQLExtensionsBundle\Service\Locator\Storage;


/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/22/17 3:51 PM
 */
interface StorageInterface
{
    /**
     * @param $absolutePath string
     * @param $data         string
     *
     * @return bool
     */
    public function save($absolutePath, $data);

    /**
     * @param $absolutePath
     *
     * @return bool
     */
    public function exists($absolutePath);

    /**
     * @param $absolutePath
     *
     * @return int
     */
    public function size($absolutePath);

    /**
     * @param $path
     *
     * @return null
     */
    public function preparePath($path);


}