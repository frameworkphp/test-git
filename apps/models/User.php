<?php

namespace Models;

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

    /**
     * @var array roles
     */
    public static $roles = [
        'member' => 'Member',
        'employee' => 'Employee',
        'admin' => 'Admin',
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

    public function afterUpdate()
    {
        $cache = $this->getDi()->get('cache');
        $key = HOST_HASH . md5(get_class() . '::getRoleById::' . $this->id);
        $cache->delete($key);
    }
}
