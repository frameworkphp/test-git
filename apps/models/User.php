<?php

namespace Models;

/**
 * User
 *
 * Represents a User
 *
 * @Source('user');
 */
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
    public $role;

    /**
     * @Column(type="string", length=200, nullable=false, column="gender")
     */
    public $gender;

    /**
     * @Column(type="integer", length=1, nullable=false, column="status")
     */
    public $status;

    /**
     * @Column(type="integer", length=10, nullable=false, column="datecreated")
     */
    public $dateCreated;

    /**
     * @Column(type="integer", length=10, nullable=false, column="datemodified")
     */
    public $dateModified;


    public function beforeCreate()
    {
        $this->dateCreated = time();
    }

    public function beforeUpdate()
    {
        $this->dateModified = time();
    }
}
