<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/raum42/admin42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
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
                    $href = $this->linkProvider->assembleById((int) $matches[2]);
                    return '<a' . $matches[1] . 'href="' . $href . '"' . $matches[3] . '>';
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
