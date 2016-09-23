<?php
namespace Admin42\FormElements;

use Zend\Form\ElementInterface;

interface AngularAwareInterface extends ElementInterface
{
    /**
     * @return boolean
     */
    public function isReadonly();

    /**
     * @param boolean $readonly
     * @return ElementTrait
     */
    public function setReadonly($readonly);

    /**
     * @return string
     */
    public function getTemplate();

    /**
     * @param string $template
     * @return ElementTrait
     */
    public function setTemplate($template);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     * @return ElementTrait
     */
    public function setDescription($description);

    /**
     * @return boolean
     */
    public function isRequired();

    /**
     * @param boolean $required
     * @return ElementTrait
     */
    public function setRequired($required);
}
