<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
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
            $value = "";
        } elseif (is_string($value)) {
            try {
                $value = new \DateTime($value);
                $value = $value->format("Y-m-d H:i:s");
            } catch (\Exception $e) {
                $value = "";
            }
        } elseif ($value instanceof \DateTime) {
            $value->setTimezone(new \DateTimeZone('UTC'));
            $value = $value->format("Y-m-d H:i:s");
        } else {
            $value = "";
        }

        return $value;
    }
}
