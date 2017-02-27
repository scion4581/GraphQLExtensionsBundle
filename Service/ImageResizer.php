<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/26/17 7:06 PM
 */

namespace Youshido\GraphQLExtensionsBundle\Service;


use Imagine\Image\Box;
use Youshido\GraphQLExtensionsBundle\Model\PathAwareInterface;
use Youshido\GraphQLExtensionsBundle\Service\Locator\Storage\StorageInterface;
use Youshido\GraphQLExtensionsBundle\Service\PathResolver\PathResolverInterface;
use Youshido\GraphQLExtensionsBundle\Service\Factory\ImagineFactory;

class ImageResizer
{
    /** @var PathResolverInterface */
    private $pathResolver;

    /** @var StorageInterface */
    private $storage;

    private $driver = ImagineFactory::DRIVER_GD;

    public function __construct(StorageInterface $storage, PathResolverInterface $pathResolver, $driver)
    {
        $this->storage      = $storage;
        $this->pathResolver = $pathResolver;
        $this->driver       = $driver;
    }

    public function resize(PathAwareInterface $object, $config)
    {
        $absoluteTargetPath = $this->pathResolver->resolveAbsoluteResizablePath($config, $object);

        if ($this->storage->exists($absoluteTargetPath)) {
            return;
        }

        $imagine = ImagineFactory::createImagine($this->driver);

        $this->storage->preparePath($absoluteTargetPath);

        $imagine
            ->open($this->pathResolver->resolveAbsolutePath($object))
            ->thumbnail(new Box($config['width'], $config['height']), $config['mode'])
            ->save($absoluteTargetPath);
    }

    public function getPathResolver()
    {
        return $this->pathResolver;
    }
}