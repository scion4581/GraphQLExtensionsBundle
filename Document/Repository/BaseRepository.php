<?php

namespace Youshido\GraphQLExtensionsBundle\Document\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\PropertyAccess\PropertyAccess;

class BaseRepository extends DocumentRepository
{
    const DEFAULT_PAGE_LIMIT = 20;

    public function setEntityPropertiesValues($object, $data, array $properties)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($properties as $property) {
            if (isset($data[$property])) {
                $propertyAccessor->setValue($object, $property, $data[$property]);
            }
        }
    }

    /**
     * @param $filters
     * @return \Doctrine\ODM\MongoDB\Query\Builder
     */
    protected function createQueryForFilters($filters)
    {
        $qb = $this->createQueryBuilder();

        if (isset($filters['query']) && $filters['query']) {
            $qb->field('title')->equals(new \MongoRegex(sprintf('/^%s/i', $filters['query'])));
        }

        $qb->sort('title', 'asc');

        return $qb;
    }

    public function getPaginatedResult($filters)
    {
        $qb = $this->createQueryForFilters($filters);

        $totalCount = $qb->getQuery()->count(true);

        $result = [
            'totalCount' => $totalCount,
        ];
        /** these should be always here for such method */
        if (isset($filters['pagingInfo'])) {
            $qb->limit($filters['pagingInfo']['limit']);
            $qb->skip($filters['pagingInfo']['offset']);
            $result['limit']  = $filters['pagingInfo']['limit'];
            $result['offset'] = $filters['pagingInfo']['offset'];
        }
        $result['items'] = $qb->getQuery()->execute();

        return $result;
    }

    public function getFilteredResult($filters)
    {
        return $this->createQueryForFilters($filters)->getQuery()->execute();
    }



}