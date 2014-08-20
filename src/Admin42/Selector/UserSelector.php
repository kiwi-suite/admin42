<?php
namespace Admin42\Selector;

use Core42\Hydrator\ModelHydrator;
use Core42\Selector\AbstractDatabaseSelector;
use Zend\Db\Sql\Select;

class UserSelector extends AbstractDatabaseSelector
{

    /**
     * @return Select|string
     */
    public function getSelect()
    {
        return "SELECT * FROM admin42_user";
    }

    protected function getHydrator()
    {
        $hydrator = new ModelHydrator();
        $hydrator->addStrategy(
            'id',
            $this->getHydratorStrategy()->get('Mysql/Integer')
        );

        $hydrator->addStrategy(
            'lastLogin',
            $this->getHydratorStrategy()->get('Mysql/Datetime')
        );

        return $hydrator;
    }
}
