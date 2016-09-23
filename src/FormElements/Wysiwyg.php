<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\FormElements;

use Zend\Form\Element;
use Zend\Stdlib\ArrayUtils;

class Wysiwyg extends Element  implements AngularAwareInterface
{
    use ElementTrait;

    protected $editorOptions = [
        'trusted' => true,
        'format' => 'raw',
        'height' => 300,
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
    public function handleExtraOptions($options)
    {
        if (!empty($options['editorOptions'])) {
            $this->setEditorOptions($options['editorOptions']);
        }
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
