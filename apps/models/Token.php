<?php
/**
 * Token.php 01/02/2016
 * ----------------------------------------------
 *
 * @author      Phan Nguyen <phannguyen2020@gmail.com>
 * @copyright   Copyright (c) 2016, framework
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */

namespace Models;


class Token extends BaseModel
{
    /**
     * @Primary
     * @Identity
     * @Column(type="integer", nullable=false, column="id")
     */
    public $id;

    /**
     * @Column(type="integer", length=11, nullable=false, column="user_id")
     */
    public $userId;

    /**
     * @Column(type="string", length=255, nullable=false, column="token")
     */
    public $token;

    /**
     * @Column(type="string", length=255, nullable=false, column="user_agent")
     */
    public $userAgent;

    /**
     * @Column(type="integer", length=10, nullable=false, column="datecreated")
     */
    public $dateCreated = 0;


    public function initialize()
    {
        $this->setSource(TABLE_PREFIX . 'token');

//        $this->belongsTo('userId', __NAMESPACE__ . '\User', 'id', [
//            'alias' => 'user'
//        ]);
    }

    public function beforeCreate()
    {
        $this->dateCreated = time();
    }
}
