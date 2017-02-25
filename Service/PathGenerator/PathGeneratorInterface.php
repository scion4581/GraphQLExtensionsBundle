<?php

namespace Youshido\GraphQLExtensionsBundle\Service\PathGenerator;


interface PathGeneratorInterface
{

    /**
     * @param $extension
     * @return string
     */
    public function generatePath($extension);

}