<?php

namespace Youshido\GraphQLExtensionsBundle\GraphQL\Field;

use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQLExtension\Type\FileType;
use Youshido\GraphQLExtensionsBundle\Model\FileModelInterface;

/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/25/17 10:25 AM
 */
class UploadFileField extends AbstractField
{
    public function build(FieldConfig $config)
    {
        $config->addArguments([
            'field' => [
                'type'         => new StringType(),
                'defaultValue' => 'file'
            ]
        ]);
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {
        /** @var FileModelInterface $object */
        $object = $info->getContainer()->get('graphql_extensions.file_provider')->processFileFromRequest($args['field']);
        return [
            'id'   => $object->getId(),
            'url'  => $info->getContainer()->get('graphql_extensions.path_resolver')->resolveWebPath($object),
            'file' => $object,
        ];
    }

    public function getType()
    {
        return new FileType();
    }


}
