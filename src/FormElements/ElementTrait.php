<?php
namespace Admin42\FormElements;

use Zend\Form\Exception\InvalidArgumentException;
use Zend\Stdlib\ArrayUtils;

trait ElementTrait
{
    /**
     * @var boolean
     */
    protected $readonly;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var boolean
     */
    protected $required;

    /**
     * @return boolean
     */
    public function isReadonly()
    {
        return $this->readonly;
    }

    /**
     * @param boolean $readonly
     * @return ElementTrait
     */
    public function setReadonly($readonly)
    {
        $this->readonly = $readonly;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     * @return ElementTrait
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return ElementTrait
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @param boolean $required
     * @return ElementTrait
     */
    public function setRequired($required)
    {
        $this->required = $required;
        return $this;
    }
}
