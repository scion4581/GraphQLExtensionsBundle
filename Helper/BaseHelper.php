<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2/21/17 11:42 PM
 */

namespace Youshido\GraphQLExtensionsBundle\Helper;

use Doctrine\Common\Persistence\ObjectManager;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Youshido\GraphQL\Exception\DatableResolveException;
use Youshido\GraphQL\Execution\Context\ExecutionContextInterface;
use Youshido\GraphQLExtensionsBundle\Service\ErrorCode;

class BaseHelper
{

    /** @var  TokenStorageInterface */
    protected $tokenStorage;
    /** @var  Logger */
    protected $logger;
    /** @var  AuthorizationCheckerInterface */
    protected $authChecker;
    /** @var  ValidatorInterface */
    protected $validator;
    /** @var  ObjectManager */
    protected $om;

    public function setInitialDependencies(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authChecker, ValidatorInterface $validator,
                                           LoggerInterface $logger, ObjectManager $om)
    {
        $this->tokenStorage = $tokenStorage;
        $this->logger       = $logger;
        $this->validator    = $validator;
        $this->authChecker  = $authChecker;
        $this->om           = $om;
    }

    protected function setEntityProperties($object, $values, array $properties)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($properties as $index => $property) {
            if (isset($values[$property])) {
                if (!is_numeric($index)) {
                    $object = $this->om->getRepository($index)->find($values[$property]);
                    /** This part suppose to work with fields like 'categoryId' only */
                    if (strpos($property, 'Id') !== false) {
                        $property = substr($property, 0, -2);
                    }
                    $values[$property] = $object;
                }

                $propertyAccessor->setValue($object, $property, $values[$property]);
            }
        }
    }

    protected function getObject($class, $id, $attribute = null)
    {
        $object = $this->om->getRepository($class)->find($id);

        if (!$object) {
            throw $this->createNotFoundException();
        }

        if ($attribute) {
            if (!$this->authChecker->isGranted($attribute, $object)) {
                throw $this->createAccessDeniedException();
            }
        }

        return $object;
    }

    protected function validateAndSave($object, ExecutionContextInterface $executionContext)
    {
        $errors = $this->validator->validate($object);
        if ($errors->count() > 0) {
            foreach ($errors as $error) {
                /** @var ConstraintViolation $error */
                $executionContext->addError(new DatableResolveException($error->getMessage(), ErrorCode::CODE_BAD_REQUEST, [
                    'field' => $error->getPropertyPath(),
                ]));
            }

            return null;
        }
        $this->om->persist($object);
        $this->om->flush();

        return $object;
    }

    /**
     * @return UserInterface
     *
     * @throws \Exception
     */
    public function getCurrentUser()
    {
        if ($token = $this->tokenStorage->getToken()) {
            if (($user = $token->getUser()) && is_object($user)) {
                return $user;
            }
        }

        throw new \Exception('Authorization needed', ErrorCode::CODE_UNAUTHORIZED);
    }

    /**
     * @return mixed|UserInterface
     */
    public function findCurrentUser()
    {
        if ($token = $this->tokenStorage->getToken()) {
            if (($user = $token->getUser()) && is_object($user)) {
                return $user;
            }
        }

        return null;
    }

    protected function assertIsGranted($attribute)
    {
        if (!$this->authChecker->isGranted($attribute)) {
            throw $this->createAccessDeniedException();
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

    /**
     * @return ObjectManager
     */
    public function getOm()
    {
        return $this->om;
    }

    /**
     * @param ObjectManager $om
     * @return BaseHelper
     */
    public function setOm(ObjectManager $om)
    {
        $this->om = $om;

        return $this;
    }


}