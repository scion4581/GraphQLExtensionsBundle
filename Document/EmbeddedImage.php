<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/26/17 4:33 PM
 */

namespace Youshido\GraphQLExtensionsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class EmbeddedImage
 * @package Youshido\GraphQLExtensionsBundle\Document
 * @ODM\EmbeddedDocument()
 */
class EmbeddedImage extends EmbeddedFile
{

}