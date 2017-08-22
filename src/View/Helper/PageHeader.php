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


namespace Admin42\View\Helper;

use Zend\View\Helper\AbstractHelper;

class PageHeader extends AbstractHelper
{
    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $icon;

    /**
     * @param string $title
     * @param string $icon
     * @return $this
     */
    public function __invoke($title = null, $icon = null)
    {
        if (!empty($title)) {
            $this->setTitle($title);
        }

        if (!empty($icon)) {
            $this->setIcon($icon);
        }

        return $this;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        $this->getView()->plugin('headTitle')->append($title);

        return $this;
    }

    /**
     * @param string $icon
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    public function __toString()
    {
        $partialHelper = $this->getView()->plugin('partial');

        return (string) $partialHelper('partial/admin42/page-header', [
            'title' => $this->title,
            'icon' => $this->icon,
        ]);
    }
}
