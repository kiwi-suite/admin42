<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
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
