<?php

namespace Youshido\GraphQlExtensionsBundle\Services\PathResolver;


use Youshido\GraphQlExtensionsBundle\Model\PathAwareInterface;

interface PathResolverInterface
{
    public function resolveWebPath(PathAwareInterface $object);

    public function resolveRelativePath(PathAwareInterface $object);

    public function resolveAbsolutePath(PathAwareInterface $object);

}