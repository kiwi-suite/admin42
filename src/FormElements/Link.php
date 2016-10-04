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
     * @var
     */
    protected $allLinkTypes;

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
     * @param array $allLinkTypes
     */
    public function setAllLinkTypes(array $allLinkTypes)
    {
        $this->allLinkTypes = $allLinkTypes;
    }

    /**
     * @return array
     */
    public function getAllLinkTypes()
    {
        return $this->allLinkTypes;
    }

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        if (isset($options['linkTypes'])) {
            $this->setLinkTypes($options['linkTypes']);
        }
        return $this;
    }
}
