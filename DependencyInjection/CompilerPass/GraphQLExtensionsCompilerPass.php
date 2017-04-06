<?php

namespace Youshido\GraphQLExtensionsBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Youshido\GraphQLExtensionsBundle\Service\Locator\Storage\FileSystemStorage;
use Youshido\GraphQLExtensionsBundle\Service\Locator\Storage\S3Storage;
use Youshido\GraphQLExtensionsBundle\Service\PathResolver\LocalPathResolver;
use Youshido\GraphQLExtensionsBundle\Service\PathResolver\S3PathResolver;

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
        $resolverDefinition = $container->getDefinition('graphql_extensions.path_resolver');
        switch ($container->getParameter('graphql_extensions.config.storage')) {
            case 'local':
                $storageDefinition->setClass(FileSystemStorage::class);
                $resolverDefinition->setClass(LocalPathResolver::class);
                break;
            case 's3':
                $storageDefinition->setClass(S3Storage::class);
                $bucket = $container->getParameter('graphql_extensions.config.s3_bucket');
                $storageDefinition->setMethodCalls([
                    ['setClient', [new Reference("aws.s3")]],
                    ['setBucket', [$bucket]]
                ]);
                $resolverDefinition->setClass(S3PathResolver::class);
                $resolverDefinition->setArguments([
                    new Reference("router"),
                    "", "", "s3.amazonaws.com/" . $bucket, "https"
                ]);
                break;
        }
        $container->setDefinition('graphql_extensions.storage', $storageDefinition);
        $models   = [];
        $platform = $container->getParameter('graphql_extensions.config.platform');
        switch ($platform) {
            case 'orm':
                $container->setAlias('graphql_extensions.om', 'doctrine.orm.entity_manager');
                $models['file'] = 'Youshido\GraphQLExtensionsBundle\Entity\File';
                break;

            case 'odm':
                $container->setAlias('graphql_extensions.om', 'doctrine_mongodb.odm.document_manager');
                $models['file'] = 'Youshido\GraphQLExtensionsBundle\Document\File';
                break;
        }
        $container->setParameter('graphql_extensions.models', $models);
        $container->getDefinition('graphql_extensions.file_provider')->addMethodCall('setModelClass', [$models['file']]);
    }


}