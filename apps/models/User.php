<?php

namespace Models;

use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Phalcon\Mvc\Model\Query\Builder as Builder;

class User extends BaseModel
{
    /**
     * @Primary
     * @Identity
     * @Column(type="integer", nullable=false, column="id")
     */
    public $id;

    /**
     * @Column(type="string", length=200, nullable=false, column="name")
     */
    public $name;

    /**
     * @Column(type="string", length=200, nullable=false, column="email")
     */
    public $email;

    /**
     * @Column(type="string", length=200, nullable=false, column="password")
     */
    public $password;

    /**
     * @Column(type="string", length=50, nullable=false, column="role")
     */
    public $role = 'member';

    /**
     * @Column(type="string", length=200, nullable=false, column="gender")
     */
    public $gender = 'male';

    /**
     * @Column(type="integer", length=1, nullable=false, column="status")
     */
    public $status = 0;

    /**
     * @Column(type="integer", length=10, nullable=false, column="datecreated")
     */
    public $dateCreated = 0;

    /**
     * @Column(type="integer", length=10, nullable=false, column="datemodified")
     */
    public $dateModified = 0;

    /**
     * Declare const
     */
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_BANNED = 2;

    /**
     * @var array roles
     */
    public static $roles = [
        'member' => 'Member',
        'employee' => 'Employee',
        'admin' => 'Administrator',
    ];

    /**
     * @var array
     */
    public static $statusName = [
        self::STATUS_ACTIVE => 'Approved',
        self::STATUS_INACTIVE => 'Pending',
        self::STATUS_BANNED => 'Banned'
    ];

    /**
     * @var array
     */
    public static $statusLabel = [
        self::STATUS_ACTIVE => 'label-success',
        self::STATUS_INACTIVE => 'label-warning',
        self::STATUS_BANNED => 'label-danger'
    ];

    public function initialize()
    {
        $this->setSource(TABLE_PREFIX . 'user');
    }

    public function beforeCreate()
    {
        $this->dateCreated = time();
    }

    public function beforeUpdate()
    {
        $this->dateModified = time();
    }

    public function getAuthData()
    {
        $authData = new \stdClass();
        $authData->id = $this->id;
        $authData->email = $this->email;
        $authData->name = $this->name;
        $authData->role = $this->getRoleName();
        $authData->gender = $this->gender;
        return $authData;
    }

    public static function getRoleById($id)
    {
        $role = self::findFirst([
            'conditions' => 'id = :userId:',
            'bind'       => ['userId' => $id],
            'columns'    => ['role'],
            'cache'      => [
                'key'      => HOST_HASH . md5(get_class() . '::getRoleById::' . $id),
                'lifetime' => 3600,
            ]
        ]);

        if ($role) {
            return $role->role;
        } else {
            return 'guest';
        }
    }

    public static function getUserById($id)
    {
        return self::findFirst([
            'conditions' => 'id = :userId:',
            'bind'       => ['userId' => $id],
            'cache'      => [
                'key'      => HOST_HASH . md5(get_class() . '::getUserById::' . $id),
                'lifetime' => 3600,
            ]
        ]);
    }

    public function afterUpdate()
    {
        $cache = $this->getDi()->get('cache');
        // Delete cache role by id
        $key = HOST_HASH . md5(get_class() . '::getRoleById::' . $this->id);
        $cache->delete($key);

        // Delete cache user by id
        $key = HOST_HASH . md5(get_class() . '::getUserById::' . $this->id);
        $cache->delete($key);
    }

    public static function getUsers($parameter = [], $columns = '*', $limit = 30, $offset = 1, $sortBy = '', $sortType = '')
    {
        $whereString = '';
        $bindParams = [];
        $modelName = get_class();

        // Begin assign keyword to search
        if (isset($parameter['keyword']) && $parameter['keyword'] != '' && isset($parameter['keywordIn'])
            && !empty($parameter['keywordIn'])
        ) {
            $keyword = $parameter['keyword'];
            $keywordIn = $parameter['keywordIn'];

            $whereString .= ($whereString != '' ? ' OR ' : ' (');
            $filter = '';
            foreach ($keywordIn as $in) {
                $filter .= ($filter != '' ? ' OR ' : '') . $in . ' LIKE :searchKeyword:';
            }

            $whereString .= $filter . ')';
            $bindParams['searchKeyword'] = '%' . $keyword . '%';
        }
        unset($parameter['keyword']);
        unset($parameter['keywordIn']);
        // End Search

        // Assign name params same MetaData
        foreach ($parameter as $key => $value) {
            $whereString .= ($whereString != '' ? ' AND ' : '') . $key . ' = :' . $key . ':';
            $bindParams[$key] = $value;
        }

        $conditions = [];
        if ($whereString != '' && !empty($bindParams)) {
            $conditions = [[$whereString, $bindParams]];
        }

        // Check order
        if ($sortBy == '') {
            $sortBy = 'id';
        }

        if (strcasecmp($sortType, 'ASC') != 0 && strcasecmp($sortType, 'DESC') != 0) {
            $sortType = 'DESC';
        }
        $order = $sortBy . ' ' . $sortType;

        $params = [
            'models' => $modelName,
            'columns' => $columns,
            'conditions' => $conditions,
            'order' => [$modelName . '.' . $order . '']
        ];

        $builder = new Builder($params);
        $pagination = new PaginatorQueryBuilder([
            'builder' => $builder,
            'limit' => $limit,
            'page' => $offset
        ]);

        return $pagination->getPaginate();
    }

    public function getRoleName()
    {
        return self::$roles[$this->role];
    }

    public function getStatusName()
    {
        return self::$statusName[$this->status];
    }

    public function getStatusLabel()
    {
        return self::$statusLabel[$this->status];
    }
}
