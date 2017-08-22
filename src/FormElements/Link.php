<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/kiwi-suite/admin42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <tech@kiwi-suite.com>
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
     * @var array
     */
    protected $linkPartials = [];

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
     * @param array $linkPartials
     * @return $this
     */
    public function setLinkPartials(array $linkPartials)
    {
        $this->linkPartials = $linkPartials;

        return $this;
    }

    /**
     * @param null|string $name
     * @return array
     */
    public function getLinkPartial($name = null)
    {
        if ($name === null) {
            return $this->linkPartials;
        }

        if (!isset($this->linkPartials[$name])) {
            return [];
        }

        return $this->linkPartials[$name];
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
