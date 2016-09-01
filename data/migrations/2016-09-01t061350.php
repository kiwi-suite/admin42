<?php
class Migration20160901061350
{

    public function up(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "ALTER TABLE `admin42_user` 
ADD COLUMN `payload` LONGTEXT NOT NULL AFTER `status`";

        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        $sql = "UPDATE `admin42_user` SET payload = '[]'";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    public function down(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "ALTER TABLE `admin42_user` 
DROP COLUMN `payload`";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }
}
