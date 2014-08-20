<?php
namespace Admin42\DataTable\Decorator;

use Admin42\DataTable\Column\Column;
use Zend\Json\Expr;

class IdDecorator
{
    public function __invoke(Column $column)
    {
        if ($column->getMatchName() == 'id') {
            $column->addAttribute("render", new Expr("dth.id"));
        }
    }
}
