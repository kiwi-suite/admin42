<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\FormElements;

class Select extends \Zend\Form\Element\Select implements AngularAwareInterface
{
    use ElementTrait;

    public function handleExtraOptions($options)
    {
        if (isset($options['selectOptions'])) {
            $this->setValueOptions($options['selectOptions']);
        }
    }
}
