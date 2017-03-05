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


namespace Admin42\View\Helper;

use Ramsey\Uuid\Uuid;
use Zend\Form\ElementInterface;
use Zend\Json\Json;
use Zend\View\Helper\AbstractHelper;

class Angular extends AbstractHelper
{
    /**
     * @var array
     */
    protected $jsonTemplates = [];

    /**
     * @var array
     */
    protected $htmlTemplates = [];

    /**
     * @var array
     */
    protected $htmlPartials = [];

    /**
     * @var array
     */
    protected $config = [];

    /**
     * Angular constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function angularBootstrap()
    {
        /** @var Admin $adminHelper */
        $adminHelper = $this->view->plugin('admin');

        $appConfig = [
            'locale' => \Locale::getDefault(),
            'defaultDateTimeFormat' => 'LLL',
            'timezone' => \date_default_timezone_get(),
            'displayTimezone' => $adminHelper->getTimezone(),
        ];

        return 'var APP;'
        . 'angular.element(document).ready(function(){'
        . "APP = angular.module('APP', " . Json::encode($this->config['modules']) . ');'
        . "APP.constant('appConfig', " . Json::encode($appConfig) . ');'
        . "angular.bootstrap(document, ['APP']);"
        . '});' . PHP_EOL;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasJsonTemplate($name)
    {
        return isset($this->jsonTemplates[$name]);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param bool $force
     * @return $this
     */
    public function addJsonTemplate($name, $value, $force = true)
    {
        if ($force === false && $this->hasJsonTemplate($name)) {
            return $this;
        }

        $this->jsonTemplates[$name] = $value;

        return $this;
    }

    /**
     * @param mixed $value
     * @param string $prefix
     * @return string
     */
    public function generateJsonTemplate($value, $prefix = '')
    {
        $name = $prefix . Uuid::uuid4()->toString() . '.json';
        $this->addJsonTemplate($name, $value);

        return $name;
    }

    /**
     * @return string
     */
    public function getJsonTemplates()
    {
        $templates = [];

        foreach ($this->jsonTemplates as $name => $value) {
            $templates[] = \sprintf(
                '<script id="%s" type="application/json" nonce="%s">%s</script>',
                $name,
                $this->view->plugin('csp')->getNonce(),
                Json::encode($value)
            );
        }

        return \implode(PHP_EOL, $templates);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasHtmlTemplate($name)
    {
        return isset($this->htmlTemplates[$name]);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasHtmlPartial($name)
    {
        return isset($this->htmlPartials[$name]);
    }

    /**
     * @param string $name
     * @param string $html
     * @param bool $force
     * @return $this
     */
    public function addHtmlTemplate($name, $html, $force = true)
    {
        if ($force === false && $this->hasHtmlTemplate($name)) {
            return $this;
        }

        $this->htmlTemplates[$name] = $html;

        return $this;
    }

    /**
     * @param $name
     * @param $partial
     * @param bool $force
     * @return $this
     */
    public function addHtmlPartial($name, $partial, $force = false)
    {
        if ($force === false && $this->hasHtmlPartial($name)) {
            return $this;
        }

        $this->htmlPartials[$name] = $partial;

        return $this;
    }

    /**
     * @return string
     */
    public function getHtmlTemplates()
    {
        $templates = [];

        foreach ($this->htmlTemplates as $name => $html) {
            $templates[] = \sprintf(
                '<script id="%s" type="text/ng-template" nonce="%s">%s</script>',
                $name,
                $this->view->plugin('csp')->getNonce(),
                $html
            );
        }

        if (!empty($this->htmlPartials)) {
            $partialHelper = $this->view->plugin('partial');
            foreach ($this->htmlPartials as $name => $partial) {
                $templates[] = \sprintf(
                    '<script id="%s" type="text/ng-template" nonce="%s">%s</script>',
                    $name,
                    $this->view->plugin('csp')->getNonce(),
                    $partialHelper($partial)
                );
            }
        }

        return \implode(PHP_EOL, $templates);
    }

    /**
     * @param ElementInterface $element
     * @param mixed $value
     * @return array
     */
    public function prepareFormArray(ElementInterface $element, $value)
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
            'value' => $value,
            'required' => $element->hasAttribute('required'),
            'options' => $element->getOptions(),
            'attributes' => $element->getAttributes(),
            'errors' => \array_values($element->getMessages()),
        ];
    }
}
