# TYPO3 Extension Manager dump 1.1
#
# Host: localhost    Database: mm_forum
#--------------------------------------------------------


#
# Table structure for table "tx_mmforum_domain_model_format_textparser"
#
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


#
# Table structure for table "tx_mmforum_domain_model_forum_access"
#
CREATE TABLE tx_mmforum_domain_model_forum_access (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  login_level tinyint(4) unsigned default '0',
  forum int(11) unsigned default '0',
  operation tinytext,
  negate tinyint(1) unsigned NOT NULL default '0',
  affected_group int(11) unsigned default '0',
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


#
# Table structure for table "tx_mmforum_domain_model_forum_attachment"
#
CREATE TABLE tx_mmforum_domain_model_forum_attachment (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  post int(11) unsigned default '0',
  filename tinytext,
  mime_type tinytext,
  download_count int(11) unsigned NOT NULL default '0',
  tstamp int(11) unsigned NOT NULL default '0',
  crdate int(11) unsigned NOT NULL default '0',
  deleted tinyint(4) unsigned NOT NULL default '0',
  hidden tinyint(4) unsigned NOT NULL default '0',
  PRIMARY KEY (uid),
  KEY parent (pid)
);


#
# Table structure for table "tx_mmforum_domain_model_forum_forum"
#
CREATE TABLE tx_mmforum_domain_model_forum_forum (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  forum int(11) unsigned NOT NULL default '0',
  title tinytext,
  description tinytext,
  children int(11) unsigned NOT NULL default '0',
  topics int(11) unsigned NOT NULL default '0',
  acls int(11) unsigned NOT NULL default '0',
  last_topic int(11) unsigned default '0',
  last_post int(11) unsigned default '0',
  subscribers int(11) unsigned default '0',
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


#
# Table structure for table "tx_mmforum_domain_model_forum_post"
#
CREATE TABLE tx_mmforum_domain_model_forum_post (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  topic int(11) unsigned NOT NULL default '0',
  text text,
  author int(11) unsigned default '0',
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
  attachments int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (uid),
  KEY parent (pid)
);


#
# Table structure for table "tx_mmforum_domain_model_forum_topic"
#
CREATE TABLE tx_mmforum_domain_model_forum_topic (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  type tinyint(1) NOT NULL default '0',
  forum int(11) unsigned NOT NULL default '0',
  subject tinytext,
  posts int(11) unsigned NOT NULL default '0',
  author int(11) unsigned default '0',
  subscribers int(11) unsigned default '0',
  last_post int(11) unsigned default '0',
  closed tinyint(1) unsigned default '0',
  sticky tinyint(1) unsigned default '0',
  target int(10) unsigned default '',
  readers int(11) unsigned NOT NULL default '0',
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


#
# Table structure for table "tx_mmforum_domain_model_moderation_report"
#
CREATE TABLE tx_mmforum_domain_model_moderation_report (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  post int(11) unsigned NOT NULL default '0',
  reporter int(11) unsigned NOT NULL default '0',
  moderator int(11) unsigned default '',
  workflow_status int(11) unsigned default '',
  comments int(11) unsigned NOT NULL default '0',
  tstamp int(11) unsigned NOT NULL default '0',
  crdate int(11) unsigned NOT NULL default '0',
  deleted tinyint(4) unsigned NOT NULL default '0',
  hidden tinyint(4) unsigned NOT NULL default '0',
  PRIMARY KEY (uid),
  KEY parent (pid)
);


#
# Table structure for table "tx_mmforum_domain_model_moderation_reportcomment"
#
CREATE TABLE tx_mmforum_domain_model_moderation_reportcomment (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  report int(11) unsigned NOT NULL default '0',
  author int(11) unsigned NOT NULL default '0',
  text text,
  tstamp int(11) unsigned NOT NULL default '0',
  crdate int(11) unsigned NOT NULL default '0',
  deleted tinyint(4) unsigned NOT NULL default '0',
  hidden tinyint(4) unsigned NOT NULL default '0',
  PRIMARY KEY (uid),
  KEY parent (pid)
);


#
# Table structure for table "tx_mmforum_domain_model_moderation_reportworkflowstatus"
#
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


#
# Table structure for table "tx_mmforum_domain_model_moderation_reportworkflowstatus_followup"
#
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


#
# Table structure for table "tx_mmforum_domain_model_user_forumsubscription"
#
CREATE TABLE tx_mmforum_domain_model_user_forumsubscription (
  uid int(10) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  uid_local int(11) unsigned NOT NULL default '0',
  uid_foreign int(11) unsigned NOT NULL default '0',
  tablenames varchar(255) NOT NULL default '',
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


#
# Table structure for table "tx_mmforum_domain_model_user_readtopic"
#
CREATE TABLE tx_mmforum_domain_model_user_readtopic (
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


#
# Table structure for table "tx_mmforum_domain_model_user_topicsubscription"
#
CREATE TABLE tx_mmforum_domain_model_user_topicsubscription (
  uid int(10) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  uid_local int(11) unsigned NOT NULL default '0',
  uid_foreign int(11) unsigned NOT NULL default '0',
  tablenames varchar(255) NOT NULL default '',
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


#
# Table structure for table "tx_mmforum_domain_model_user_userfield_userfield"
#
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


#
# Table structure for table "tx_mmforum_domain_model_user_userfield_value"
#
CREATE TABLE tx_mmforum_domain_model_user_userfield_value (
  uid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  user int(11) unsigned NOT NULL default '0',
  userfield int(11) unsigned NOT NULL default '0',
  value text,
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


#
# Table structure for table "fe_users"
#
CREATE TABLE fe_users (
  tx_mmforum_post_count int(11) NOT NULL default '0',
  tx_mmforum_topic_subscriptions int(11) unsigned NOT NULL default '0',
  tx_mmforum_forum_subscriptions int(11) unsigned default '0',
  tx_mmforum_signature text,
  tx_mmforum_userfield_values int(11) unsigned NOT NULL default '0'
);
