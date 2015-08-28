<?php
namespace Admin42\Media;

use Admin42\Model\Media;
use Admin42\TableGateway\MediaTableGateway;
use Zend\Cache\Storage\StorageInterface;
use Zend\View\Helper\AbstractHelper;

class MediaUrl extends AbstractHelper
{
    /**
     * @var MediaTableGateway;
     */
    protected $mediaTableGateway;

    /**
     * @var mediaOptions
     */
    protected $mediaOptions;

    /**
     * @var string
     */
    protected $mediaUrl;

    /**
     * @var StorageInterface
     */
    protected $cache;

    /**
     * @param MediaTableGateway $mediaTableGateway
     * @param MediaOptions $mediaOptions
     */
    public function __construct(
        MediaTableGateway $mediaTableGateway,
        MediaOptions $mediaOptions,
        $mediaUrl,
        StorageInterface $cache
    ) {
        $this->mediaTableGateway = $mediaTableGateway;

        $this->mediaOptions = $mediaOptions;

        $this->mediaUrl = $mediaUrl;

        $this->cache = $cache;
    }

    public function getUrl($mediaId, $dimension = null)
    {
        $media = $this->loadMedia($mediaId);
        if (empty($media)) {
            return "";
        }

        if (substr($media->getMimeType(), 0, 6) != "image/" || $dimension === null) {
            return $this->mediaUrl . str_replace("data/media", "", $media->getDirectory()) . $media->getFilename();
        }

        $dimensions = $this->mediaOptions->getDimensions();
        if (!isset($dimensions[$dimension])) {
            return "";
        }

        $dimension = $dimensions[$dimension];

        $filenameParts = explode(".", $media->getFilename());

        $extension = array_pop($filenameParts);
        $filename = implode(".", $filenameParts);

        $filename .= '-'
            . (($dimension['width'] == 'auto') ? '000' : $dimension['width'])
            . 'x'
            . (($dimension['height'] == 'auto') ? '000' : $dimension['height'])
            . '.' . $extension;


        return $this->mediaUrl . str_replace("data/media", "", $media->getDirectory()) . $filename;
    }

    /**
     * @param $mediaId
     * @return Media
     * @throws \Exception
     */
    public function loadMedia($mediaId)
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
