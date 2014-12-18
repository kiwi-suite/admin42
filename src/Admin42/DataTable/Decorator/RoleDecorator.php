<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\DataTable\Decorator;

use Admin42\DataTable\Column\Column;
use Zend\Json\Expr;

class RoleDecorator
{
    /**
     * @param Column $column
     * @return Expr
     */
    public function __invoke(Column $column)
    {
        return new Expr("dataTableHelper.role");
    }
}
