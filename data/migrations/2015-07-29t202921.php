<?php
class Migration20150729202921
{

    public function up(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "ALTER TABLE `admin42_media` ADD COLUMN `category` VARCHAR(255) NOT NULL DEFAULT 'default' COMMENT '' AFTER `filename`";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    public function down(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "ALTER TABLE `admin42_media` DROP COLUMN `category`";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }


}
