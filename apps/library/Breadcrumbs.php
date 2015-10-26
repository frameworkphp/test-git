<?php
/**
 * Breadcrumbs.php 23/10/2015
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

class Breadcrumbs
{
    /**
     * @var array
     */
    private $_elements = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_elements[] = [
            'active' => false,
            'link' => '/',
            'text' => 'Home',
        ];
    }

    /**
     * Adds a new element in the stack
     *
     * @param string $caption
     * @param string $link
     */
    public function add($caption, $link)
    {
        $this->_elements[] = [
            'active' => false,
            'link' => '/' . $link,
            'text' => $caption,
        ];
    }

    /**
     * Resets the internal element array
     */
    public function reset()
    {
        $this->_elements = [];
    }

    /**
     * Generates the JSON string from the internal array
     *
     * @return string
     */
    public function generate()
    {
        $lastKey = key(array_slice($this->_elements, -1, 1, true));
        $this->_elements[$lastKey]['active'] = true;
        return $this->_elements;
    }
}
