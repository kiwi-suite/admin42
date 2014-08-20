<?php
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
     * @var array
     */
    private $attributes = array();

    /**
     * @var array
     */
    private $decorators = array();

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
     * @return array
     */
    public function getDecorators()
    {
        return $this->decorators;
    }

    /**
     * @param array $decorators
     */
    public function setDecorators($decorators)
    {
        $this->decorators = $decorators;
    }

    /**
     * @param callable $decorator
     */
    public function addDecorator($decorator)
    {
        $this->decorators[] = $decorator;
    }

    /**
     *
     */
    public function applyDecorators()
    {
        foreach ($this->decorators as $decorator)
        {
            $decorator($this);
        }
    }
}
