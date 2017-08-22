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
