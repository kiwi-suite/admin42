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
     * @param $options
     */
    public function setOptions($options)
    {
        if ($options instanceof \Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new InvalidArgumentException(
                'The options parameter must be an array or a Traversable'
            );
        }

        $readonly = (isset($options['readonly']) && $options['readonly'] === true);
        $this->setReadonly($readonly);
        unset($options['readonly']);

        $required = (isset($options['required']) && $options['required'] === true);
        $this->setRequired($required);
        unset($options['required']);

        if (isset($options['description'])) {
            $this->setDescription($options['description']);
            unset($options['description']);
        }

        if (isset($options['template'])) {
            $this->setTemplate($options['template']);
            unset($options['template']);
        }

        if (isset($options['label'])) {
            $this->setLabel($options['label']);
            unset($options['label']);
        }

        if (method_exists($this, 'handleExtraOptions')) {
            $this->handleExtraOptions($options);
        }
    }

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
