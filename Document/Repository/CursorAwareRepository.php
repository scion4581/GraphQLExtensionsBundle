<?php
/**
 * This file is a part of GraphQLExtensionsBundle project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 3/1/17 11:07 PM
 */

namespace Youshido\GraphQLExtensionsBundle\Document\Repository;


use Symfony\Component\PropertyAccess\PropertyAccessor;

class CursorAwareRepository extends BaseRepository
{

    public function getCursoredList($args)
    {
        $this->assertValidCursorArguments($args);
        $limit     = static::DEFAULT_PAGE_LIMIT;
        $direction = 1;

        if (!empty($args['first'])) {
            $limit = $args['first'];
        } elseif (!empty($args['last'])) {
            $direction = -1;
            $limit     = $args['last'];
        }
        if (empty($args['sort'])) {
            $args['sort'] = [
                'field' => 'id',
                'order' => 1,
            ];
        }
        $sortOrder      = !empty($args['sort']['order']) ? $args['sort']['order'] : 1;
        $finalDirection = $sortOrder * $direction;

        $qb         = $this->createQueryBuilder()
            ->sort($args['sort']['field'], $finalDirection)
            ->limit($limit);
        $cursorData = null;
        if (!empty($args['after'])) {
            $cursorData = $this->cursorToData($args['after']);
        } elseif (!empty($args['before'])) {
            $cursorData = $this->cursorToData($args['before']);
        }
        if ($cursorData) {
            $sortCommand = $finalDirection === 1 ? '$gte' : '$lte';
            $qb->addAnd([
                $args['sort']['field'] => [$sortCommand => $cursorData[1]],
                'id'                   => ['$ne' => $cursorData[0]]
            ]);
        }
        $data  = $qb->find()
            ->getQuery()->execute();
        $edges = [];
        foreach ($data as $item) {
            $edges[] = [
                'node'   => $item,
                'cursor' => $this->cursorForObjectWithSort($item, $args['sort'])
            ];
        }

        return [
            'edges'    => $sortOrder === $finalDirection ? $edges : array_reverse($edges),
            'pageInfo' => [
                'startCursor'     => null,
                'endCursor'       => null,
                'hasPreviousPage' => false,
                'hasNextPage'     => false
            ]
        ];
    }

    protected function assertValidCursorArguments($args)
    {
        if (!empty($args['first'])) {
            if (!empty($args['last']) || !empty($args['before'])) {
                $this->throwInvalidParamsException();
            }
        } elseif (!empty($args['after'])) {
            $this->throwInvalidParamsException();
        }
        if (!empty($args['last'])) {
            if (!empty($args['first']) || !empty($args['after'])) {
                $this->throwInvalidParamsException();
            }
        } elseif (!empty($args['before'])) {
            $this->throwInvalidParamsException();
        }
    }

    protected function throwInvalidParamsException($message = 'Invalid cursor params')
    {
        throw new \Exception($message);
    }

    protected function cursorToData($cursor)
    {
        try {
            $data = base64_decode($cursor);
        } catch (\Exception $e) {
            return null;
        }

        return explode(chr(0), $data);
    }

    protected function cursorForObjectWithSort($object, $sort)
    {
        $ap = new PropertyAccessor();

        $fieldValue = $ap->getValue($object, $sort['field']);
        $id         = $ap->getValue($object, 'id');

        return base64_encode($id . chr(0) . $fieldValue);
    }
}