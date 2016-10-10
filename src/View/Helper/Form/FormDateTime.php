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

class FormDateTime extends FormHelper
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
                $value = new \DateTime($value);
                $value = $value->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                $value = '';
            }
        } elseif ($value instanceof \DateTime) {
            $value->setTimezone(new \DateTimeZone('UTC'));
            $value = $value->format('Y-m-d H:i:s');
        } else {
            $value = '';
        }

        return $value;
    }
}
