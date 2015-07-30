<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\FormElements;

class DateTime extends \Zend\Form\Element\DateTime
{
    /**
     * @var string
     */
    protected $format = 'Y-m-d H:iP';

    /**
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => false,
            'filters' => [
                ['name' => 'Zend\Filter\StringTrim'],
            ],
            'validators' => $this->getValidators(),
        ];
    }
}
