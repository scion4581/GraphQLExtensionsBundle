<?php

namespace Youshido\GraphQLExtensionsBundle\Service\PathResolver;


use Youshido\GraphQLExtensionsBundle\Model\PathAwareInterface;

interface PathResolverInterface
{
    public function resolveWebPath(PathAwareInterface $object);

    public function resolveRelativePath(PathAwareInterface $object);

    public function resolveAbsolutePath(PathAwareInterface $object);

}