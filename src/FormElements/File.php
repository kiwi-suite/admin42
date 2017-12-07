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

namespace Admin42\FormElements;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;

class File extends Element implements InputProviderInterface, AngularAwareInterface
{
    use ElementTrait;

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInput()}.
     *
     * @return array
     */
    public function getInputSpecification()
    {
        return [
            'type'     => 'Zend\InputFilter\FileInput',
            'name'     => $this->getName(),
            'required' => $this->isRequired(),
        ];
    }

}
