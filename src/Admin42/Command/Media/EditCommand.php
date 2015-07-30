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

class EditCommand extends AbstractCommand
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
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $keywords;

    /**
     * @var array
     */
    protected $uploadData;

    /**
     * @param int $mediaId
     * @return $this
     */
    public function setMediaId($mediaId)
    {
        $this->mediaId = (int) $mediaId;
        return $this;
    }

    /**
     * @param Media $media
     * @return $this
     */
    public function setMedia($media)
    {
        $this->media = $media;
        return $this;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string $keywords
     * @return $this
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * @param array $uploadData
     * @return $this
     */
    public function setUploadData($uploadData)
    {
        $this->uploadData = $uploadData;
        return $this;
    }

    public function hydrate(array $values)
    {
        $this->setTitle($values['title']);
        $this->setDescription($values['description']);
        $this->setKeywords($values['keywords']);
    }

    /**
     * @throws \Exception
     */
    protected function preExecute()
    {
        if (!empty($this->mediaId)) {
            $this->media = $this->getTableGateway('Admin42\Media')->selectByPrimary($this->mediaId);
        }

        if (!($this->media instanceof Media)) {
            $this->addError("media", "invalid media");
            return;
        }

        if (empty($this->title)) {
            $this->addError("title", "title can't be empty");
        }
    }

    /**
     * @return mixed
     */
    protected function execute()
    {
        $this->media->setTitle($this->title)
            ->setDescription((!empty($this->description) ? $this->description : null))
            ->setKeywords($this->keywords)
            ->setUpdated(new \DateTime());

        $this->getTableGateway('Admin42\Media')->update($this->media);

        if (!empty($this->uploadData)) {
            /* @var UploadCommand $cmd */
            /*
            $cmd = $this->getCommand('Admin42\Media\Upload');
            $cmd->setMedia($this->media)
                ->setUploadData('')
                ->run();
            */
        }

        return $this->media;
    }
}
