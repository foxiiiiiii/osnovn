<?php
/**
 * @package ab-express
 */
/*
Plugin Name: Ab-Express Plugin
Plugin URI:
Description: Плагин AB-Express инициализации данных
Version: 1.1
Author: Cherhish Web studio
Text Domain: ab-express
License: Apache
Copyright: 2020
*/

// if accessed directly
if (!defined('ABSPATH')) {
    die();
}

require_once(plugin_dir_path(__FILE__) . 'amb-core.php');

$amb = new AmbCore();
$amb->InitCustomUserProfileFields();
$amb->SetLoginHooks();
$amb->HttpsToHttpInSourcesLoadersHooks();
$amb->CustomRoutes();
//add_action('init', static function () {
//        new AmbCore();
//        //$amb->InitCustomUserProfileFields();
//    });