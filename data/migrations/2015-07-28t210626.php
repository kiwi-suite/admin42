<?php
class Migration20150728210626
{

    public function up(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "ALTER TABLE `admin42_user` ADD COLUMN `shortName` VARCHAR(2) NOT NULL COMMENT '' AFTER `displayName`";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    public function down(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "ALTER TABLE `admin42_user` DROP COLUMN `shortName`";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }


}
