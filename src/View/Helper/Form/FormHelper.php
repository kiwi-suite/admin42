<?php
namespace Admin42\View\Helper\Form;

use Admin42\FormElements\AngularAwareInterface;
use Admin42\View\Helper\Angular;
use Ramsey\Uuid\Uuid;
use Zend\View\Helper\AbstractHelper;

class FormHelper extends AbstractHelper implements AngularHelperInterface
{

    /**
     *
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
        $this->addElementTemplate($element);

        $angularDirective = $this->getAngularDirective($element);
        return sprintf(
            '<%s element-data-id="%s"></%s>',
            $angularDirective,
            $this->getAngularHelper()->generateJsonTemplate(
                $this->getElementData($element, false),
                'element/form/value/'
            ),
            $angularDirective
        );
    }

    /**
     * @param AngularAwareInterface $element
     * @return string
     */
    public function getAngularDirective(AngularAwareInterface $element)
    {
        return 'form-' . strtolower($this->getType($element));
    }

    /**
     * @param AngularAwareInterface $element
     * @return mixed|string
     */
    public function getValue(AngularAwareInterface $element)
    {
        $value = $element->getValue();
        if (!is_string($value)) {
            $value = "";
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
            'id' => 'form-'.Uuid::uuid4()->toString(),
            'name' => $element->getName(),
            'label' => $label,
            'value' => $this->getValue($element),
            'required' => $element->isRequired(),
            'description' => $element->getDescription(),
            'readonly' => $element->isReadonly(),
            'template' => $this->getTemplate($element),
            'options' => $element->getOptions(),
            'errors' => array_values($element->getMessages()),
            'angularNameRendering' => $angularNameRendering,
        ];
    }

    public function addElementTemplate(AngularAwareInterface $element)
    {
        $type = $this->getType($element);
        $this
            ->getAngularHelper()
            ->addHtmlPartial(
                'element/form/'.strtolower($type).'.html',
                'partial/admin42/form/'.$type
            );
    }

    protected function getTemplate(AngularAwareInterface $element)
    {

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
