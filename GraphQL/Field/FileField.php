<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/26/17 12:00 AM
 */

namespace Youshido\GraphQLExtensionsBundle\GraphQL\Field;


use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQLExtension\Type\FileType;

class FileField extends AbstractField
{

    /**
     * FileField constructor.
     * @param string $fieldName
     */
    public function __construct($fieldName)
    {
        parent::__construct([
            'type' => $this->getType(),
            'name' => $fieldName
        ]);
    }

    public function getType()
    {
        return new FileType();
    }


}