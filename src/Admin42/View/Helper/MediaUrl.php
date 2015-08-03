<?php
namespace Admin42\View\Helper;

use Admin42\Media\MediaOptions;
use Admin42\Model\Media;
use Admin42\TableGateway\MediaTableGateway;
use Zend\View\Helper\AbstractHelper;

class MediaUrl extends AbstractHelper
{
    /**
     * @var \Admin42\Media\MediaUrl;
     */
    protected $mediaUrl;

    /**
     * @param \Admin42\Media\MediaUrl $mediaUrl
     */
    public function __construct(\Admin42\Media\MediaUrl $mediaUrl)
    {
        $this->mediaUrl = $mediaUrl;
    }

    /**
     * @param $mediaId
     * @param null $dimension
     * @return string
     */
    public function __invoke($mediaId, $dimension = null)
    {
        return $this->mediaUrl->getUrl($mediaId, $dimension);
    }
}