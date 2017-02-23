<?php

namespace Youshido\GraphQlExtensionsBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Youshido\GraphQlExtensionsBundle\Services\Locator\Storage\FileSystemStorage;

/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/22/17 9:03 PM
 */
class GraphQLExtensionsCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $storageDefinition  = new Definition();

        switch ($container->getParameter('graphql_extensions.config.storage')) {
            case 'filesystem':
                $storageDefinition->setClass(FileSystemStorage::class);
        }

        $container->setParameter('graphql_extensions.storage', $storageDefinition);
    }


}