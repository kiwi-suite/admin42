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


namespace Admin42\View\Helper;

use Admin42\Link\LinkProvider;
use Zend\View\Helper\AbstractHelper;

class Link extends AbstractHelper
{
    /**
     * @var LinkProvider
     */
    protected $linkProvider;

    /**
     * @param LinkProvider $linkProvider
     */
    public function __construct(
        LinkProvider $linkProvider
    ) {
        $this->linkProvider = $linkProvider;
    }

    /**
     * @param null $linkId
     * @return $this|string
     */
    public function __invoke($linkId = null)
    {
        if ($linkId === null) {
            return $this;
        }

        $link = $this->getLink($linkId);
        if (empty($link)) {
            return '';
        }

        return $this->linkProvider->assembleById($linkId);
    }

    /**
     * @param $linkId
     * @return string
     */
    public function getDisplayName($linkId)
    {
        $link = $this->getLink($linkId);
        if (empty($link)) {
            return '';
        }

        return $this->linkProvider->getDisplayName($link->getType(), $link->getValue());
    }

    /**
     * @param $linkId
     * @throws \Exception
     * @return \Admin42\Model\Link
     */
    public function getLink($linkId)
    {
        return $this->linkProvider->getLink($linkId);
    }
}
