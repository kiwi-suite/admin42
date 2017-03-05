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
