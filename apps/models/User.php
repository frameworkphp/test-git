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


    public function initialize()
    {
        $this->setSource(DB_PREFIX . 'user');
    }

    public function beforeCreate()
    {
        $this->dateCreated = time();
    }

    public function beforeUpdate()
    {
        $this->dateModified = time();
    }
}
