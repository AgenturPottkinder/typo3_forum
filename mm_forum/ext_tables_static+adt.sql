# TYPO3 Extension Manager dump 1.1
#
# Host: localhost    Database: mm_forum
#--------------------------------------------------------


#
# Table structure for table "tx_mmforum_domain_model_format_textparser"
#
DROP TABLE IF EXISTS tx_mmforum_domain_model_format_textparser;
CREATE TABLE tx_mmforum_domain_model_format_textparser (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  type varchar(64) NOT NULL default 'Tx_MmForum_Domain_Model_Format_BBCode',
  name tinytext,
  icon tinytext,
  bbcode_wrap varchar(64) default '',
  regular_expression tinytext,
  regular_expression_replacement tinytext,
  smilie_shortcut varchar(16) default '',
  language varchar(16) default '',
  tstamp int(11) unsigned NOT NULL default '0',
  crdate int(11) unsigned NOT NULL default '0',
  deleted tinyint(4) unsigned NOT NULL default '0',
  hidden tinyint(4) unsigned NOT NULL default '0',
  t3ver_oid int(11) NOT NULL default '0',
  t3ver_id int(11) NOT NULL default '0',
  t3ver_wsid int(11) NOT NULL default '0',
  t3ver_label varchar(30) NOT NULL default '',
  t3ver_state tinyint(4) NOT NULL default '0',
  t3ver_stage tinyint(4) NOT NULL default '0',
  t3ver_count int(11) NOT NULL default '0',
  t3ver_tstamp int(11) NOT NULL default '0',
  t3_origuid int(11) NOT NULL default '0',
  sys_language_uid int(11) NOT NULL default '0',
  l18n_parent int(11) NOT NULL default '0',
  l18n_diffsource mediumblob NOT NULL,
  PRIMARY KEY (uid),
  KEY parent (pid)
);


INSERT INTO tx_mmforum_domain_model_format_textparser VALUES ('1', '0', 'Tx_MmForum_Domain_Model_Format_BBCode', 'Bold', 'Bold.png', '[b]|[/b]', '/\\[b\\](.*)\\[\\/b\\]/i', '<b>\\1</b>', NULL, NULL, '1288879482', '1284727514', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:5:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:18:"regular_expression";N;s:30:"regular_expression_replacement";N;}');
INSERT INTO tx_mmforum_domain_model_format_textparser VALUES ('2', '0', 'Tx_MmForum_Domain_Model_Format_BBCode', 'Italic', 'Italic.png', '[i]|[/i]', '/\\[i\\](.*)\\[\\/i\\]/i', '<i>\\1</i>', NULL, NULL, '1288879482', '1284727514', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:5:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:18:"regular_expression";N;s:30:"regular_expression_replacement";N;}');
INSERT INTO tx_mmforum_domain_model_format_textparser VALUES ('3', '0', 'Tx_MmForum_Domain_Model_Format_QuoteBBCode', 'Quotation', 'Quote.png', '', NULL, NULL, NULL, NULL, '1288180606', '1288180540', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:3:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;}');
INSERT INTO tx_mmforum_domain_model_format_textparser VALUES ('4', '0', 'Tx_MmForum_Domain_Model_Format_BBCode', 'Image', 'Image.png', '[img]|[/img]', '/\\[img\\](.*)\\[\\/img\\]/i', '<img src="\\1" />', NULL, NULL, '1288879482', '1288183634', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:6:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:11:"bbcode_wrap";N;s:18:"regular_expression";N;s:30:"regular_expression_replacement";N;}');
INSERT INTO tx_mmforum_domain_model_format_textparser VALUES ('5', '0', 'Tx_MmForum_Domain_Model_Format_Smilie', 'Smile', 'smile.gif', NULL, NULL, NULL, ':)', NULL, '1288879482', '1288184040', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:15:"smilie_shortcut";N;}');
INSERT INTO tx_mmforum_domain_model_format_textparser VALUES ('6', '0', 'Tx_MmForum_Domain_Model_Format_Smilie', 'Wink', 'wink.gif', NULL, NULL, NULL, ';)', NULL, '1288879482', '1288188000', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:15:"smilie_shortcut";N;}');
INSERT INTO tx_mmforum_domain_model_format_textparser VALUES ('7', '0', 'Tx_MmForum_Domain_Model_Format_Smilie', 'Neutral', 'neutral.gif', NULL, NULL, NULL, ':|', NULL, '1288879482', '1288188066', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:15:"smilie_shortcut";N;}');
INSERT INTO tx_mmforum_domain_model_format_textparser VALUES ('8', '0', 'Tx_MmForum_Domain_Model_Format_Smilie', 'Angry', 'mad.gif', NULL, NULL, NULL, '>:(', NULL, '1288879482', '1288188107', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:15:"smilie_shortcut";N;}');
INSERT INTO tx_mmforum_domain_model_format_textparser VALUES ('9', '0', 'Tx_MmForum_Domain_Model_Format_Smilie', 'Sad', 'sad.gif', NULL, NULL, NULL, ':(', NULL, '1288879482', '1288188126', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:15:"smilie_shortcut";N;}');
INSERT INTO tx_mmforum_domain_model_format_textparser VALUES ('10', '0', 'Tx_MmForum_Domain_Model_Format_Smilie', 'Confused', 'confused.gif', NULL, NULL, NULL, ':/', NULL, '1288879482', '1288188156', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:15:"smilie_shortcut";N;}');
INSERT INTO tx_mmforum_domain_model_format_textparser VALUES ('11', '0', 'Tx_MmForum_Domain_Model_Format_ListBBCode', 'Unordered List', 'List_Unordered.png', NULL, NULL, NULL, NULL, NULL, '1288248587', '1288248572', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:3:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;}');
INSERT INTO tx_mmforum_domain_model_format_textparser VALUES ('12', '0', 'Tx_MmForum_Domain_Model_Format_SyntaxHighlighting', 'PHP', 'software-php.png', NULL, NULL, NULL, NULL, 'php', '1288865100', '1288274212', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:8:"language";N;}');



