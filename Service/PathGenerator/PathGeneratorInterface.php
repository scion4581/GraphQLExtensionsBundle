<?php

namespace Youshido\GraphQlExtensionsBundle\Services\PathGenerator;


interface PathGeneratorInterface
{

    /**
     * @param $extension
     * @return string
     */
    public function generatePath($extension);

}