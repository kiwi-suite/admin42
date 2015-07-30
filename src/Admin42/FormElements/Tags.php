<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\FormElements;

use Zend\Form\Element\Select;
use Zend\Form\Element\Text;

class Tags extends Text
{
    /**
     * @var array
     */
    protected $attributes = array(
        'type' => 'tags',
    );
}
