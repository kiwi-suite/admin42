<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\FormElements;

use Zend\Form\Element\Hidden;

class Link extends Hidden
{
    /**
     * @var array
     */
    protected $linkTypes;

    /**
     * @var array
     */
    protected $attributes = [
        'type' => 'link',
    ];

    /**
     * @param array $linkTypes
     */
    public function setLinkTypes(array $linkTypes)
    {
        $this->linkTypes = $linkTypes;
    }

    /**
     * @return array
     */
    public function getLinkTypes()
    {
        return $this->linkTypes;
    }
}
