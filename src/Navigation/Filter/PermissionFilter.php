<?php
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
     * @return boolean
     */
    protected function isAccepted()
    {
        if (!strlen($this->current()->getRoute())) {
            return true;
        }

        return $this->permission->isGranted('route/' . $this->current()->getRoute());
    }
}
