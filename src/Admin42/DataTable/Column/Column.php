<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\DataTable\Column;

use Zend\Stdlib\AbstractOptions;

class Column extends AbstractOptions
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $matchName;

    /**
     * @var bool
     */
    private $sortable = false;

    /**
     * @var bool
     */
    private $searchable = false;

    /**
     * @var callable
     */
    private $mutator;

    /**
     * @var callable
     */
    private $decorator;

    /**
     * @var array
     */
    private $attributes = array();

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return boolean
     */
    public function isSearchable()
    {
        return $this->searchable;
    }

    /**
     * @param boolean $searchable
     */
    public function setSearchable($searchable)
    {
        $this->searchable = $searchable;
    }

    /**
     * @return boolean
     */
    public function isSortable()
    {
        return $this->sortable;
    }

    /**
     * @param boolean $sortable
     */
    public function setSortable($sortable)
    {
        $this->sortable = $sortable;
    }

    /**
     * @return string
     */
    public function getMatchName()
    {
        return $this->matchName;
    }

    /**
     * @param string $matchName
     */
    public function setMatchName($matchName)
    {
        $this->matchName = $matchName;
    }

    /**
     * @return callable
     */
    public function getMutator()
    {
        return $this->mutator;
    }

    /**
     * @param callable $mutator
     */
    public function setMutator($mutator)
    {
        $this->mutator = $mutator;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function addAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @return callable
     */
    public function getDecorator()
    {
        return $this->decorator;
    }

    /**
     * @param callable $decorator
     */
    public function setDecorator($decorator)
    {
        $this->decorator = $decorator;
    }
}
