<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/21/17 11:42 PM
 */

namespace Youshido\GraphQLExtensionsBundle\Resolver;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Youshido\GraphQLExtensionsBundle\Service\ErrorCode;

class AbstractResolver
{

    /** @var  TokenStorageInterface */
    protected $tokenStorage;

    public function setInitialDependencies(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    protected function setEntityPropertiesValues($object, $data, array $properties)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($properties as $property) {
            if (isset($data[$property])) {
                $propertyAccessor->setValue($object, $property, $data[$property]);
            }
        }
    }

    protected function createNotFoundException($message = 'Entity not found')
    {
        return new \Exception($message, ErrorCode::CODE_NOT_FOUND);
    }

    protected function createInvalidParamsException($message = 'Invalid params')
    {
        return new \Exception($message, ErrorCode::CODE_BAD_REQUEST);
    }

    protected function createAccessDeniedException($message = 'No access to this action')
    {
        return new \Exception($message, ErrorCode::CODE_FORBIDDEN);
    }
}