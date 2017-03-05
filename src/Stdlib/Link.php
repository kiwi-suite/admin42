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
