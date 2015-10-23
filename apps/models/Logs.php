<?php
/**
 * Logs.php 21/10/2015
 * ----------------------------------------------
 *
 * @author      Phan Nguyen <phannguyen2020@gmail.com>
 * @copyright   Copyright (c) 2015, framework
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */

namespace Models;

use Phalcon\Logger as Logger;
use Phalcon\DI\FactoryDefault;

class Logs extends BaseModel
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
     * @Column(type="integer", length=3, nullable=false, column="type")
     */
    public $type = 0;

    /**
     * @Column(type="string", length=200, nullable=false, column="content")
     */
    public $content;

    /**
     * @Column(type="integer", length=10, nullable=false, column="created_at")
     */
    public $created_at = 0;

    const EMERGENCY = 0;
    const CRITICAL = 1;
    const ALERT = 2;
    const ERROR = 3;
    const WARNING = 4;
    const NOTICE = 5;
    const INFO = 6;
    const DEBUG = 7;

    public function initialize()
    {
        $this->setSource(TABLE_PREFIX . 'logs');
    }

    /**
     * @param $name
     * @param $message
     * @param $type
     */
    public static function log($name, $message, $type)
    {
        $typeName = self::getTypeString($type);
        $logger = FactoryDefault::getDefault()->get('logger');

        $logger->name = $name;
        $logger->$typeName($message);
    }

    /**
     * getTypeString
     *
     * Translates Phalcon log types into type strings.
     *
     * @param  integer $type
     * @return string
     */
    public static function getTypeString($type)
    {
        switch ($type) {
            case Logger::EMERGENCY:
            case Logger::EMERGENCE:
            case Logger::CRITICAL:
                // emergence, critical
                return 'critical';
            case Logger::ALERT:
            case Logger::ERROR:
                // error, alert
                return 'error';
            case Logger::WARNING:
                // warning
                return 'warning';
            case Logger::NOTICE:
            case Logger::INFO:
                // info, notice
                return 'info';
            case Logger::DEBUG:
            case Logger::CUSTOM:
            case Logger::SPECIAL:
            default:
                // debug, log, custom, special
                return 'debug';
        }
    }
}
