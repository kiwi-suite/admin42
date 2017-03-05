<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
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
