<?php
namespace Admin42\View\Helper\Form;

use Admin42\View\Helper\Angular;
use Ramsey\Uuid\Uuid;
use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;

class FormInput extends AbstractHelper
{

    /**
     *
     * @var string
     */
    protected $defaultHelper = 'formInput';

    /**
     * @param ElementInterface|null $element
     * @return $this|string
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * @param ElementInterface $element
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $type = $this->getType($element);
        $this
            ->getAngularHelper()
            ->addHtmlPartial(
                'element/form/'.strtolower($type).'.html',
                'partial/admin42/form/'.$type
            );

        $angularDirective = $this->getAngularDirective($element);
        return sprintf(
            '<%s json-cache-id="%s"></%s>',
            $angularDirective,
            $this->getAngularHelper()->generateJsonTemplate(
                $this->getElementData($element),
                'element/form/value/'
            ),
            $angularDirective
        );
    }

    /**
     * @param ElementInterface $element
     * @return string
     */
    public function getAngularDirective(ElementInterface $element)
    {
        return 'form-' . strtolower($this->getType($element));
    }

    /**
     * @param ElementInterface $element
     * @return mixed|string
     */
    public function getValue(ElementInterface $element)
    {
        $value = $element->getValue();
        if (!is_string($value)) {
            $value = "";
        }

        return $value;
    }

    /**
     * @param ElementInterface $element
     * @return array
     */
    public function getElementData(ElementInterface $element)
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
            'required' => $element->hasAttribute("required"),
            'options' => $element->getOptions(),
            'attributes' => $element->getAttributes(),
            'errors' => array_values($element->getMessages()),
        ];
    }

    /**
     * @param ElementInterface $element
     * @return string
     */
    protected function getType(ElementInterface $element)
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
