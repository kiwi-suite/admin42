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
use Zend\Cache\Storage\StorageInterface;
use Zend\View\Helper\AbstractHelper;

class Media extends AbstractHelper
{
    /**
     * @var MediaTableGateway
     */
    protected $mediaTableGateway;

    /**
     * @var StorageInterface
     */
    protected $cache;

    /**
     * @param MediaTableGateway $mediaTableGateway
     */
    public function __construct(MediaTableGateway $mediaTableGateway, StorageInterface $cache)
    {
        $this->mediaTableGateway = $mediaTableGateway;

        $this->cache = $cache;
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
        if (empty($mediaId)) {
            return ;
        }
        if (!$this->cache->hasItem('media_'. $mediaId)) {
            $this->cache->setItem(
                'media_'. $mediaId,
                $this->mediaTableGateway->selectByPrimary((int) $mediaId)
            );
        }

        return $this->cache->getItem('media_'. $mediaId);
    }
}
