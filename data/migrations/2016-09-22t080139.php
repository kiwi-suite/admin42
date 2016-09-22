<?php
class Migration20160922080139
{

    public function up(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "ALTER TABLE `admin42_user` 
ADD COLUMN `locale` VARCHAR(10) NOT NULL DEFAULT 'en-US' AFTER `lastLogin`,
ADD COLUMN `timezone` VARCHAR(100) NOT NULL DEFAULT 'UTC' AFTER `locale`";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    public function down(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "ALTER TABLE `admin42_user` 
DROP COLUMN `timezone`,
DROP COLUMN `locale`";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }


}
