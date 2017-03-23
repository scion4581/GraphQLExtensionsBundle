<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/25/17 10:41 AM
 */

namespace Youshido\GraphQLExtensionsBundle\Service;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Youshido\GraphQLExtensionsBundle\Model\FileModelInterface;
use Youshido\GraphQLExtensionsBundle\Service\Locator\Locator;

class FileProvider
{

    /** @var RequestStack */
    protected $requestStack;
    /** @var ObjectManager */
    protected $om;
    /** @var  Locator */
    protected $locator;

    protected $modelClass;

    public function __construct(RequestStack $requestStack, Locator $locator, ObjectManager $objectManager)
    {
        $this->requestStack = $requestStack;
        $this->locator = $locator;
        $this->om = $objectManager;
    }

    public function setObjectManager(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function setModelClass($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function processFileFromRequest($fieldName)
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request->files->has($fieldName) || !$request->files->get($fieldName)) {
            throw new \InvalidArgumentException(sprintf('Request hasn\'t file with field "%s"', $fieldName));
        }

        $uploadedFile = $request->files->get($fieldName);
        return $this->processUploadedFile($uploadedFile);
    }

    public function processUploadedFile($uploadedFile)
    {
        $locatedFile  = $this->locator->saveFromUploadedFile($uploadedFile);
        /** @var FileModelInterface $object */
        $object = new $this->modelClass();
        $object->setTitle($locatedFile->getFilename());
        $object->setSize($locatedFile->getSize());
        $object->setPath($locatedFile->getPath());
        $this->om->persist($object);
        $this->om->flush();
        return $object;
    }

}