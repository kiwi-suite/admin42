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


namespace Admin42\View\Helper\Form;

use Admin42\FormElements\AngularAwareInterface;
use Admin42\View\Helper\Angular;
use Ramsey\Uuid\Uuid;
use Zend\View\Helper\AbstractHelper;

class FormHelper extends AbstractHelper implements AngularHelperInterface
{
    /**
     * @var string
     */
    protected $defaultHelper = 'formHelper';

    /**
     * @param AngularAwareInterface|null $element
     * @return $this|string
     */
    public function __invoke(AngularAwareInterface $element = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * @param AngularAwareInterface $element
     * @return string
     */
    public function render(AngularAwareInterface $element)
    {
        $angularDirective = $this->getAngularDirective($element);

        return \sprintf(
            '<%s element-data-id="%s" template="%s"></%s>',
            $angularDirective,
            $this->getAngularHelper()->generateJsonTemplate(
                $this->getElementData($element, false),
                'element/form/value/'
            ),
            $this->getTemplate($element),
            $angularDirective
        );
    }

    /**
     * @param AngularAwareInterface $element
     * @return string
     */
    public function getAngularDirective(AngularAwareInterface $element)
    {
        return 'form-' . \mb_strtolower($this->getType($element));
    }

    /**
     * @param AngularAwareInterface $element
     * @return mixed|string
     */
    public function getValue(AngularAwareInterface $element)
    {
        $value = $element->getValue();
        if (!\is_scalar($value)) {
            $value = '';
        }

        return $value;
    }

    /**
     * @param AngularAwareInterface $element
     * @param bool $angularNameRendering
     * @return array
     */
    public function getElementData(AngularAwareInterface $element, $angularNameRendering = true)
    {
        $translateHelper = $this->getView()->plugin('translate');

        $label = $element->getLabel();
        if (!empty($label)) {
            $label = $translateHelper($label, 'admin');
        }

        return [
            'id' => 'form-' . Uuid::uuid4()->toString(),
            'name' => $element->getName(),
            'label' => $label,
            'value' => $this->getValue($element),
            'required' => $element->isRequired(),
            'description' => $element->getDescription(),
            'readonly' => $element->isReadonly(),
            'template' => $this->getTemplate($element),
            'options' => $element->getOptions(),
            'errors' => \array_values($element->getMessages()),
            'angularNameRendering' => $angularNameRendering,
        ];
    }

    /**
     * @param string $template
     * @param string $partial
     */
    public function addElementTemplate($template, $partial)
    {
        $this
            ->getAngularHelper()
            ->addHtmlPartial(
                $template,
                $partial
            );
    }

    protected function getTemplate(AngularAwareInterface $element)
    {
        $template = $element->getTemplate();

        if (empty($template)) {
            $template = 'partial/admin42/form/' . \mb_strtolower($this->getType($element));
        }

        $templateName = 'element/form/' . \md5($template) . '.html';
        $this->addElementTemplate($templateName, $template);

        return $templateName;
    }

    /**
     * @param AngularAwareInterface $element
     * @return string
     */
    protected function getType(AngularAwareInterface $element)
    {
        return (new \ReflectionClass($element))->getShortName();
    }

    /**
     * @return Angular
     */
    protected function getAngularHelper()
    {
        return $this->getView()->plugin('angular');
    }
}
