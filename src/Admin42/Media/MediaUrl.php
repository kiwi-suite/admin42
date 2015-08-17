<?php
namespace Admin42\Media;

use Admin42\Model\Media;
use Admin42\TableGateway\MediaTableGateway;
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
     * @var array
     */
    protected $cached = [];

    /**
     * @param MediaTableGateway $mediaTableGateway
     * @param MediaOptions $mediaOptions
     */
    public function __construct(MediaTableGateway $mediaTableGateway, MediaOptions $mediaOptions, $mediaUrl)
    {
        $this->mediaTableGateway = $mediaTableGateway;

        $this->mediaOptions = $mediaOptions;

        $this->mediaUrl = $mediaUrl;
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
        if (!isset($this->cached[$mediaId])) {
            $this->cached[$mediaId] = $this->mediaTableGateway->selectByPrimary((int) $mediaId);
        }

        return $this->cached[$mediaId];
    }
}