<?php

namespace Youshido\GraphQlExtensionsBundle\Services\PathGenerator;


class DatePathGenerator implements PathGeneratorInterface
{

    public function generatePath($extension)
    {
        $now = new \DateTime();

        return sprintf('%s/%s/%s/%s.%s', $now->format('Y'), $now->format('m'), $now->format('d'), uniqid(), $extension);
    }
}