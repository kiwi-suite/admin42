<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/kiwi-suite/admin42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <tech@kiwi-suite.com>
 */


namespace Admin42\Navigation\Filter;

use Core42\Navigation\Filter\AbstractFilter;
use Core42\Permission\Permission;

class PermissionFilter extends AbstractFilter
{
    /**
     * @var Permission
     */
    protected $permission;

    /**
     * @param \RecursiveIterator $iterator
     * @param Permission $permission
     */
    public function __construct(\RecursiveIterator $iterator, Permission $permission)
    {
        $this->permission = $permission;
        parent::__construct($iterator);
    }

    /**
     * @return PermissionFilter
     */
    public function getChildren()
    {
        return new self($this->getInnerIterator()->getChildren(), $this->permission);
    }

    /**
     * @return bool
     */
    protected function isAccepted()
    {
        if (!\mb_strlen($this->current()->getPermission())) {
            return true;
        }

        return $this->permission->authorized($this->current()->getPermission());
    }
}
