<?php
namespace Admin42\Media;

use Zend\Stdlib\AbstractOptions;

class MediaOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $images = [];

    /**
     * @param $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param array $images
     */
    public function setImages(array $images)
    {
        $this->images = $images;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param bool $includeSystem
     * @return array
     */
    public function getDimensions($includeSystem = true)
    {
        $dimensions = [];

        foreach($this->images['dimensions'] as $name => $_dimensions) {
            if ($includeSystem === false
                && array_key_exists('system', $_dimensions)
                && $_dimensions['system'] === true)
            {
                continue;
            }

            $dimensions[$name] = $_dimensions;
        }

        return $dimensions;
    }
}
