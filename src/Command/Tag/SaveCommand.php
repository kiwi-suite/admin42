<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Admin42\Command\Tag;

use Admin42\Model\Tag;
use Admin42\TableGateway\TagTableGateway;
use Core42\Command\AbstractCommand;
use Core42\Stdlib\DateTime;

class SaveCommand extends AbstractCommand
{
    protected $tags;

    /**
     * @param mixed $tags
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function execute()
    {
        $tagsTableGateway = $this->getTableGateway(TagTableGateway::class);

        $data = explode(',', $this->tags);
        foreach ($data as $tag) {
            $result = $tagsTableGateway->select(['tag' => $tag]);
            if ($result->count() == 0) {
                $newTag = new Tag();
                $newTag->setTag($tag);
                $newTag->setCreated(new DateTime());
                $tagsTableGateway->insert($newTag);
            }
        }
    }
}
