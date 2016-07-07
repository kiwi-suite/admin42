<?php
/**
 * admin42 (www.raum42.at)
 *
 * @link      http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Admin42\Controller;

use Admin42\Mvc\Controller\AbstractAdminController;

class FileDialogController extends AbstractAdminController
{
    /**
     * @return array
     */
    public function fileDialogAction()
    {
        $this->layout('admin/layout/dialog');

        $linkProvider = $this->getServiceManager()->get('Admin42\LinkProvider');

        return [
            'linkTypes' => $linkProvider->getAvailableAdapters(),
        ];
    }
}
