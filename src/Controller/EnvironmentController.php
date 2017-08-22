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


namespace Admin42\Controller;

use Admin42\Mvc\Controller\AbstractAdminController;
use Zend\Json\Json;

class EnvironmentController extends AbstractAdminController
{
    /**
     * @return array|mixed
     */
    public function indexAction()
    {
        $revision = [];
        if (\file_exists('resources/version/revision.json')) {
            try {
                $revision = Json::decode(\file_get_contents('resources/version/revision.json'), Json::TYPE_ARRAY);
            } catch (\Exception $e) {
            }
        }

        $packages = [];
        if (\file_exists('resources/version/packages.json')) {
            try {
                $packages = Json::decode(\file_get_contents('resources/version/packages.json'), Json::TYPE_ARRAY);
            } catch (\Exception $e) {
            }
        }

        return [
            'revision' => $revision,
            'packages' => $packages,
            'ini'      => \ini_get_all(),
        ];
    }
}
