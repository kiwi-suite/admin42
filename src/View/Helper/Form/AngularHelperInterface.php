<?php
namespace Admin42\View\Helper\Form;

use Admin42\FormElements\AngularAwareInterface;

interface AngularHelperInterface
{
    /**
     * @param AngularAwareInterface $element
     * @return string
     */
    public function getAngularDirective(AngularAwareInterface $element);

    /**
     * @param AngularAwareInterface $element
     * @return mixed
     */
    public function getValue(AngularAwareInterface $element);

    /**
     * @param AngularAwareInterface $element
     * @param bool $angularNameRendering
     * @return array
     */
    public function getElementData(AngularAwareInterface $element, $angularNameRendering = true);

    /**
     * @param string $template
     * @param string $partial
     */
    public function addElementTemplate($template, $partial);
}
