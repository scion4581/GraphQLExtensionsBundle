<?php

namespace Youshido\GraphQLExtensionsBundle\Service\PathResolver;


use Symfony\Component\Routing\RouterInterface;
use Youshido\GraphQLExtensionsBundle\Model\PathAwareInterface;

class LocalPathResolver implements PathResolverInterface
{
    /** @var  string */
    private $scheme;

    /** @var  string */
    private $host;

    /** @var  string */
    private $prefix;

    /** @var string */
    private $webRoot;

    public function __construct(RouterInterface $router, $webRoot, $prefix, $host = null, $scheme = null)
    {
        $this->prefix  = $prefix;
        $this->webRoot = $webRoot;

        $this->host   = $host;
        $this->scheme = $scheme;

        if (!$host) {
            $this->host = $router->getContext()->getHost();
        }

        if (!$scheme) {
            $this->scheme = $router->getContext()->getScheme();
        }
    }

    public function resolveWebPath(PathAwareInterface $object)
    {
        return sprintf('%s://%s%s', $this->scheme, $this->host, $this->resolveRelativePath($object));
    }

    public function resolveAbsolutePath(PathAwareInterface $object)
    {
        return sprintf('%s%s', $this->webRoot, $this->resolveRelativePath($object));
    }

    public function resolveRelativePath(PathAwareInterface $object)
    {
        return sprintf('/%s/%s', $this->prefix, $object->getPath());
    }

}