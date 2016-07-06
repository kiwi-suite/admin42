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

class ImportCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @param $filename
     * @return $this
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }


    /**
     * @return mixed|void
     */
    protected function execute()
    {
        $targetDir = 'data/media/' . implode(DIRECTORY_SEPARATOR, str_split(substr(md5(uniqid()), 0, 6), 2)) . '/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $originalFilename = basename($this->filename);

        $source = $targetDir . $originalFilename;
        file_put_contents($source, file_get_contents($this->filename));

        $dateTime = new \DateTime();

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $media = new Media();
        $media->setFilename($originalFilename)
            ->setTitle($originalFilename)
            ->setMeta(json_encode([]))
            ->setDirectory($targetDir)
            ->setMimeType(finfo_file($finfo, $source))
            ->setSize(sprintf("%u", filesize($source)))
            ->setUpdated($dateTime)
            ->setCreated($dateTime);

        $this->getTableGateway('Admin42\Media')->insert($media);

        if (substr($media->getMimeType(), 0, 6) == "image/") {
            $mediaOptions = $this->getServiceManager()->get('Admin42\MediaOptions');

            foreach (array_keys($mediaOptions->getDimensions()) as $dimension) {
                /* @var ImageResizeCommand $cmd */
                $cmd = $this->getCommand('Admin42\Media\ImageResize');
                $cmd->setMedia($media)
                    ->setDimensionName($dimension)
                    ->run();
            }
        }

        return $media;
    }
}
