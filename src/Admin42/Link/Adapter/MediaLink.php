<?php
namespace Admin42\Link\Adapter;

use Admin42\Model\Media;
use Admin42\TableGateway\MediaTableGateway;

class MediaLink implements AdapterInterface
{
    /**
     * @var MediaTableGateway
     */
    protected $mediaTableGateway;

    /**
     * @var
     */
    protected $mediaUrl;

    /**
     * @param MediaTableGateway $mediaTableGateway
     * @param $mediaUrl
     */
    public function __construct(MediaTableGateway $mediaTableGateway, $mediaUrl)
    {
        $this->mediaTableGateway = $mediaTableGateway;

        $this->mediaUrl = $mediaUrl;
    }

    public function assemble($value)
    {
        $media = $this->getLinkData($value);
        if (empty($media)) {
            return "";
        }

        return $this->mediaUrl . str_replace("data/media", "", $media->getDirectory()) . $media->getFilename();
    }

    public function getDisplayName($value)
    {
        $media = $this->getLinkData($value);
        if (empty($media)) {
            return "";
        }

        return $media->getTitle();
    }

    /**
     * @param $value
     * @return Media
     * @throws \Exception
     */
    protected function getLinkData($value)
    {
        return $this->mediaTableGateway->selectByPrimary((int) $value['id']);
    }
}
