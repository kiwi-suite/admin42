<?php
namespace Admin42\Command\Media;

use Admin42\Model\Media;
use Core42\Command\AbstractCommand;
use Imagine\Image\Box;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Point;
use Zend\Json\Json;

class ImageCropCommand extends AbstractCommand
{
    /**
     * @var int
     */
    protected $mediaId;

    /**
     * @var Media
     */
    protected $media;

    /**
     * @var ImagineInterface
     */
    protected $imagine;

    /**
     * @var string
     */
    protected $dimensionName;

    /**
     * @var array
     */
    protected $dimension;

    /**
     * @var int
     */
    protected $offsetX;

    /**
     * @var int
     */
    protected $offsetY;

    /**
     * @var int
     */
    protected $boxWidth;

    /**
     * @var int
     */
    protected $boxHeight;

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
     * @param string $dimensionName
     * @return $this
     */
    public function setDimensionName($dimensionName)
    {
        $this->dimensionName = $dimensionName;

        return $this;
    }

    /**
     * @param $boxWidth
     * @return $this
     */
    public function setBoxWidth($boxWidth)
    {
        $this->boxWidth = $boxWidth;

        return $this;
    }

    /**
     * @param $boxHeight
     * @return $this
     */
    public function setBoxHeight($boxHeight)
    {
        $this->boxHeight = $boxHeight;

        return $this;
    }

    /**
     * @param $offsetX
     * @return $this
     */
    public function setOffsetX($offsetX)
    {
        $this->offsetX = $offsetX;

        return $this;
    }

    /**
     * @param $offsetY
     * @return $this
     */
    public function setOffsetY($offsetY)
    {
        $this->offsetY = $offsetY;

        return $this;
    }

    /**
     * @throws \Exception
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

        $mediaConfig = $this->getServiceManager()->get('config')['media'];
        if (!isset($mediaConfig['images']['dimensions'][$this->dimensionName])) {
            $this->addError("dimensions", "dimensionName invalid");

            return;
        }

        $this->dimension = $mediaConfig['images']['dimensions'][$this->dimensionName];

        $this->imagine = $this->getServiceManager()->get('Imagine');

        $this->offsetX = round($this->offsetX);
        $this->offsetY = round($this->offsetY);
        $this->boxWidth = round($this->boxWidth);
        $this->boxHeight = round($this->boxHeight);
    }

    /**
     * @return mixed
     */
    protected function execute()
    {
        $filenameParts = explode(".", $this->media->getFilename());

        $extension = array_pop($filenameParts);
        $filename = implode(".", $filenameParts);

        $filename .= '-'
            . (($this->dimension['width'] == 'auto') ? '000' : $this->dimension['width'])
            . 'x'
            . (($this->dimension['height'] == 'auto') ? '000' : $this->dimension['height']);

        $media = new Media();
        $media->setFilename($filename . '.' . $extension)
            ->setDirectory($this->media->getDirectory());

        $width = (($this->dimension['width'] == 'auto') ? PHP_INT_MAX : $this->dimension['width']);
        $height = (($this->dimension['height'] == 'auto') ? PHP_INT_MAX : $this->dimension['height']);

        $this->imagine
            ->open($this->media->getDirectory() . $this->media->getFilename())
            ->crop(new Point($this->offsetX, $this->offsetY), new Box($this->boxWidth, $this->boxHeight))
            ->thumbnail(new Box($width, $height))
            ->save($media->getDirectory() . $media->getFilename());

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $media->setMimeType(finfo_file($finfo, $media->getDirectory() . $media->getFilename()));
        $media->setSize(filesize($media->getDirectory() . $media->getFilename()));


        $meta = $this->media->getMeta();
        $meta = (strlen($meta)) ? Json::decode($meta, true) : [];
        $meta[$this->dimensionName] = [
            'x' => $this->offsetX,
            'y' => $this->offsetY,
            'width' => $this->boxWidth,
            'height' => $this->boxHeight
        ];
        $this->media->setMeta(Json::encode($meta));
        if ($this->media->hasChanged()) {
            $this->media->setUpdated(new \DateTime());
        }
        $this->getTableGateway('Admin42\Media')->update($this->media);

        return $media;
    }
}
