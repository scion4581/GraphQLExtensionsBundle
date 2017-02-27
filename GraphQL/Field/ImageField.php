<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/26/17 4:33 PM
 */

namespace Youshido\GraphQLExtensionsBundle\GraphQL\Field;


use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQLExtension\Type\FileType;

class ImageField extends AbstractField
{

    /** @var string */
    protected $fieldName;

    /**
     * ImageField constructor.
     * @param string $fieldName
     */
    public function __construct($fieldName = 'image') {
        $this->fieldName = $fieldName;
        parent::__construct([
            'name' => $fieldName,
            'type' => $this->getType(),
        ]);
    }

    public function getType()
    {
        return new FileType();
    }


}