<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/26/17 5:19 PM
 */

namespace Youshido\GraphQLExtensionsBundle\GraphQL\Field;


use Symfony\Component\PropertyAccess\PropertyAccessor;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQLExtensionsBundle\GraphQL\Type\ResizeModeType;

class ResizableImageField extends ImageField
{
    public function build(FieldConfig $config)
    {
        parent::build($config);
        $config->addArguments([
            'width'  => [
                'type'    => new IntType(),
                'defaultValue' => 0
            ],
            'height' => [
                'type'    => new IntType(),
                'defaultValue' => 0
            ],
            'mode'   => [
                'type'    => new ResizeModeType(),
                'defaultValue' => ResizeModeType::OUTBOUND
            ]
        ]);
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {
        if ($value && is_object($value)) {
            $pa = new PropertyAccessor();
            $image = $pa->getValue($value, $this->fieldName);
            if (!empty($args['width']) || !empty($args['height'])) {
                $url = $info->getContainer()->get('graphql_extensions.image_resizer')->getPathResolver()->resolveWebResizablePath(
                    $args['width'], $image
                );
            } else {
                $url = $info->getContainer()->get('graphql_extensions.path_resolver')->resolveWebPath($image);
            }

            return [
                'id'    => $image->getId(),
                'url'   => $url,
                'image' => $image
            ];
        }

        return null;
    }




}