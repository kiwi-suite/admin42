<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\FormElements;

use Zend\Form\Element;

class Link extends Element implements AngularAwareInterface
{
    use ElementTrait;
    /**
     * @var array
     */
    protected $linkTypes;

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

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        return $this;
    }
}
