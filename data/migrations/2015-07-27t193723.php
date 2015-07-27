<?php
class Migration20150727193723
{

    public function up(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "ALTER TABLE `admin42_media` CHANGE COLUMN `title` `title` VARCHAR(255) NULL DEFAULT NULL COMMENT ''";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

    }

    public function down(Zend\ServiceManager\ServiceManager $serviceManager)
    {

    }


}
