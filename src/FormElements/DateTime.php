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
