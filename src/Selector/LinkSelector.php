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

namespace Admin42\Selector;

use Admin42\TableGateway\LinkTableGateway;
use Core42\Selector\AbstractSelector;
use Core42\Selector\CacheAbleTrait;

class LinkSelector extends AbstractSelector
{
    use CacheAbleTrait;

    /**
     * @var int
     */
    protected $linkId;

    /**
     * @param int $linkId
     * @return $this
     */
    public function setLinkId($linkId)
    {
        $this->linkId = $linkId;

        return $this;
    }

    /**
     * @return string
     */
    protected function getCacheName()
    {
        return "link";
    }

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        return "l" . $this->linkId;
    }

    /**
     * @return mixed
     */
    protected function getUncachedResult()
    {
        return $this->getTableGateway(LinkTableGateway::class)->selectByPrimary((int) $this->linkId);
    }
}
