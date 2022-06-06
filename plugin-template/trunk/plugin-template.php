<?php
/*
Plugin Name: Common Ninja Plugin
Plugin URI: https://www.commoninja.com/
Description: Build & Monetize E-Commerce Apps
Version: 1.0.0
Author: Common Ninja
Author URI: https://www.commoninja.com/
License: TBA
Text Domain: common-ninja-integrations
*/

if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

$COMMON_NINJA_PLUGIN_DIR = plugin_dir_path(__FILE__) . 'commonninja/';

require_once(COMMON_NINJA_PLUGIN_DIR . 'trait.commonninja-plugin.php');
require_once(COMMON_NINJA_PLUGIN_DIR . 'trait.commonninja-styles.php');
require_once(COMMON_NINJA_PLUGIN_DIR . 'trait.commonninja-menu.php');
require_once(COMMON_NINJA_PLUGIN_DIR . 'trait.commonninja-helpers.php');
require_once(COMMON_NINJA_PLUGIN_DIR . 'trait.commonninja-auth.php');
require_once(COMMON_NINJA_PLUGIN_DIR . 'class.commonninja.php');

$CommonNinjaIntegrations = new CommonNinjaIntegrations();
