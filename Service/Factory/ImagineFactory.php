<?php

namespace Youshido\GraphQLExtensionsBundle\Service\Factory;

use Imagine\Gd\Imagine as GDImagine;
use Imagine\Gmagick\Imagine as GmagickImagine;
use Imagine\Image\AbstractImagine;
use Imagine\Imagick\Imagine as ImagickImagine;

class ImagineFactory
{

    const DRIVER_GD      = 'gd';
    const DRIVER_IMAGICK = 'imagick';
    const DRIVER_GMAGICK = 'gmagick';

    /**
     * @param string $driver
     * @return AbstractImagine
     */
    public static function createImagine($driver = self::DRIVER_GD)
    {
        switch ($driver) {
            case self::DRIVER_GD:
                return new GDImagine();

            case self::DRIVER_IMAGICK:
                return new ImagickImagine();

            case self::DRIVER_GMAGICK:
                return new GmagickImagine();

            default:
                throw new \InvalidArgumentException(sprintf('Driver "%s" not supported', $driver));
        }
    }

}