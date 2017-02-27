<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/26/17 3:48 PM
 */

namespace Youshido\GraphQLExtensionsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Youshido\GraphQLExtensionsBundle\Model\FileModelInterface;

/**
 * Class Image
 * @package Youshido\GraphQLExtensionsBundle\Document
 * @ODM\Document(collection="images")
 */
class Image extends File
{

}