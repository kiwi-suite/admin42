<?php
class Migration20150223175745
{

    public function up(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "CREATE TABLE `admin42_notification` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL,
  `text` varchar(255) NOT NULL,
  `route` varchar(255) DEFAULT NULL,
  `routeParams` varchar(1000) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fgk_notifiication_user_idx` (`userId`),
  CONSTRAINT `fgk_notifiication_user` FOREIGN KEY (`userId`) REFERENCES `admin42_user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    public function down(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "DROP TABLE `admin42_notification`";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }


}
