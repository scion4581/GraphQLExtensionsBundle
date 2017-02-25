<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/22/17 9:10 PM
 */

namespace Youshido\GraphQLExtensionsBundle\Service\Locator;

interface LocatedObjectInterface
{
    /**
     * @return string
     */
    public function getPath();

    /**
     * @return int
     */
    public function getSize();

    public function getExtension();
}