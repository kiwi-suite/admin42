<?php
class Migration20161010142303
{

    public function up(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "CREATE TABLE `admin42_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `displayName` varchar(255) DEFAULT NULL,
  `shortName` varchar(2) NOT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `status` enum('inactive','active','locked') NOT NULL,
  `payload` longtext,
  `lastLogin` datetime DEFAULT NULL,
  `locale` varchar(10) NOT NULL DEFAULT 'en-US',
  `timezone` varchar(100) NOT NULL DEFAULT 'UTC',
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `hash_UNIQUE` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        $sql = "CREATE TABLE `admin42_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash_UNIQUE` (`hash`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    public function down(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "DROP TABLE `admin42_user`";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        $sql = "DROP TABLE `admin42_link`";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }


}
