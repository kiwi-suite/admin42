<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\FormElements;

use Admin42\Filter\ToDateTime;
use Zend\Filter\StringTrim;
use Zend\Filter\ToNull;

class Date extends \Zend\Form\Element\Date
{
    /**
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => false,
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => ToNull::class],
                ['name' => ToDateTime::class],
            ],
            'validators' => $this->getValidators(),
        ];
    }
}
