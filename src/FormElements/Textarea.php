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

namespace Admin42\FormElements;

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
