<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\View\Helper;

use Admin42\TableGateway\MediaTableGateway;
use Zend\View\Helper\AbstractHelper;

class Media extends AbstractHelper
{
    /**
     * @var MediaTableGateway
     */
    protected $mediaTableGateway;

    /**
     * @var array
     */
    protected $cachedMedia = [];

    /**
     * @param MediaTableGateway $mediaTableGateway
     */
    public function __construct(MediaTableGateway $mediaTableGateway)
    {
        $this->mediaTableGateway = $mediaTableGateway;
    }

    /**
     * @param null $mediaId
     * @return $this|\Admin42\Model\Media
     */
    public function __invoke($mediaId = null)
    {
        if ($mediaId !== null) {
            return $this->getMedia($mediaId);
        }

        return $this;
    }

    /**
     * @param $mediaId
     * @return \Admin42\Model\Media
     * @throws \Exception
     */
    public function getMedia($mediaId)
    {
        if (!isset($this->cachedMedia[$mediaId])) {
            $this->cachedMedia[$mediaId] = $this->mediaTableGateway->selectByPrimary((int) $mediaId);
        }

        return $this->cachedMedia[$mediaId];
    }
}
