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


namespace Admin42\FormElements;

use Zend\Form\Element;
use Zend\Stdlib\ArrayUtils;

class Wysiwyg extends Element implements AngularAwareInterface
{
    use ElementTrait;

    protected $editorOptions = [
        'trusted' => true,
        'height' => 300,
        'plugins' => 'paste autolink lists charmap table code link42',
        'menubar' => false,
        'toolbar' => 'undo redo paste | styleselect | bold italic | link42 unlink42 | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | table code | ',
        'skin' => 'lightgray',
        'theme' => 'modern',
        'elementpath' => false,
        'convert_urls' => false,
        'resize' => true,
    ];

    /**
     * @param array|\Traversable $options
     * @return \Zend\Form\Element|\Zend\Form\ElementInterface
     */
    public function setOptions($options)
    {
        if (!empty($options['editorOptions'])) {
            $this->setEditorOptions($options['editorOptions']);
        }

        return $this;
    }

    /**
     * @param $editorOptions
     * @param bool $merge
     * @return $this
     */
    public function setEditorOptions($editorOptions, $merge = true)
    {
        if ($merge == true) {
            $editorOptions = ArrayUtils::merge($editorOptions, $this->editorOptions);
        }
        $this->editorOptions = $editorOptions;

        return $this;
    }

    /**
     * @return array
     */
    public function getEditorOptions()
    {
        return $this->editorOptions;
    }
}
