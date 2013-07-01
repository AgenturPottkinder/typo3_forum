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
  icon_class tinytext,
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
  real_filename tinytext,
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
  criteria int(11) unsigned NOT NULL default '0',
  topic_count int(11) unsigned NOT NULL default '0',
  post_count int(11) unsigned NOT NULL default '0',
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
  rendered_text blob,
  author int(11) unsigned default '0',
  author_name tinytext,
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
  supporters int(11) unsigned NOT NULL default '0',
  helpful_count int(11) unsigned NOT NULL default '0',
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
  post_count int(11) unsigned NOT NULL default '0',
  author int(11) unsigned default '0',
  subscribers int(11) unsigned default '0',
  fav_subscribers int(11) unsigned default '0',
  last_post int(11) unsigned default '0',
  last_post_crdate int(11) unsigned default '0',
  solution int(11) unsigned NOT NULL default '0',
  closed tinyint(1) unsigned default '0',
  sticky tinyint(1) unsigned default '0',
  target int(11) unsigned default '0',
  readers int(11) unsigned NOT NULL default '0',
  criteria_options int(11) unsigned NOT NULL default '0',
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
  moderator int(11) unsigned default '0',
  workflow_status int(11) unsigned default '0',
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
  final tinyint(1) NOT NULL default '0',
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
# Table structure for table "tx_mmforum_domain_model_user_supportpost"
#
CREATE TABLE tx_mmforum_domain_model_user_supportpost (
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
# Table structure for table "tx_mmforum_domain_model_user_topicfavsubscription"
#
CREATE TABLE tx_mmforum_domain_model_user_topicfavsubscription (
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
# Table structure for table 'tx_mmforum_domain_model_forum_criteria'
#
CREATE TABLE IF NOT EXISTS tx_mmforum_domain_model_forum_criteria (
  uid int(11) unsigned NOT NULL auto_increment,
  pid int(11) unsigned NOT NULL default '0',
  tstamp int(11) unsigned NOT NULL default '0',
  deleted tinyint(3) unsigned NOT NULL default '0',
  `name` varchar(64) NOT NULL,
  options int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (uid)
);


#
# Table structure for table 'tx_mmforum_domain_model_forum_criteria_forum'
#
CREATE TABLE tx_mmforum_domain_model_forum_criteria_forum (
	uid_local int(10) UNSIGNED DEFAULT '0' NOT NULL,
	uid_foreign int(10) UNSIGNED DEFAULT '0' NOT NULL,
	sorting int(10) UNSIGNED DEFAULT '0' NOT NULL,
	sorting_foreign int(10) UNSIGNED DEFAULT '0' NOT NULL,
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);


#
# Table structure for table 'tx_mmforum_domain_model_forum_criteria_options'
#
CREATE TABLE IF NOT EXISTS tx_mmforum_domain_model_forum_criteria_options (
  uid int(11) unsigned NOT NULL auto_increment,
  pid int(11) unsigned NOT NULL default '0',
  tstamp int(11) unsigned NOT NULL default '0',
  deleted tinyint(3) NOT NULL default '0',
  criteria int(11) unsigned NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY  (uid),
  KEY criteria (criteria)
);


#
# Table structure for table 'tx_mmforum_domain_model_forum_criteria_topic_options'
#
CREATE TABLE tx_mmforum_domain_model_forum_criteria_topic_options (
	uid_local int(10) UNSIGNED DEFAULT '0' NOT NULL,
	uid_foreign int(10) UNSIGNED DEFAULT '0' NOT NULL,
	sorting int(10) UNSIGNED DEFAULT '0' NOT NULL,
	sorting_foreign int(10) UNSIGNED DEFAULT '0' NOT NULL,
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);


#
# Table structure for table 'tx_mmforum_domain_model_forum_ads'
#
CREATE TABLE tx_mmforum_domain_model_forum_ads (
  uid int(11) unsigned NOT NULL auto_increment,
  pid int(11) unsigned NOT NULL default '0',
  tstamp int(11) unsigned NOT NULL default '0',
  deleted tinyint(3) NOT NULL default '0',
  active tinyint(3) unsigned NOT NULL default '0',
  category tinyint(3) unsigned NOT NULL default '0',
  `name` varchar(64) NOT NULL,
  `url` varchar(128) NOT NULL,
  alt_text tinytext NULL default NULL,
  path varchar(128) NOT NULL
  PRIMARY KEY  (uid)
);


# Table structure for table 'tx_mmforum_domain_model_forum_user_privatemessages'
#
CREATE TABLE tx_mmforum_domain_model_user_privatemessages (
  uid int(11) unsigned NOT NULL auto_increment,
  pid int(11) unsigned NOT NULL default '0',
  tstamp int(11) unsigned NOT NULL default '0',
  deleted tinyint(4) NOT NULL default '0',
  feuser int(11) unsigned NOT NULL default '0',
  opponent int(11) unsigned NOT NULL default '0',
  `type` tinyint(3) NOT NULL default '0',
  `message`  int(11) unsigned NOT NULL default '0',
  user_read` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (uid),
  KEY `message` (`message`),
  KEY `opponent` (`opponent`)
);


#
# Table structure for table 'tx_mmforum_domain_model_user_privatemessages_text'
#
CREATE TABLE `tx_mmforum_domain_model_user_privatemessages_text` (
  uid int(11) unsigned NOT NULL auto_increment,
  pid int(11) unsigned NOT NULL,
  message_text text NOT NULL,
  PRIMARY KEY (`uid`)
);



#
# Table structure for table "fe_users"
#
CREATE TABLE fe_users (
  tx_mmforum_post_count int(11) NOT NULL default '0',
  tx_mmforum_topic_favsubscriptions int(11) unsigned NOT NULL default '0',
  tx_mmforum_topic_subscriptions int(11) unsigned NOT NULL default '0',
  tx_mmforum_forum_subscriptions int(11) unsigned default '0',
  tx_mmforum_helpful_count int(11) NOT NULL default '0',
  tx_mmforum_private_messages int(11) unsigned NOT NULL default '0',
  tx_mmforum_signature text,
  tx_mmforum_interests text,
  tx_mmforum_userfield_values int(11) unsigned NOT NULL default '0'
  tx_mmforum_read_topics int(11) unsigned NOT NULL default '0',
  tx_mmforum_support_posts int(11) unsigned NOT NULL default '0',
  tx_mmforum_use_gravatar tinyint(1) unsigned default '0',
  tx_mmforum_facebook VARCHAR( 255 ) NOT NULL,
  tx_mmforum_twitter VARCHAR( 255 ) NOT NULL,
  tx_mmforum_google VARCHAR( 255 ) NOT NULL,
  tx_mmforum_skype VARCHAR( 255 ) NOT NULL,
  tx_mmforum_job VARCHAR( 255 ) NOT NULL,
  tx_mmforum_working_environment int(11) unsigned NOT NULL default '0',
  tx_mmforum_contact text
);


#
# Table structure for table 'tx_mmforum_cache'
#
CREATE TABLE tx_mmforum_cache (
    id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    identifier varchar(250) DEFAULT '' NOT NULL,
    crdate int(11) UNSIGNED DEFAULT '0' NOT NULL,
    content mediumblob,
    lifetime int(11) UNSIGNED DEFAULT '0' NOT NULL,
    PRIMARY KEY (id),
    KEY cache_id (identifier)
) ENGINE=InnoDB;
 

#
# Table structure for table 'tx_mmforum_cache_tags'
#
CREATE TABLE tx_mmforum_cache_tags (
    id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    identifier varchar(250) DEFAULT '' NOT NULL,
    tag varchar(250) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    KEY cache_id (identifier),
    KEY cache_tag (tag)
) ENGINE=InnoDB;
