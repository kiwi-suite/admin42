<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Command\Tag;

use Admin42\Model\Tag;
use Core42\Command\AbstractCommand;

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
        $tagsTableGateway = $this->getTableGateway('Admin42\Tag');

        $data = explode(',', $this->tags);
        foreach ($data as $tag) {
            $result = $tagsTableGateway->select(['tag' => $tag]);
            if ($result->count() == 0) {
                $newTag = new Tag();
                $newTag->setTag($tag);
                $tagsTableGateway->insert($newTag);
            }
        }
    }
}
