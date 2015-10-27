<?php
/**
 * Acl.php 26/10/2015
 * ----------------------------------------------
 *
 * @author      Phan Nguyen <phannguyen2020@gmail.com>
 * @copyright   Copyright (c) 2015, framework
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */

namespace Library;

use Phalcon\Mvc\User\Component;
use Phalcon\Acl\Adapter\Memory as AclMemory;
use Phalcon\Acl\Role as Role;
use Phalcon\Acl\Resource as Resource;

class Acl extends Component
{
    /**
     * The ACL Object
     *
     * @var \Phalcon\Acl\Adapter\Memory
     */
    private $acl;

    /**
     * The resources from file
     *
     * @var apps\config\acl.php
     */
    private $resources;

    /**
     * The file path of the Acl cache file from ROOT_URL
     *
     * @var string
     */
    private $filePath = '/apps/cache/acl/data.txt';

    /**
     * @param $resources
     */
    public function __construct($resources)
    {
        $this->resources = $resources;
    }

    /**
     * Returns the ACL list
     */
    public function getAcl()
    {
        // Check if the Acl is in APC
        if (function_exists('apc_fetch')) {
            $acl = apc_fetch('acl');
            if (is_object($acl)) {
                $this->acl = $acl;
                return $this->acl;
            }
        }

        // Check if the acl is already generated
        if (!file_exists(ROOT_URL . $this->filePath)) {
            $this->acl = $this->createAcl();
            return $this->acl;
        }

        // Get the ACL from the data file
        $data = file_get_contents(ROOT_URL . $this->filePath);
        $this->acl = unserialize($data);

        // Store the ACL in APC
        if (function_exists('apc_store')) {
            apc_store('acl', $this->acl);
        }

        return $this->acl;
    }

    public function createAcl()
    {
        $acl = new AclMemory();
        $acl->setDefaultAction(\Phalcon\Acl::DENY);

        foreach ($this->resources as $role => $groups) {
            $acl->addRole(new Role($role, ucfirst($role)));
            foreach ($groups as $module => $controllers) {
                foreach ($controllers as $controller => $actions) {
                    $resource = strtolower($module) . '/' . $controller;
                    $acl->addResource(new Resource($resource), $actions);
                    $acl->allow($role, $resource, $actions);
                }
            }
        }

        if (touch(ROOT_URL . $this->filePath) && is_writable(ROOT_URL . $this->filePath)) {
            // Store the acl in File
            file_put_contents(ROOT_URL . $this->filePath, serialize($acl));

            // Store the Acl in APC
            if (function_exists('apc_store')) {
                apc_store('acl', $acl);
            }
        }

        return $acl;
    }
}
