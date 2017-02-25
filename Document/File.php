<?php

namespace Youshido\GraphQLExtensionsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Youshido\GraphQLExtensionsBundle\Model\FileModelInterface;
use Youshido\GraphQLExtensionsBundle\Model\PathAwareInterface;

/**
 * Class File
 *
 * @MongoDB\Document(collection="files")
 * @MongoDB\HasLifecycleCallbacks()
 */
class File implements FileModelInterface
{
    /**
     * @MongoDB\Id()
     */
    private $id;

    /**
     * @MongoDB\Field(type="string")
     */
    private $title;

    /**
     * @MongoDB\Field(type="int")
     */
    private $size;

    /**
     * @MongoDB\Field(type="string")
     */
    private $path;

    /**
     * @MongoDB\Field(type="date")
     */
    private $createdAt;

    /**
     * @MongoDB\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

}