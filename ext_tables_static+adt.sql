DROP TABLE IF EXISTS tx_typo3forum_domain_model_format_textparser;
CREATE TABLE tx_typo3forum_domain_model_format_textparser (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  type varchar(64) NOT NULL default 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\BBCode',
  name tinytext,
  editor_icon_class tinytext,
  image_path tinytext,
  bbcode_wrap varchar(64) default '',
  regular_expression tinytext,
  regular_expression_replacement tinytext,
  regular_expression_replacement_blocked tinytext,
  alias varchar(255) default '',
  groups text DEFAULT NULL,
  tstamp int(11) NOT NULL default '0',
  crdate int(11) NOT NULL default '0',
  deleted tinyint(4) NOT NULL default '0',
  hidden tinyint(4) NOT NULL default '0',
  t3ver_oid int(11) NOT NULL default '0',
  t3ver_id int(11) NOT NULL default '0',
  t3ver_wsid int(11) NOT NULL default '0',
  t3ver_label varchar(30) NOT NULL default '',
  t3ver_state tinyint(4) NOT NULL default '0',
  t3ver_stage tinyint(4) NOT NULL default '0',
  t3ver_count int(11) NOT NULL default '0',
  t3ver_tstamp int(11) NOT NULL default '0',
  t3ver_move_id int(11) NOT NULL default '0',
  t3_origuid int(11) NOT NULL default '0',
  sys_language_uid int(11) NOT NULL default '0',
  l18n_parent int(11) NOT NULL default '0',
  l18n_diffsource mediumblob NOT NULL,
  PRIMARY KEY (uid),
  KEY parent (pid)
);

INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (1, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\BBCode', 'Bold', 'tx-typo3forum-miu-bold', NULL, '[b]|[/b]', '/\\[b\\](.*)\\[\\/b\\]/iU', '<b>\\1</b>', NULL, 'a:5:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:18:"regular_expression";N;s:30:"regular_expression_replacement";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (2, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\BBCode', 'Italic', 'tx-typo3forum-miu-italic', NULL, '[i]|[/i]', '/\\[i\\](.*)\\[\\/i\\]/iU', '<i>\\1</i>', NULL, 'a:5:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:18:"regular_expression";N;s:30:"regular_expression_replacement";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (3, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\BBCode', 'Underline', 'tx-typo3forum-miu-underline', NULL, '[u]|[/u]', '/\\[u\\](.*)\\[\\/u\\]/iU', '<u>\\1</u>', NULL, 'a:5:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:18:"regular_expression";N;s:30:"regular_expression_replacement";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (4, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\BBCode', 'Quote', 'tx-typo3forum-miu-quote', NULL, '[quote]|[/quote]', NULL, NULL, NULL, 'a:3:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (5, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\BBcode', 'Bullet List', 'tx-typo3forum-miu-ul', NULL, CONCAT('[list]', CHAR(13), '[*] |', CHAR(13), '[/list]'), NULL, NULL, NULL, 'a:3:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (6, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\BBCode', 'Link', 'tx-typo3forum-miu-link', NULL, '[url]|[/url]', NULL, NULL, NULL, 'a:6:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:11:"bbcode_wrap";N;s:18:"regular_expression";N;s:30:"regular_expression_replacement";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (7, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\BBCode', 'Image', 'tx-typo3forum-miu-picture', NULL, '[img]|[/img]', '/\\[img\\](.*)\\[\\/img\\]/iU', '<img src="\\1" />', NULL, 'a:6:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:11:"bbcode_wrap";N;s:18:"regular_expression";N;s:30:"regular_expression_replacement";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (8, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\Smiley', 'Smile', 'tx-typo3forum-miu-smile_smiley', 'EXT:typo3_forum/Resources/Public/Images/Icons/Smiley/smile.gif', NULL, NULL, NULL, ':)','a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:15:"alias";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (9, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\Smiley', 'Wink', 'tx-typo3forum-miu-wink_smiley', 'EXT:typo3_forum/Resources/Public/Images/Icons/Smiley/wink.gif', NULL, NULL, NULL, ';)', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:15:"alias";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (10, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\Smiley', 'Neutral', 'tx-typo3forum-miu-neutral_smiley', 'EXT:typo3_forum/Resources/Public/Images/Icons/Smiley/neutral.gif', NULL, NULL, NULL, ':|', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:15:"alias";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (11, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\Smiley', 'Angry', 'tx-typo3forum-miu-angry_smiley', 'EXT:typo3_forum/Resources/Public/Images/Icons/Smiley/mad.gif', NULL, NULL, NULL, '>:(', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:15:"alias";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (12, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\Smiley', 'Sad', 'tx-typo3forum-miu-sad_smiley', 'EXT:typo3_forum/Resources/Public/Images/Icons/Smiley/sad.gif', NULL, NULL, NULL, ':(', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:15:"alias";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (13, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\Smiley', 'Confused', 'tx-typo3forum-miu-confused_smiley', 'EXT:typo3_forum/Resources/Public/Images/Icons/Smiley/confused.gif', NULL, NULL, NULL, ':/', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:15:"alias";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (14, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\SyntaxHighlighting', 'C', 'tx-typo3forum-miu-code', NULL, NULL, NULL, NULL, 'c', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:8:"language";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (15, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\SyntaxHighlighting', 'C#', 'tx-typo3forum-miu-code', NULL, NULL, NULL, NULL, 'csharp', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:8:"language";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (16, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\SyntaxHighlighting', 'C++', 'tx-typo3forum-miu-code', NULL, NULL, NULL, NULL, 'cpp', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:8:"language";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (17, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\SyntaxHighlighting', 'Golang', 'tx-typo3forum-miu-code', NULL, NULL, NULL, NULL, 'go', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:8:"language";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (18, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\SyntaxHighlighting', 'Java', 'tx-typo3forum-miu-code', NULL, NULL, NULL, NULL, 'java', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:8:"language";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (19, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\SyntaxHighlighting', 'JavaScript', 'tx-typo3forum-miu-code', NULL, NULL, NULL, NULL, 'javascript', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:8:"language";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (20, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\SyntaxHighlighting', 'Kotlin', 'tx-typo3forum-miu-code', NULL, NULL, NULL, NULL, 'kotlin', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:8:"language";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (21, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\SyntaxHighlighting', 'PHP', 'tx-typo3forum-miu-code', NULL, NULL, NULL, NULL, 'php', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:8:"language";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (22, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\SyntaxHighlighting', 'Python', 'tx-typo3forum-miu-code', NULL, NULL, NULL, NULL, 'python', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:8:"language";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (23, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\SyntaxHighlighting', 'Ruby', 'tx-typo3forum-miu-code', NULL, NULL, NULL, NULL, 'ruby', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:8:"language";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (24, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\SyntaxHighlighting', 'SQL', 'tx-typo3forum-miu-code', NULL, NULL, NULL, NULL, 'sql', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:8:"language";N;}');
INSERT INTO tx_typo3forum_domain_model_format_textparser (`uid`, `pid`, `type`, `name`, `editor_icon_class`, `image_path`, `bbcode_wrap`, `regular_expression`, `regular_expression_replacement`, `alias`, `l18n_diffsource`)
  VALUES (25, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\Format\\SyntaxHighlighting', 'Swift', 'tx-typo3forum-miu-code', NULL, NULL, NULL, NULL, 'swift', 'a:4:{s:4:"type";N;s:4:"name";N;s:4:"icon";N;s:8:"language";N;}');


DROP TABLE IF EXISTS tx_typo3forum_domain_model_moderation_reportworkflowstatus;
CREATE TABLE tx_typo3forum_domain_model_moderation_reportworkflowstatus (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  name tinytext,
  followup_status int(11) NOT NULL default '0',
  initial tinyint(1) NOT NULL default '0',
  final tinyint(1) NOT NULL default '0',
  tstamp int(11) NOT NULL default '0',
  crdate int(11) NOT NULL default '0',
  deleted tinyint(4) NOT NULL default '0',
  hidden tinyint(4) NOT NULL default '0',
  t3ver_oid int(11) NOT NULL default '0',
  t3ver_id int(11) NOT NULL default '0',
  t3ver_wsid int(11) NOT NULL default '0',
  t3ver_label varchar(30) NOT NULL default '',
  t3ver_state tinyint(4) NOT NULL default '0',
  t3ver_stage tinyint(4) NOT NULL default '0',
  t3ver_count int(11) NOT NULL default '0',
  t3ver_tstamp int(11) NOT NULL default '0',
  t3ver_move_id int(11) NOT NULL default '0',
  t3_origuid int(11) NOT NULL default '0',
  sys_language_uid int(11) DEFAULT '0' NOT NULL,
  l18n_parent int(11) DEFAULT '0' NOT NULL,
  l18n_diffsource mediumblob,
  PRIMARY KEY (uid),
  KEY parent (pid)
);

INSERT INTO tx_typo3forum_domain_model_moderation_reportworkflowstatus (`uid`, `pid`, `name`, `followup_status`, `initial`, `final`)
  VALUES (1, 0, 'New',  2,  1,  0);
INSERT INTO tx_typo3forum_domain_model_moderation_reportworkflowstatus (`uid`, `pid`, `name`, `followup_status`, `initial`, `final`)
  VALUES (2, 0, 'On hold',  1,  0,  0);
INSERT INTO tx_typo3forum_domain_model_moderation_reportworkflowstatus (`uid`, `pid`, `name`, `followup_status`, `initial`, `final`)
  VALUES (3, 0, 'In progress',  2,  0,  0);
INSERT INTO tx_typo3forum_domain_model_moderation_reportworkflowstatus (`uid`, `pid`, `name`, `followup_status`, `initial`, `final`)
  VALUES (4, 0, 'Closed',  0,  0,  1);


DROP TABLE IF EXISTS tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm;
CREATE TABLE tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm (
  uid int(10) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  uid_local int(11) NOT NULL default '0',
  uid_foreign int(11) NOT NULL default '0',
  sorting int(11) NOT NULL default '0',
  sorting_foreign int(11) NOT NULL default '0',
  tstamp int(10) NOT NULL default '0',
  crdate int(10) NOT NULL default '0',
  hidden tinyint(3) NOT NULL default '0',
  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);

INSERT INTO tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm (`uid`, `pid`, `uid_local`, `uid_foreign`, `sorting`, `sorting_foreign`, `tstamp`, `crdate`, `hidden`)
  VALUES (1, 0, '1', '2', '1', '0', '0', '0', '0');
INSERT INTO tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm (`uid`, `pid`, `uid_local`, `uid_foreign`, `sorting`, `sorting_foreign`, `tstamp`, `crdate`, `hidden`)
  VALUES (2, 0, '1', '3', '2', '0', '0', '0', '0');
INSERT INTO tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm (`uid`, `pid`, `uid_local`, `uid_foreign`, `sorting`, `sorting_foreign`, `tstamp`, `crdate`, `hidden`)
  VALUES (3, 0, '2', '3', '1', '0', '0', '0', '0');
INSERT INTO tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm (`uid`, `pid`, `uid_local`, `uid_foreign`, `sorting`, `sorting_foreign`, `tstamp`, `crdate`, `hidden`)
  VALUES (4, 0, '3', '4', '1', '0', '0', '0', '0');
INSERT INTO tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm (`uid`, `pid`, `uid_local`, `uid_foreign`, `sorting`, `sorting_foreign`, `tstamp`, `crdate`, `hidden`)
  VALUES (5, 0, '3', '2', '2', '0', '0', '0', '0');


DROP TABLE IF EXISTS tx_typo3forum_domain_model_user_userfield_userfield;
CREATE TABLE tx_typo3forum_domain_model_user_userfield_userfield (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  type tinytext,
  name tinytext,
  typoscript_path tinytext,
  map_to_user_object varchar(64) default '',
  tstamp int(11) NOT NULL default '0',
  crdate int(11) NOT NULL default '0',
  deleted tinyint(4) NOT NULL default '0',
  hidden tinyint(4) NOT NULL default '0',
  t3ver_oid int(11) NOT NULL default '0',
  t3ver_id int(11) NOT NULL default '0',
  t3ver_wsid int(11) NOT NULL default '0',
  t3ver_label varchar(30) NOT NULL default '',
  t3ver_state tinyint(4) NOT NULL default '0',
  t3ver_stage tinyint(4) NOT NULL default '0',
  t3ver_count int(11) NOT NULL default '0',
  t3ver_tstamp int(11) NOT NULL default '0',
  t3ver_move_id int(11) NOT NULL default '0',
  t3_origuid int(11) NOT NULL default '0',
  sys_language_uid int(11) NOT NULL default '0',
  l18n_parent int(11) NOT NULL default '0',
  l18n_diffsource mediumblob,
  PRIMARY KEY (uid),
  KEY parent (pid)
);

INSERT INTO tx_typo3forum_domain_model_user_userfield_userfield (`uid`, `pid`, `type`, `name`, `typoscript_path`, `map_to_user_object`, `tstamp`, `crdate`, `deleted`, `hidden`, `t3ver_oid`, `t3ver_id`, `t3ver_wsid`, `t3ver_label`, `t3ver_state`, `t3ver_stage`, `t3ver_count`, `t3ver_tstamp`, `t3ver_move_id`, `t3_origuid`, `sys_language_uid`, `l18n_parent`, `l18n_diffsource`)
  VALUES (1, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\User\\Userfield\\TextUserfield', 'Telephone', NULL, 'telephone', '1288345900', '1288345828', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', '0', 'a:3:{s:4:"type";N;s:4:"name";N;s:18:"map_to_user_object";N;}');
INSERT INTO tx_typo3forum_domain_model_user_userfield_userfield (`uid`, `pid`, `type`, `name`, `typoscript_path`, `map_to_user_object`, `tstamp`, `crdate`, `deleted`, `hidden`, `t3ver_oid`, `t3ver_id`, `t3ver_wsid`, `t3ver_label`, `t3ver_state`, `t3ver_stage`, `t3ver_count`, `t3ver_tstamp`, `t3ver_move_id`, `t3_origuid`, `sys_language_uid`, `l18n_parent`, `l18n_diffsource`)
  VALUES (2, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\User\\Userfield\\TyposcriptUserfield', 'Country', 'plugin.tx_typo3forum.userfields.country', 'country', '1288692309', '1288691535', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', '0', 'a:4:{s:4:"type";N;s:4:"name";N;s:15:"typoscript_path";N;s:18:"map_to_user_object";N;}');
INSERT INTO tx_typo3forum_domain_model_user_userfield_userfield (`uid`, `pid`, `type`, `name`, `typoscript_path`, `map_to_user_object`, `tstamp`, `crdate`, `deleted`, `hidden`, `t3ver_oid`, `t3ver_id`, `t3ver_wsid`, `t3ver_label`, `t3ver_state`, `t3ver_stage`, `t3ver_count`, `t3ver_tstamp`, `t3ver_move_id`, `t3_origuid`, `sys_language_uid`, `l18n_parent`, `l18n_diffsource`)
  VALUES (3, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\User\\Userfield\\TyposcriptUserfield', 'Gender', 'plugin.tx_typo3forum.userfields.gender', 'gender', '1288694065', '1288694054', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', '0', 'a:4:{s:4:"type";N;s:4:"name";N;s:15:"typoscript_path";N;s:18:"map_to_user_object";N;}');
INSERT INTO tx_typo3forum_domain_model_user_userfield_userfield (`uid`, `pid`, `type`, `name`, `typoscript_path`, `map_to_user_object`, `tstamp`, `crdate`, `deleted`, `hidden`, `t3ver_oid`, `t3ver_id`, `t3ver_wsid`, `t3ver_label`, `t3ver_state`, `t3ver_stage`, `t3ver_count`, `t3ver_tstamp`, `t3ver_move_id`, `t3_origuid`, `sys_language_uid`, `l18n_parent`, `l18n_diffsource`)
  VALUES (4, 0, 'Mittwald\\Typo3Forum\\Domain\\Model\\User\\Userfield\\TextUserfield', 'City', NULL, 'zip|city', '1288694371', '1288694361', '0', '0', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', '0', 'a:3:{s:4:"type";N;s:4:"name";N;s:18:"map_to_user_object";N;}');

DROP TABLE IF EXISTS tx_typo3forum_domain_model_user_rank;
CREATE TABLE tx_typo3forum_domain_model_user_rank (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  deleted tinyint(3) NOT NULL default '0',
  name varchar(32) default '',
  point_limit int(11) NOT NULL default '0',
  user_count int(11) NOT NULL default '0',
  PRIMARY KEY (uid)
);

INSERT INTO tx_typo3forum_domain_model_user_rank (`uid`, `pid`, `name`, `point_limit`, `user_count`)
  VALUES (1, 0, 'Newbie', 10, 0);
INSERT INTO tx_typo3forum_domain_model_user_rank (`uid`, `pid`, `name`, `point_limit`, `user_count`)
  VALUES (2, 0, 'Rookie', 100, 0);
INSERT INTO tx_typo3forum_domain_model_user_rank (`uid`, `pid`, `name`, `point_limit`, `user_count`)
  VALUES (3, 0, 'Regular', 1000, 0);
INSERT INTO tx_typo3forum_domain_model_user_rank (`uid`, `pid`, `name`, `point_limit`, `user_count`)
  VALUES (4, 0, 'Veteran', 10000, 0);

DROP TABLE IF EXISTS tx_typo3forum_domain_model_forum_color;
CREATE TABLE tx_typo3forum_domain_model_forum_color (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default 0,
  name varchar(64) NOT NULL default '',
  primary_color char(7) NOT NULL default '',
  secondary_color char(7) NOT NULL default '',
  PRIMARY KEY (uid)
);

INSERT INTO tx_typo3forum_domain_model_forum_color (`uid`, `pid`, `name`, `primary_color`, `secondary_color`)
  VALUES (1, 0, 'Red', '#fff', '#ff3434');
INSERT INTO tx_typo3forum_domain_model_forum_color (`uid`, `pid`, `name`, `primary_color`, `secondary_color`)
  VALUES (2, 0, 'Orange', '#331206', '#f69522');
INSERT INTO tx_typo3forum_domain_model_forum_color (`uid`, `pid`, `name`, `primary_color`, `secondary_color`)
  VALUES (3, 0, 'Purple', '#fff', '#6344be');
INSERT INTO tx_typo3forum_domain_model_forum_color (`uid`, `pid`, `name`, `primary_color`, `secondary_color`)
  VALUES (4, 0, 'Magenta', '#fff', '#db3392');
INSERT INTO tx_typo3forum_domain_model_forum_color (`uid`, `pid`, `name`, `primary_color`, `secondary_color`)
  VALUES (5, 0, 'Green', '#fff', '#129b12');
INSERT INTO tx_typo3forum_domain_model_forum_color (`uid`, `pid`, `name`, `primary_color`, `secondary_color`)
  VALUES (6, 0, 'Yellow', '#462604', '#ffd502');
INSERT INTO tx_typo3forum_domain_model_forum_color (`uid`, `pid`, `name`, `primary_color`, `secondary_color`)
  VALUES (7, 0, 'Lime', '#164604', '#9fee02');
INSERT INTO tx_typo3forum_domain_model_forum_color (`uid`, `pid`, `name`, `primary_color`, `secondary_color`)
  VALUES (8, 0, 'Blue', '#fff', '#0748f0');
