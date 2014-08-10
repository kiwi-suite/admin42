<?php

class Migration20140804110052
{

    public function up(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "CREATE TABLE `admin42_user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_bin' NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `displayName` VARCHAR(255) NULL,
  `hash` VARCHAR(255) NULL,
  `status` ENUM('inactive', 'active', 'locked') NOT NULL,
  `lastLogin` DATETIME NULL,
  `updated` DATETIME NOT NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `hash_UNIQUE` (`hash` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    public function down(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "DROP TABLE `admin42_user`";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }


}
