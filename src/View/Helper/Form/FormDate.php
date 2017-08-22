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
use Core42\Stdlib\Date;

class FormDate extends FormHelper
{
    /**
     * @param AngularAwareInterface $element
     * @return string
     */
    public function getValue(AngularAwareInterface $element)
    {
        $value = $element->getValue(false);
        if (empty($value)) {
            $value = '';
        } elseif (\is_string($value)) {
            try {
                $adminHelper = $this->getView()->plugin('admin');
                $value = new Date($value);
                $value->setTimezone(new \DateTimeZone($adminHelper->getTimezone()));
                $value = $value->format('Y-m-d');
            } catch (\Exception $e) {
                $value = '';
            }
        } elseif ($value instanceof \DateTime) {
            $adminHelper = $this->getView()->plugin('admin');
            $value->setTimezone(new \DateTimeZone($adminHelper->getTimezone()));
            $value = $value->format('Y-m-d');
        } else {
            $value = '';
        }

        return $value;
    }
}
