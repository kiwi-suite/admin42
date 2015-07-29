<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Command\Media;

use Admin42\Model\Media;
use Core42\Command\AbstractCommand;

class DeleteCommand extends AbstractCommand
{
    /**
     * @var Media
     */
    protected $media;

    /**
     * @var int
     */
    protected $mediaId;

    /**
     * @param int $mediaId
     * @return $this
     */
    public function setMediaId($mediaId)
    {
        $this->mediaId = $mediaId;

        return $this;
    }

    /**
     * @param Media $media
     * @return $this
     */
    public function setMedia(Media $media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        if ($this->mediaId > 0) {
            $this->media = $this->getTableGateway('Admin42\Media')->selectByPrimary((int) $this->mediaId);
        }

        if (empty($this->media)) {
            $this->addError("media", "media not found");

            return;
        }
    }


    /**
     * @return mixed|void
     */
    protected function execute()
    {
        $dir = scandir($this->media->getDirectory());
        foreach ($dir as $_entry) {
            if ($_entry == ".." || $_entry == ".") {
                continue;
            }

            @unlink($this->media->getDirectory() . $_entry);
        }

        @rmdir($this->media->getDirectory());

        $this->getTableGateway('Admin42\Media')->delete($this->media);
    }
}
