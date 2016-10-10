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

namespace Admin42\View\Helper\Form;

use Admin42\FormElements\AngularAwareInterface;

class FormDate extends FormHelper
{
    /**
     * @param AngularAwareInterface $element
     * @return \DateTime|mixed|string
     */
    public function getValue(AngularAwareInterface $element)
    {
        $value = $element->getValue(false);
        if (empty($value)) {
            $value = '';
        } elseif (is_string($value)) {
            try {
                $adminHelper = $this->getView()->plugin('admin');
                $value = new \DateTime($value);
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
