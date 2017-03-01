<?php

namespace Youshido\GraphQLExtensionsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Youshido\GraphQLExtensionsBundle\Service\Locator\LocatedObject;

class ResizeImageController extends Controller
{
    /**
     * @Route("/media/cache/{mode}/{width}x{height}/{path}", name="images.resize", requirements={"path"=".+"})
     *
     * @param $mode
     * @param $width
     * @param $height
     * @param $path
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function resizeAction($mode, $width, $height, $path)
    {
        try {
            $resizer = $this->get('graphql_extensions.image_resizer');

            $object = new LocatedObject($path);

            $config = [
                'width'  => $width,
                'height' => $height,
                'mode'   => $mode
            ];
            $resizer->resize($object, $config);

            return new RedirectResponse($resizer->getPathResolver()->resolveWebResizablePath($config, $object), 301);
        } catch (\Exception $e) {
            throw $this->createNotFoundException();
        }
    }
}
