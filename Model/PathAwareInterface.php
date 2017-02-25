<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/22/17 6:38 PM
 */

namespace Youshido\GraphQLExtensionsBundle\Model;


interface PathAwareInterface
{

    /**
     * @return string
     */
    public function getPath();

}