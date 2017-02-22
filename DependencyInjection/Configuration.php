<?php
namespace Youshido\GraphQlExtensionsBundle\DependencyInjection;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/21/17 11:28 PM
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('graphql_extensions');

        $rootNode
            ->children()
                ->arrayNode('files')
                    ->children()
                        ->enumNode('storage')
                        ->values(['s3', 'filesystem'])
                        ->defaultValue('filesystem')
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }


}