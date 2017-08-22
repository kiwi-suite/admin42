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


namespace Admin42\Link\Adapter;

class ExternalLink implements AdapterInterface
{
    /**
     * @param mixed $value
     * @param array $options
     * @return string
     */
    public function assemble($value, $options = [])
    {
        return $this->getLinkData($value);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function getDisplayName($value)
    {
        return $this->getLinkData($value);
    }

    /**
     * @param $value
     * @return string
     */
    protected function getLinkData($value)
    {
        if (empty($value['url'])) {
            return '';
        }

        $value['url'] = \str_replace('http://', '', $value['url']);
        $value['url'] = \str_replace('https://', '', $value['url']);
        $value['url'] = \str_replace('mailto:', '', $value['url']);

        if (empty($value['type'])) {
            $value['type'] = 'http://';
        }

        return $value['type'] . $value['url'];
    }

    /**
     * @return array
     */
    public function getPartials()
    {
        return [
            'link/external.html' => 'link/external',
        ];
    }
}
