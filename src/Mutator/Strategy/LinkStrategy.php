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

namespace Admin42\Mutator\Strategy;

use Admin42\Link\LinkProvider;
use Admin42\Stdlib\Link;
use Core42\Hydrator\Mutator\Strategy\StrategyInterface;

class LinkStrategy implements StrategyInterface
{

    /**
     * @var LinkProvider
     */
    private $linkProvider;

    /**
     * LinkStrategy constructor.
     * @param LinkProvider $linkProvider
     */
    public function __construct(
        LinkProvider $linkProvider
    ) {
        $this->linkProvider = $linkProvider;
    }

    /**
     * @param mixed $value
     * @param array $spec
     * @return mixed
     */
    public function hydrate($value, array $spec = [])
    {
        return new Link($this->linkProvider, $value);
    }
}