# TYPO3 Extension Manager dump 1.1
#
# Host: localhost    Database: mm_forum
#--------------------------------------------------------


#
# Table structure for table "tx_mmforum_domain_model_moderation_reportworkflowstatus"
#
DROP TABLE IF EXISTS tx_mmforum_domain_model_moderation_reportworkflowstatus;
CREATE TABLE tx_mmforum_domain_model_moderation_reportworkflowstatus (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  name tinytext,
  followup_status int(11) unsigned NOT NULL default '0',
  initial tinyint(1) NOT NULL default '0',
  tstamp int(11) unsigned NOT NULL default '0',
  crdate int(11) unsigned NOT NULL default '0',
  deleted tinyint(4) unsigned NOT NULL default '0',
  hidden tinyint(4) unsigned NOT NULL default '0',
  t3ver_oid int(11) NOT NULL default '0',
  t3ver_id int(11) NOT NULL default '0',
  t3ver_wsid int(11) NOT NULL default '0',
  t3ver_label varchar(30) NOT NULL default '',
  t3ver_state tinyint(4) NOT NULL default '0',
  t3ver_stage tinyint(4) NOT NULL default '0',
  t3ver_count int(11) NOT NULL default '0',
  t3ver_tstamp int(11) NOT NULL default '0',
  t3_origuid int(11) NOT NULL default '0',
  sys_language_uid int(11) NOT NULL default '0',
  l18n_parent int(11) NOT NULL default '0',
  l18n_diffsource mediumblob NOT NULL,
  PRIMARY KEY (uid),
  KEY parent (pid)
);


