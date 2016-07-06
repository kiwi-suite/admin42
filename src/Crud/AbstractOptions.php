<?php
namespace Admin42\Crud;

abstract class AbstractOptions
{
    /**
     * @return string
     */
    abstract public function getTableGatewayName();

    /**
     * @return string
     */
    abstract public function getFormName();

    /**
     * @return string
     */
    abstract public function getSelectorName();

    /**
     * @return string
     */
    abstract public function getIcon();

    /**
     * @return mixed
     */
    abstract public function getName();

    /**
     * @return mixed
     */
    abstract public function getIndexViewTemplate();

    /**
     * @return string
     */
    public function getEditCommandName()
    {
        return 'Admin42\Crud\Edit';
    }

    /**
     * @return string
     */
    public function getCreateCommandName()
    {
        return 'Admin42\Crud\Create';
    }

    /**
     * @return string
     */
    public function getDeleteCommandName()
    {
        return 'Admin42\Crud\Delete';
    }
}
