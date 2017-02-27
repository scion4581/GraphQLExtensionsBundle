<?php

namespace Youshido\GraphQLExtensionsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Youshido\GraphQLExtensionsBundle\Model\FileModelInterface;

/**
 * Class File
 *
 * @ODM\EmbeddedDocument()
 * @ODM\HasLifecycleCallbacks()
 */
class EmbeddedFile implements FileModelInterface
{
    /**
     * @ODM\Id()
     */
    private $id;

    /**
     * @ODM\Field(type="string")
     */
    private $referenceId;

    /**
     * @ODM\Field(type="string")
     */
    private $title;

    /**
     * @ODM\Field(type="int")
     */
    private $size;

    /**
     * @ODM\Field(type="string")
     */
    private $path;

    /**
     * @ODM\Field(type="date")
     */
    private $uploadedAt;


    public function __construct(File $file = null)
    {
        if ($file) {
            $this->referenceId = $file->getId();
            $this->title       = $file->getTitle();
            $this->size        = $file->getSize();
            $this->path        = $file->getPath();
        }
    }


    /**
     * @ODM\PrePersist()
     */
    public function prePersist()
    {
        $this->uploadedAt = new \DateTime();
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
    public function getReferenceId()
    {
        return $this->referenceId;
    }

    /**
     * @param mixed $referenceId
     * @return EmbeddedFile
     */
    public function setReferenceId($referenceId)
    {
        $this->referenceId = $referenceId;
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

    /**
     * @return mixed
     */
    public function getUploadedAt()
    {
        return $this->uploadedAt;
    }

    /**
     * @param mixed $uploadedAt
     * @return EmbeddedFile
     */
    public function setUploadedAt($uploadedAt)
    {
        $this->uploadedAt = $uploadedAt;
        return $this;
    }



}