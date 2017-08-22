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

namespace Admin42\Stdlib;

use Admin42\Link\LinkProvider;

class Link
{
    /**
     * @var LinkProvider
     */
    private $linkProvider;

    /**
     * @var int
     */
    private $id;

    /**
     * Link constructor.
     * @param LinkProvider $linkProvider
     * @param int $id
     */
    public function __construct(LinkProvider $linkProvider, $id)
    {
        $this->linkProvider = $linkProvider;
        $this->id = (int) $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        if (empty($this->id)) {
            return null;
        }

        $link = $this->linkProvider->getLink($this->id);

        if (empty($link)) {
            return null;
        }

        return $link->getType();
    }

    /**
     * @return string
     */
    public function assemble()
    {
        return $this->linkProvider->assembleById($this->id);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->assemble();
    }
}
