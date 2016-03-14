<?php

namespace Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query\Builder as Builder;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class BaseModel extends Model
{
    /**
     * Get list record with pagination
     * @param $params
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public static function getList($params, $limit, $offset)
    {
        $builder = new Builder($params);
        $pagination = new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit' => $limit,
            'page' => $offset
        ]);

        return $pagination->getPaginate();
    }
}
