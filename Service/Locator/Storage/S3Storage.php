<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/22/17 5:01 PM
 */

namespace Youshido\GraphQLExtensionsBundle\Service\Locator\Storage;

use Aws\S3\S3Client;

class S3Storage implements StorageInterface
{

    /** @var S3Client */
    private $client;

    /** @var string */
    private $bucketName;

    public function setClient(S3Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getBucketName()
    {
        return $this->bucketName;
    }

    /**
     * @param string $bucketName
     */
    public function setBucket($bucketName)
    {
        $this->bucketName = $bucketName;
    }

    public function save($absolutePath, $data)
    {
        return $this->client->putObject([
            'Bucket' => $this->bucketName,
            'Key'    => $absolutePath,
            'Body'   => $data,
            'ACL'    => 'public-read',
        ]);
    }

    public function exists($absolutePath)
    {
        return file_exists($absolutePath);
    }

    public function size($absolutePath)
    {
        return 1;
//        return $this->client->in($absolutePath);
    }

    public function preparePath($path)
    {
    }

}