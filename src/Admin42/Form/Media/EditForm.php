<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Form\Media;

use Admin42\FormElements\File;
use Admin42\FormElements\Tags;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class EditForm extends Form
{
    /**
     *
     */
    public function init()
    {
        $title = new Text("title");
        $title->setLabel("label.title");
        $this->add($title);

        $description = new Textarea('description');
        $description->setLabel('label.description');
        $this->add($description);

        $tags = new Tags('keywords');
        $tags->setLabel('label.keywords');
        $this->add($tags);

        /*
        $tags = new File('file');
        $tags->setLabel('label.tags');
        $this->add($tags);
*/
    }
}
