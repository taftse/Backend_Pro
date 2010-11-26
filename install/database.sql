-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 26, 2010 at 09:55 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET FOREIGN_KEY_CHECKS=0;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `backendpro`
--

-- --------------------------------------------------------

--
-- Table structure for table `bep_access_actions`
--

CREATE TABLE IF NOT EXISTS `bep_access_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) CHARACTER SET utf8 NOT NULL,
  `resource_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `bep_access_actions`
--

INSERT INTO `bep_access_actions` (`id`, `name`, `resource_id`) VALUES
(1, 'Manage', 3),
(2, 'Add', 4),
(3, 'Edit', 4),
(4, 'Delete', 4);

-- --------------------------------------------------------

--
-- Table structure for table `bep_access_groups`
--

CREATE TABLE IF NOT EXISTS `bep_access_groups` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(254) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `bep_access_groups`
--

INSERT INTO `bep_access_groups` (`id`, `locked`, `name`) VALUES
(1, 1, 'Administrators'),
(2, 1, 'Users');

-- --------------------------------------------------------

--
-- Table structure for table `bep_access_permissions`
--

CREATE TABLE IF NOT EXISTS `bep_access_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `resource_id` (`resource_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `bep_access_permissions`
--

INSERT INTO `bep_access_permissions` (`id`, `group_id`, `resource_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(5, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `bep_access_permission_actions`
--

CREATE TABLE IF NOT EXISTS `bep_access_permission_actions` (
  `permission_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  KEY `permission_id` (`permission_id`),
  KEY `action_id` (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bep_access_permission_actions`
--

INSERT INTO `bep_access_permission_actions` (`permission_id`, `action_id`) VALUES
(3, 1),
(5, 2),
(5, 3),
(5, 4);

-- --------------------------------------------------------

--
-- Table structure for table `bep_access_resources`
--

CREATE TABLE IF NOT EXISTS `bep_access_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) CHARACTER SET utf8 NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `lft` smallint(6) NOT NULL,
  `rgt` smallint(6) NOT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `bep_access_resources`
--

INSERT INTO `bep_access_resources` (`id`, `name`, `parent_id`, `lft`, `rgt`, `locked`) VALUES
(1, 'Site', NULL, 1, 8, 1),
(2, 'Control Panel', 1, 2, 7, 1),
(3, 'Settings', 2, 3, 4, 1),
(4, 'Users', 2, 5, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bep_settings`
--

CREATE TABLE IF NOT EXISTS `bep_settings` (
  `slug` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('text','textarea','password','select','select-multiple','checkbox') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text',
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `options` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `validation_rules` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_gui` tinyint(1) NOT NULL DEFAULT '1',
  `module` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores all sorts of settings for the admin to change';

--
-- Dumping data for table `bep_settings`
--

INSERT INTO `bep_settings` (`slug`, `title`, `description`, `type`, `value`, `options`, `validation_rules`, `is_required`, `is_gui`, `module`) VALUES
('activation_period', 'Activation Period', 'The time in days which a user has before there new account gets removed if not activated.', 'text', '7', '', 'numeric', 1, 1, 'users'),
('allow_user_registration', 'Allow User Registration', '', 'checkbox', '1', '', '', 1, 1, 'users'),
('auto_login_length', 'Auto Login Length', 'The period in seconds which the users login cookie will last for before they need to re-login.', 'text', '15778458', '', 'is_numeric', 1, 1, 'users'),
('automated_email_address', 'Automated Email Address', '', 'text', 'noreply@mysite.com', '', 'valid_email', 1, 1, 'users'),
('automated_email_name', 'Automated Email Name', '', 'text', 'MySite - No Reply', '', '', 1, 1, 'users'),
('default_user_group', 'Default Group', 'This is the group all newly registered users are assigned to.', 'text', '2', '', '', 0, 1, 'users'),
('identity_mode', 'Identity Mode', 'The identity mode allows you to change if the user must use their email/user or either to log into the system', 'select', 'all', 'username,email,all', '', 1, 1, 'users'),
('min_password_length', 'Minimum Password Length', '', 'text', '4', '', 'numeric', 1, 1, 'users'),
('min_username_length', 'Minimum Username Length', '', 'text', '5', '', 'numeric', 1, 1, 'users'),
('site_name', 'Site Name', 'The name of the website', 'text', 'MySite', '', '', 1, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `bep_users`
--

CREATE TABLE IF NOT EXISTS `bep_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `activation_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reset_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_id` int(11) NOT NULL,
  `last_ip` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `group_id` (`group_id`),
  KEY `activation_key` (`activation_key`),
  KEY `reset_key` (`reset_key`),
  KEY `remember_code` (`remember_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores all user profilesfor the site.' AUTO_INCREMENT=12 ;

--
-- Dumping data for table `bep_users`
--

INSERT INTO `bep_users` (`id`, `username`, `password`, `email`, `is_active`, `activation_key`, `reset_key`, `remember_code`, `group_id`, `last_ip`, `last_login`, `created_on`, `modified_on`) VALUES
(1, 'admin', '767ea7203da3d1b1fdd603f05c02d56752fef5f5', 'admin@mysite.com', 1, NULL, NULL, NULL, 1, '127.0.0.1', '2010-11-26 21:46:25', '2010-09-04 00:00:00', '2010-11-13 18:23:55'),
(11, 'james', '767ea7203da3d1b1fdd603f05c02d56752fef5f5', 'james@mysite.com', 1, NULL, NULL, NULL, 2, NULL, NULL, '2010-10-12 04:36:49', '2010-10-12 04:43:27');

-- --------------------------------------------------------

--
-- Table structure for table `bep_user_profiles`
--

CREATE TABLE IF NOT EXISTS `bep_user_profiles` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `second_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bep_user_profiles`
--

INSERT INTO `bep_user_profiles` (`user_id`, `first_name`, `second_name`, `gender`) VALUES
(1, '', '', 'male'),
(11, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `ip_address` varchar(16) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `user_agent` varchar(50) CHARACTER SET latin1 NOT NULL,
  `user_data` text NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ci_sessions`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `bep_users`
--
ALTER TABLE `bep_users`
  ADD CONSTRAINT `bep_users_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `bep_access_groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `bep_user_profiles`
--
ALTER TABLE `bep_user_profiles`
  ADD CONSTRAINT `bep_user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `bep_users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

SET FOREIGN_KEY_CHECKS=1;