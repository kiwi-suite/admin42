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
