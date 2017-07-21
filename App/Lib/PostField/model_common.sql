-- 主表
CREATE TABLE `$basic_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siteid` smallint(5) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT 0,
  `creator` char(20) NOT NULL DEFAULT 0,
  `template` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `inputtime` timestamp NOT NULL DEFAULT '1970-01-01 08:00:01',
  `updatetime` timestamp NOT NULL DEFAULT '1970-01-01 08:00:01',
  PRIMARY KEY (`id`),
  KEY `listorder` (`listorder`)
) TYPE=InnoDB;

INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`, `iscore`, `issystem`,  `isunique`, `isbase`, `issearch`, `isadd`, `listorder`, `disabled`, `isomnipotent`, `islist`) VALUES($modelid, $siteid, 'title', '标题', '', 'inputtitle', 1, 80, '', '请输入标题', 'title', '', '', 0, 1, 0, 1, 1, 1, 0, 0, 0, 1);

INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`, `iscore`, `issystem`, `isunique`, `isbase`, `issearch`, `isadd`, `listorder`, `disabled`, `isomnipotent`) VALUES($modelid, $siteid, 'creator', '创建人', '', '', 0, 0, '', '', 'text', '', '', 1, 1, 0, 1, 0, 0, 23, 0, 0);

INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`, `iscore`, `issystem`, `isunique`, `isbase`, `issearch`, `isadd`, `listorder`, `disabled`, `isomnipotent`) VALUES($modelid, $siteid, 'template', '模板', '', '', 0, 0, '', '', 'text', '', '', 1, 1, 0, 1, 0, 0, 23, 0, 0);

INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`, `iscore`, `issystem`, `isunique`, `isbase`, `issearch`, `isadd`, `listorder`, `disabled`, `isomnipotent`) VALUES($modelid, $siteid, 'updatetime', '更新时间', '', '', 0, 0, '', '', 'datetime', 'array (\r\n  ''dateformat'' => ''datetime'',\r\n  ''format'' => ''Y-m-d H:i:s'',\r\n  ''defaulttype'' => ''0'',\r\n  ''defaultvalue'' => '''',\r\n)', '', 1, 1, 0, 1, 0, 0, 22, 0, 0);

INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`, `iscore`, `issystem`, `isunique`, `isbase`, `issearch`, `isadd`, `listorder`, `disabled`, `isomnipotent`) VALUES($modelid, $siteid, 'inputtime', '发布时间', '', '', 0, 0, '', '', 'datetime', 'array (\n  ''fieldtype'' => ''datetime'',\n  ''format'' => ''Y-m-d H:i:s'',\n  ''defaulttype'' => ''0'',\n)', '', 0, 1, 0, 0, 0, 0, 21, 0, 0);

INSERT INTO `$table_model_field` (`modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`, `iscore`, `issystem`, `isunique`, `isbase`, `issearch`, `isadd`, `listorder`, `disabled`, `isomnipotent`) VALUES($modelid, $siteid, 'listorder', '排序', '', '', 0, 6, '', '', 'number', '', '', 1, 1, 0, 1, 0, 0, 51, 0, 0);