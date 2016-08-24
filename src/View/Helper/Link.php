<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\View\Helper;

use Admin42\Link\LinkProvider;
use Admin42\TableGateway\LinkTableGateway;
use Zend\Cache\Storage\StorageInterface;
use Zend\Json\Json;
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
            return "";
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
            return "";
        }

        return $this->linkProvider->getDisplayName($link->getType(), $link->getValue());
    }

    /**
     * @param $linkId
     * @return \Admin42\Model\Link
     * @throws \Exception
     */
    public function getLink($linkId)
    {
        return $this->linkProvider->getLink($linkId);
    }
}
