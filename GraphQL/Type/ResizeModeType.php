<?php

namespace Youshido\GraphQLExtensionsBundle\GraphQL\Type;

use Youshido\GraphQL\Type\Enum\AbstractEnumType;

/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/26/17 5:50 PM
 */
class ResizeModeType extends AbstractEnumType
{
    const OUTBOUND = 'outbound';
    const INSET    = 'inset';

    /**
     * @return array
     */
    public function getValues()
    {
        return [
            [
                'name'  => 'INSET',
                'value' => self::INSET
            ],
            [
                'name'  => 'OUTBOUND',
                'value' => self::OUTBOUND
            ],
        ];
    }
}