<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Admin42\FormElements;

use Zend\Form\ElementInterface;

interface AngularAwareInterface extends ElementInterface
{
    /**
     * @return bool
     */
    public function isReadonly();

    /**
     * @param bool $readonly
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
     * @return bool
     */
    public function isRequired();

    /**
     * @param bool $required
     * @return ElementTrait
     */
    public function setRequired($required);
}
