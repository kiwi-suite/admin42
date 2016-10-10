<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
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
        if (!strlen($this->current()->getPermission())) {
            return true;
        }

        return $this->permission->isGranted($this->current()->getPermission());
    }
}
