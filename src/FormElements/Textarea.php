<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\FormElements;

use Zend\Filter\StringTrim;
use Zend\Form\Element;
use Zend\Validator\StringLength;

class Textarea extends Text implements AngularAwareInterface
{
    /**
     * @var int
     */
    protected $rows = 5;

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        parent::setOptions($options);

        if (!empty($options['rows'])) {
            $this->setRows((int) $options['rows']);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param int $rows
     * @return Textarea
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
        return $this;
    }
}
