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
