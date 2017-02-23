<?php

namespace Youshido\GraphQlExtensionsBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Youshido\GraphQlExtensionsBundle\DependencyInjection\CompilerPass\GraphQLExtensionsCompilerPass;

/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/21/17 11:21 PM
 */
class GraphQLExtensionsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new GraphQLExtensionsCompilerPass());
    }

}