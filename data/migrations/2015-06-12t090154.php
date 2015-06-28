<?php
class Migration20150612090154
{

    public function up(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "CREATE TABLE `admin42_media` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `directory` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `mimeType` varchar(255) NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    public function down(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "DROP TABLE `admin42_media`";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }


}
