<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/22/17 7:47 PM
 */

namespace Youshido\GraphQlExtensionsBundle\Services\Locator;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Youshido\GraphQlExtensionsBundle\Service\Locator\LocatedObject;
use Youshido\GraphQlExtensionsBundle\Services\Locator\Storage\StorageInterface;
use Youshido\GraphQlExtensionsBundle\Services\PathGenerator\PathGeneratorInterface;
use Youshido\GraphQlExtensionsBundle\Services\PathResolver\PathResolverInterface;

class Locator
{
    protected $pathGenerator;
    protected $pathResolver;
    protected $storage;

    public function __construct(PathGeneratorInterface $pathGenerator, PathResolverInterface $pathResolver, StorageInterface $storage)
    {
        $this->pathGenerator = $pathGenerator;
        $this->pathResolver  = $pathResolver;
        $this->storage       = $storage;
    }

    public function saveFromUploadedFile(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension() ?: $file->guessExtension();

        return $this->doUpload($extension, $this->getFileContent($file->getPathname()));
    }

    public function saveFromUrl($url)
    {
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        if (strpos($extension, '?') !== false) {
            $extension = substr($extension, 0, strpos($extension, '?'));
        }

        return $this->doUpload($extension, $this->getFileContent($url));
    }

    private function doUpload($extension, $data)
    {
        $path         = $this->pathGenerator->generatePath($extension);
        $absolutePath = $this->pathResolver->resolveAbsolutePath(new LocatedObject($path));

        $this->storage->save($absolutePath, $data);
        $size = $this->storage->size($absolutePath);

        return new LocatedObject($path, $size, $extension);
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getFileContent($path)
    {
        $contextOptions = [
            "ssl" => [
                "verify_peer"      => false,
                "verify_peer_name" => false,
            ],
        ];

        return file_get_contents($path, null, stream_context_create($contextOptions));
    }
}