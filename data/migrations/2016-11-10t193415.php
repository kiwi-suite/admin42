<?php
class Migration20161110193415
{

    public function up(\Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $sql = "CREATE TABLE `admin42_user_loginhistory` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `userId` INT UNSIGNED NOT NULL,
  `status` ENUM('success', 'fail') NOT NULL,
  `ip` VARCHAR(45) NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `admin42_user_idx` (`userId` ASC),
  CONSTRAINT `admin42_user`
    FOREIGN KEY (`userId`)
    REFERENCES `admin42_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;";
        $serviceManager->get('Db\Master')->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

    }

    public function down(\Zend\ServiceManager\ServiceManager $serviceManager)
    {

    }
}
