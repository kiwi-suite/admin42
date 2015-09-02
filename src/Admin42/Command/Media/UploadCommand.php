<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Command\Media;

use Admin42\Media\MediaEvent;
use Admin42\Model\Media;
use Core42\Command\AbstractCommand;

class UploadCommand extends AbstractCommand
{
    /**
     * @var array
     */
    private $uploadData;

    /**
     * @var string
     */
    private $category;

    /**
     * @param array $uploadData
     * @return $this
     */
    public function setUploadData(array $uploadData)
    {
        $this->uploadData = $uploadData;

        return $this;
    }

    /**
     * @param string $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @param array $values
     * @throws \Exception
     */
    public function hydrate(array $values)
    {
        $this->setUploadData($values['file']);
        $this->setCategory($values['category']);
    }

    protected function preExecute()
    {
        $mediaOptions = $this->getServiceManager()->get('Admin42\MediaOptions');
        $categories = $mediaOptions->getCategories();
        $categories = array_keys($categories);

        if (!in_array($this->category, $categories)) {
            $this->category = "default";
        }
    }

    /**
     * @return mixed|void
     */
    protected function execute()
    {
        $dateTime = new \DateTime();
        $source = $this->uploadData['tmp_name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $media = new Media();
        $media->setFilename($this->uploadData['name'])
            ->setTitle($this->uploadData['name'])
            ->setCategory($this->category)
            ->setMeta(json_encode([]))
            ->setDirectory(dirname($source) . DIRECTORY_SEPARATOR)
            ->setMimeType(finfo_file($finfo, $source))
            ->setSize(sprintf("%u", filesize($source)))
            ->setUpdated($dateTime)
            ->setCreated($dateTime);

        $this->getTableGateway('Admin42\Media')->insert($media);
        $this
            ->getServiceManager()
            ->get('Admin42\Media\EventManager')
            ->trigger(MediaEvent::EVENT_ADD, $media);

        if (substr($media->getMimeType(), 0, 6) == "image/") {
            $mediaOptions = $this->getServiceManager()->get('Admin42\MediaOptions');

            foreach(array_keys($mediaOptions->getDimensions()) as $dimension) {
                /* @var ImageResizeCommand $cmd */
                $cmd = $this->getCommand('Admin42\Media\ImageResize');
                $cmd->setMedia($media)
                    ->setDimensionName($dimension)
                    ->run();
            }
        }
    }
}
