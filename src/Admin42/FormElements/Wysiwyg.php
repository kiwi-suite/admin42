<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\FormElements;

use Zend\Form\Element\Textarea;
use Zend\Stdlib\ArrayUtils;

class Wysiwyg extends Textarea
{
    /**
     * @var array
     */
    protected $attributes = [
        'type' => 'wysiwyg',
    ];

    protected $editorOptions = [
        'trusted' => true,
        'format' => 'raw',
        'height' => 400,
        'plugins'=> 'paste autolink lists charmap table code link',
        'menubar'=> false,
        'toolbar'=> 'undo redo paste | styleselect | bold italic | link unlink | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | table code | ',
        'skin' => 'lightgray',
        'theme' => 'modern',
        'elementpath' => false,
        'convert_urls'=> false,
        'resize'=> true,
    ];

    /**
     * @param array|\Traversable $options
     * @return \Zend\Form\Element|\Zend\Form\ElementInterface
     */
    public function setOptions($options)
    {
        $return = parent::setOptions($options);

        if (!empty($options['editor_options'])) {
            $this->setEditorOptions($options['editor_options']);
        }

        return $return;
    }

    /**
     * @param $editorOptions
     * @return $this
     */
    public function setEditorOptions($editorOptions)
    {
        $this->editorOptions = ArrayUtils::merge($editorOptions, $this->editorOptions);

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
