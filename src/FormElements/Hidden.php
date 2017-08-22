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

class Hidden extends Element implements AngularAwareInterface
{
    use ElementTrait;

    /**
     * @var bool
     */
    protected $staticControl = false;

    /**
     * @var string
     */
    protected $staticControlText = '';

    /**
     * @param array|\Traversable $options
     * @return $this
     */
    public function setOptions($options)
    {
        if (\array_key_exists('staticControl', $options)) {
            $this->setStaticControl((bool) $options['staticControl']);
        }

        if (!empty($options['staticControlText'])) {
            $this->setStaticControlText($options['staticControlText']);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function getStaticControl()
    {
        return $this->staticControl;
    }

    /**
     * @param bool $staticControl
     * @return Hidden
     */
    public function setStaticControl($staticControl)
    {
        $this->staticControl = $staticControl;
        return $this;
    }

    /**
     * @return string
     */
    public function getStaticControlText()
    {
        return $this->staticControlText;
    }

    /**
     * @param string $staticControlText
     * @return Hidden
     */
    public function setStaticControlText($staticControlText)
    {
        $this->staticControlText = $staticControlText;
        return $this;
    }
}
