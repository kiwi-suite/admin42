<?php
namespace Admin42\ModuleManager\Feature;

interface AdminAwareModuleInterface
{

    /**
     * @return array
     */
    public function getAdminStylesheets();

    /**
     * @return array
     */
    public function getAdminJavascript();

    /**
     * @return array
     */
    public function getAdminViewHelpers();


    /**
     * @return array
     */
    public function getAdminFormElements();

}
