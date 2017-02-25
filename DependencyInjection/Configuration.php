<?php
namespace Youshido\GraphQLExtensionsBundle\DependencyInjection;
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
                ->scalarNode('web_root')->cannotBeEmpty()->defaultValue("%kernel.root_dir%/../web")->end()
                ->scalarNode('path_prefix')->cannotBeEmpty()->defaultValue('uploads')->end()
                ->enumNode('storage')
                    ->values(['s3', 'filesystem'])
                    ->cannotBeEmpty()
                    ->defaultValue('filesystem')
                ->end()
                ->enumNode('platform')
                    ->values(['odm', 'orm'])
                    ->cannotBeEmpty()
                    ->defaultValue('orm')
                ->end()
                ->arrayNode('files')
                    ->children()
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }


}