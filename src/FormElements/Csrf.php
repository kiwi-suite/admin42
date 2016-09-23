<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\FormElements;

class Csrf extends \Zend\Form\Element\Csrf implements AngularAwareInterface
{
    use ElementTrait;

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        if (isset($options['csrf_options'])) {
            $this->setCsrfValidatorOptions($options['csrf_options']);
        }

        return $this;
    }
}
