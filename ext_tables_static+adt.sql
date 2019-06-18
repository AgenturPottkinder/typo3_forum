# TYPO3 Extension Manager dump 1.1
#
# Host: localhost    Database: typo3_forum
#--------------------------------------------------------


#
# Table structure for table "tx_typo3forum_domain_model_format_textparser"
#
DROP TABLE IF EXISTS tx_typo3forum_domain_model_format_textparser;
CREATE TABLE tx_typo3forum_domain_model_format_textparser (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  type varchar(64) NOT NULL default 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\BBCode',
  name tinytext,
  icon_class tinytext,
  bbcode_wrap varchar(64) default '',
  regular_expression tinytext,
  regular_expression_replacement tinytext,
  smiley_shortcut varchar(16) default '',
  language varchar(16) default '',
  tstamp int(11) unsigned NOT NULL default '0',
  crdate int(11) unsigned NOT NULL default '0',
  deleted tinyint(4) unsigned NOT NULL default '0',
  hidden tinyint(4) unsigned NOT NULL default '0',
  PRIMARY KEY (uid),
  KEY parent (pid)
);


INSERT INTO tx_typo3forum_domain_model_format_textparser
 (`uid`, `pid`, `type`, `name`, `icon_class`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `smiley_shortcut`, `tstamp`, `crdate`, `deleted`, `hidden`)
VALUES
 ('1', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\BBCode', 'Bold', 'tx-typo3forum-miu-bold', '[b]|[/b]', '/\\[b\\](.*)\\[\\/b\\]/i', '<b>\\1</b>', NULL, '1288879482', '1284727514', '0', '0'),
 ('2', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\BBCode', 'Italic', 'tx-typo3forum-miu-italic', '[i]|[/i]', '/\\[i\\](.*)\\[\\/i\\]/i', '<i>\\1</i>', NULL, '1288879482', '1284727514', '0', '0'),
 ('3', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\QuoteBBCode', 'Quote', 'tx-typo3forum-miu-quote', '', NULL, NULL, NULL, '1288180606', '1288180540', '0', '0'),
 ('4', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\BBCode', 'Image', 'tx-typo3forum-miu-picture', '[img]|[/img]', '/\\[img\\](.*)\\[\\/img\\]/i', '<img src="\\1" />', NULL, '1288879482', '1288183634', '0', '0'),
 ('5', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\Smiley', 'Smile', 'smile.gif', NULL, NULL, NULL, ':)', '1288879482', '1288184040', '0', '0'),
 ('6', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\Smiley', 'Wink', 'wink.gif', NULL, NULL, NULL, ';)', '1288879482', '1288188000', '0', '0'),
 ('7', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\Smiley', 'Neutral', 'neutral.gif', NULL, NULL, NULL, ':|', '1288879482', '1288188066', '0', '0'),
 ('8', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\Smiley', 'Angry', 'mad.gif', NULL, NULL, NULL, '>:(', '1288879482', '1288188107', '0', '0'),
 ('9', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\Smiley', 'Sad', 'sad.gif', NULL, NULL, NULL, ':(', '1288879482', '1288188126', '0', '0'),
 ('10', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\Smiley', 'Confused', 'confused.gif', NULL, NULL, NULL, ':/', '1288879482', '1288188156', '0', '0'),
 ('11', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\ListBBCode', 'Unordered List', 'tx-typo3forum-miu-olist', NULL, NULL, NULL, NULL, '1288248587', '1288248572', '0', '0'),
 ('12', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\SyntaxHighlighting', 'PHP', 'software-php.png', NULL, NULL, NULL, 'php', '1288865100', '1288274212', '0', '0');



# TYPO3 Extension Manager dump 1.1
#
# Host: localhost    Database: typo3_forum
#--------------------------------------------------------


#
# Table structure for table "tx_typo3forum_domain_model_moderation_reportworkflowstatus"
#
DROP TABLE IF EXISTS tx_typo3forum_domain_model_moderation_reportworkflowstatus;
CREATE TABLE tx_typo3forum_domain_model_moderation_reportworkflowstatus (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  name tinytext,
  icon tinytext,
  followup_status int(11) unsigned NOT NULL default '0',
  initial tinyint(1) NOT NULL default '0',
  final tinyint(1) NOT NULL default '0',
  tstamp int(11) unsigned NOT NULL default '0',
  crdate int(11) unsigned NOT NULL default '0',
  deleted tinyint(4) unsigned NOT NULL default '0',
  hidden tinyint(4) unsigned NOT NULL default '0',
  PRIMARY KEY (uid),
  KEY parent (pid)
);

INSERT INTO tx_typo3forum_domain_model_moderation_reportworkflowstatus
VALUES
 ('1', '0', 'New', 'Status-New-16.png', '2', '1', '0', '1288789972', '1285078017', '0', '0'),
 ('2', '0', 'On hold', 'Status-OnHold-16.png', '1', '0', '0', '1288789972', '1285078026', '0', '0'),
 ('3', '0', 'In progress', 'Status-InProgress-16.png', '2', '0', '0', '1288789972', '1285078034', '0', '0'),
 ('4', '0', 'Closed', 'Status-Closed-16.png', '0', '0', '1', '1288789972', '1285078039', '0', '0');


# TYPO3 Extension Manager dump 1.1
#
# Host: localhost    Database: typo3_forum
#--------------------------------------------------------


#
# Table structure for table "tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm"
#
DROP TABLE IF EXISTS tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm;
CREATE TABLE tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm (
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


INSERT INTO tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm VALUES ('1', '0', '1', '2', '1', '0', '0', '0', '0');
INSERT INTO tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm VALUES ('2', '0', '1', '3', '2', '0', '0', '0', '0');
INSERT INTO tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm VALUES ('3', '0', '2', '3', '1', '0', '0', '0', '0');
INSERT INTO tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm VALUES ('4', '0', '3', '4', '1', '0', '0', '0', '0');
INSERT INTO tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm VALUES ('5', '0', '3', '2', '2', '0', '0', '0', '0');


#
# Table structure for table "tx_typo3forum_domain_model_user_userfield_userfield"
#
DROP TABLE IF EXISTS tx_typo3forum_domain_model_user_userfield_userfield;
CREATE TABLE tx_typo3forum_domain_model_user_userfield_userfield (
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
  PRIMARY KEY (uid),
  KEY parent (pid)
);


INSERT INTO tx_typo3forum_domain_model_user_userfield_userfield VALUES
 ('2', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\User\\Userfield\\TextUserfield', 'Telephone', NULL, 'telephone', '1288345900', '1288345828', '0', '0'),
 ('3', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\User\\Userfield\\TyposcriptUserfield', 'Country', 'plugin.tx_typo3forum.userfields.country', 'staticInfoCountry', '1288692309', '1288691535', '0', '0'),
 ('4', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\User\\Userfield\\TyposcriptUserfield', 'Gender', 'plugin.tx_typo3forum.userfields.gender', 'gender', '1288694065', '1288694054', '0', '0'),
 ('5', '0', 'Mittwald\\Typo3Forum\\Domain\\Model\\User\\Userfield\\TextUserfield', 'City', NULL, 'zip|city', '1288694371', '1288694361', '0', '0');
