<?php
namespace Admin42\FormElements;

use Zend\Hydrator\Strategy\StrategyInterface;

interface StrategyAwareInterface
{

    /**
     * @return string|StrategyInterface
     */
    public function getStrategy();
}
