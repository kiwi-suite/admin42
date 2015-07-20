<?php
namespace Admin42\Command\Media;

use Admin42\Model\Media;
use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use Core42\Db\ResultSet\ResultSet;
use Imagine\Image\Box;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Point;
use Zend\Json\Json;
use ZF\Console\Route;

class RegenerateCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @var ResultSet
     */
    protected $result;
    /**
     * @throws \Exception
     */
    protected function preExecute()
    {
        $this->result = $this->getTableGateway('Admin42\Media')->select();
    }

    /**
     * @return mixed
     */
    protected function execute()
    {
        $mediaOptions = $this->getServiceManager()->get('Admin42\MediaOptions');


        /** @var Media $media */
        foreach ($this->result as $media) {
            if (substr($media->getMimeType(), 0, 6) !== "image/") {
                continue;
            }

            $dir = scandir($media->getDirectory());
            foreach ($dir as $_entry) {
                if ($_entry == ".." || $_entry == ".") {
                    continue;
                }

                if ($_entry == $media->getFilename()) {
                    continue;
                }

                unlink($media->getDirectory() . $_entry);
            }

            foreach(array_keys($mediaOptions->getDimensions()) as $dimension) {
                $cmd = $this->getCommand('Admin42\Media\ImageResize');
                $cmd->setMedia($media)
                    ->setDimensionName($dimension)
                    ->run();
            }
        }
    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {
        // TODO: Implement consoleSetup() method.
    }
}
