-- 主表
CREATE TABLE `$basic_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=InnoDB;


INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`, `iscore`, `issystem`, `isunique`, `isbase`, `issearch`, `isadd`, `listorder`, `disabled`, `isomnipotent`) VALUES($modelid, $siteid, 'template', '模板', '', '', 0, 0, '', '', 'text', '', '', 1, 1, 0, 1, 0, 0, 23, 0, 0);