INSERT INTO tx_mmforum_domain_model_moderation_reportworkflowstatus VALUES ('1', '0', 'New', '2', '1', '1288789972', '1285078017', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:2:{s:4:"name";N;s:15:"followup_status";N;}');
INSERT INTO tx_mmforum_domain_model_moderation_reportworkflowstatus VALUES ('2', '0', 'On hold', '1', '0', '1288789972', '1285078026', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:2:{s:4:"name";N;s:15:"followup_status";N;}');
INSERT INTO tx_mmforum_domain_model_moderation_reportworkflowstatus VALUES ('3', '0', 'In progress', '2', '0', '1288789972', '1285078034', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:2:{s:4:"name";N;s:15:"followup_status";N;}');
INSERT INTO tx_mmforum_domain_model_moderation_reportworkflowstatus VALUES ('4', '0', 'Closed', '0', '0', '1288789972', '1285078039', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:2:{s:4:"name";N;s:15:"followup_status";N;}');


# TYPO3 Extension Manager dump 1.1
#
# Host: localhost    Database: mm_forum
#--------------------------------------------------------


#
# Table structure for table "tx_mmforum_domain_model_moderation_reportworkflowstatus_followup"
#
DROP TABLE IF EXISTS tx_mmforum_domain_model_moderation_reportworkflowstatus_followup;
CREATE TABLE tx_mmforum_domain_model_moderation_reportworkflowstatus_followup (
  uid int(10) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  uid_local int(11) unsigned NOT NULL default '0',
  uid_foreign int(11) unsigned NOT NULL default '0',
  sorting int(11) unsigned NOT NULL default '0',
  sorting_foreign int(11) unsigned NOT NULL default '0',
  tstamp int(10) unsigned NOT NULL default '0',
  crdate int(10) unsigned NOT NULL default '0',
  hidden tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);


INSERT INTO tx_mmforum_domain_model_moderation_reportworkflowstatus_followup VALUES ('1', '0', '1', '2', '1', '0', '0', '0', '0');
INSERT INTO tx_mmforum_domain_model_moderation_reportworkflowstatus_followup VALUES ('2', '0', '1', '3', '2', '0', '0', '0', '0');
INSERT INTO tx_mmforum_domain_model_moderation_reportworkflowstatus_followup VALUES ('3', '0', '2', '3', '1', '0', '0', '0', '0');
INSERT INTO tx_mmforum_domain_model_moderation_reportworkflowstatus_followup VALUES ('4', '0', '3', '4', '1', '0', '0', '0', '0');
INSERT INTO tx_mmforum_domain_model_moderation_reportworkflowstatus_followup VALUES ('5', '0', '3', '2', '2', '0', '0', '0', '0');


#
# Table structure for table "tx_mmforum_domain_model_user_userfield_userfield"
#
DROP TABLE IF EXISTS tx_mmforum_domain_model_user_userfield_userfield;
CREATE TABLE tx_mmforum_domain_model_user_userfield_userfield (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  type tinytext,
  name tinytext,
  typoscript_path tinytext,
  map_to_user_object varchar(64) default '',
  tstamp int(11) unsigned NOT NULL default '0',
  crdate int(11) unsigned NOT NULL default '0',
  deleted tinyint(4) unsigned NOT NULL default '0',
  hidden tinyint(4) unsigned NOT NULL default '0',
  t3ver_oid int(11) NOT NULL default '0',
  t3ver_id int(11) NOT NULL default '0',
  t3ver_wsid int(11) NOT NULL default '0',
  t3ver_label varchar(30) NOT NULL default '',
  t3ver_state tinyint(4) NOT NULL default '0',
  t3ver_stage tinyint(4) NOT NULL default '0',
  t3ver_count int(11) NOT NULL default '0',
  t3ver_tstamp int(11) NOT NULL default '0',
  t3_origuid int(11) NOT NULL default '0',
  sys_language_uid int(11) NOT NULL default '0',
  l18n_parent int(11) NOT NULL default '0',
  l18n_diffsource mediumblob NOT NULL,
  PRIMARY KEY (uid),
  KEY parent (pid)
);


INSERT INTO tx_mmforum_domain_model_user_userfield_userfield VALUES ('2', '0', 'Tx_MmForum_Domain_Model_User_Userfield_TextUserfield', 'Telephone', NULL, 'telephone', '1288345900', '1288345828', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:3:{s:4:"type";N;s:4:"name";N;s:18:"map_to_user_object";N;}');
INSERT INTO tx_mmforum_domain_model_user_userfield_userfield VALUES ('3', '0', 'Tx_MmForum_Domain_Model_User_Userfield_TyposcriptUserfield', 'Country', 'plugin.tx_mmforum.userfields.country', 'staticInfoCountry', '1288692309', '1288691535', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:4:{s:4:"type";N;s:4:"name";N;s:15:"typoscript_path";N;s:18:"map_to_user_object";N;}');
INSERT INTO tx_mmforum_domain_model_user_userfield_userfield VALUES ('4', '0', 'Tx_MmForum_Domain_Model_User_Userfield_TyposcriptUserfield', 'Gender', 'plugin.tx_mmforum.userfields.gender', 'gender', '1288694065', '1288694054', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:4:{s:4:"type";N;s:4:"name";N;s:15:"typoscript_path";N;s:18:"map_to_user_object";N;}');
INSERT INTO tx_mmforum_domain_model_user_userfield_userfield VALUES ('5', '0', 'Tx_MmForum_Domain_Model_User_Userfield_TextUserfield', 'City', NULL, 'zip|city', '1288694371', '1288694361', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', 'a:3:{s:4:"type";N;s:4:"name";N;s:18:"map_to_user_object";N;}');
