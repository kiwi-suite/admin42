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
