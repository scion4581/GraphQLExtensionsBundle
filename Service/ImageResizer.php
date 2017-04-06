<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/26/17 7:06 PM
 */

namespace Youshido\GraphQLExtensionsBundle\Service;


use Imagine\Filter\Basic\Crop;
use Imagine\Filter\Basic\Resize;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
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

        /** @var ImageInterface $image */
        $image = $imagine->open($this->pathResolver->resolveWebPath($object));
        $this->storage->preparePath($absoluteTargetPath);

        $box = new Box($config['width'], $config['height']);
        // determine scale
        $size = $this->fillBox($image->getSize(), $box);
        // define filters
        $resize = new Resize($size);
        if (empty($config['origin'])) {
            $config['origin'] = [0.5, 0.5];
        }
        $origin = new Point(
            floor(($size->getWidth() - $box->getWidth()) * $config['origin'][0]),
            floor(($size->getHeight() - $box->getHeight()) * $config['origin'][0])
        );
        $crop = new Crop($origin, $box);
        $image = $resize->apply($image);
        $image = $crop->apply($image);

        $this->storage->save($absoluteTargetPath, $image->__toString());
    }

    /**
     * @param BoxInterface $sourceBox
     * @param BoxInterface $targetBox
     * @return BoxInterface
     */
    private function fillBox(BoxInterface $sourceBox, BoxInterface $targetBox) {
        $sourceAspect = $sourceBox->getWidth() / $sourceBox->getHeight();
        $targetAspect = $targetBox->getWidth() / $targetBox->getHeight();

        if ($sourceAspect > $targetAspect) {
            return $sourceBox->heighten($targetBox->getHeight());
        }
        return $sourceBox->widen($targetBox->getWidth());
    }

    public function getPathResolver()
    {
        return $this->pathResolver;
    }
}