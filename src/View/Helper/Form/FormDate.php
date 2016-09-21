<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\View\Helper\Form;

use Zend\Form\ElementInterface;

class FormDate extends FormHelper
{
    public function getValue(ElementInterface $element)
    {
        $value = $element->getValue(false);
        if (empty($value)) {
            $value = "";
        } elseif (is_string($value)) {
            try {
                $value = new \DateTime($value);
                $value->setTimezone(new \DateTimeZone($this->admin()->getDisplayTimezone()));
                $value = $value->format("Y-m-d");
            } catch (\Exception $e) {
                $value = "";
            }
        } elseif ($value instanceof \DateTime) {
            $value->setTimezone(new \DateTimeZone($this->admin()->getDisplayTimezone()));
            $value = $value->format("Y-m-d");
        } else {
            $value = "";
        }

        return $value;
    }
}
