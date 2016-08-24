<?php
namespace Admin42\Crud;

use Admin42\Command\Crud\CreateCommand;
use Admin42\Command\Crud\DeleteCommand;
use Admin42\Command\Crud\EditCommand;

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
        return EditCommand::class;
    }

    /**
     * @return string
     */
    public function getCreateCommandName()
    {
        return CreateCommand::class;
    }

    /**
     * @return string
     */
    public function getDeleteCommandName()
    {
        return DeleteCommand::class;
    }
}
