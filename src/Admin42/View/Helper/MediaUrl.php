<?php
namespace Admin42\View\Helper;

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