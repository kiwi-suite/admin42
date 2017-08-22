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

namespace Admin42\Stdlib;

use Admin42\Link\LinkProvider;

class Wysiwyg
{
    /**
     * @var string
     */
    protected $originalContent;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var LinkProvider
     */
    private $linkProvider;

    /**
     * Wysiwyg constructor.
     * @param LinkProvider $linkProvider
     * @param string $originalContent
     */
    public function __construct(LinkProvider $linkProvider, $originalContent)
    {
        $this->originalContent = $originalContent;
        $this->linkProvider = $linkProvider;
    }

    public function getContent()
    {
        if ($this->content === null) {
            $this->content = \preg_replace_callback(
                '/<a(.*?)href="###([0-9]+)###"(.*?)>/i',
                function ($matches) {
                    $target = "_self";
                    $href = $this->linkProvider->assembleById((int) $matches[2]);
                    $link = $this->linkProvider->getLink((int) $matches[2]);
                    if ($link instanceof \Admin42\Model\Link && $link->getType() == "external") {
                        $target = "_blank";
                    }
                    return '<a' . $matches[1] . 'href="' . $href . '" target="' . $target . '" ' . $matches[3] . '>';
                },
                $this->originalContent
            );

            if (empty($this->content)) {
                $this->content = "";
            }
        }

        return $this->content;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getContent();
    }
}
