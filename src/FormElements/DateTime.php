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


namespace Admin42\FormElements;

use Core42\Hydrator\Strategy\DateTimeStrategy;
use Zend\Hydrator\Strategy\StrategyInterface;

class DateTime extends Date implements StrategyAwareInterface
{
    /**
     * @var string
     */
    protected $format = 'Y-m-d H:i:s';

    /**
     * @return string|StrategyInterface
     */
    public function getStrategy()
    {
        return DateTimeStrategy::class;
    }
}
