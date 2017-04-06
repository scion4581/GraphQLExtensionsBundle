<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/21/17 11:33 PM
 */

namespace Youshido\GraphQLExtensionsBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class GraphQLExtensionsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

//        $container->setParameter('graphql_extensions.files', $config['files']);
        $this->setContainerParam($container, 's3_bucket', $config['s3_bucket']);
        $this->setContainerParam($container, 'web_root', $config['web_root']);
        $this->setContainerParam($container, 'path_prefix', $config['path_prefix']);
        $this->setContainerParam($container, 'platform', $config['platform']);
        $this->setContainerParam($container, 'storage', $config['storage']);
        $this->setContainerParam($container, 'image_driver', $config['image_driver']);
        $this->setContainerParam($container, 'host', null);
        $this->setContainerParam($container, 'scheme', null);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    private function setContainerParam(ContainerBuilder $container, $parameter, $value)
    {
        $container->setParameter(sprintf('graphql_extensions.config.%s', $parameter), $value);
    }

    public function getAlias()
    {
        return "graphql_extensions";
    }


}