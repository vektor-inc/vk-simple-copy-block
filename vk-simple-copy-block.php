<?php
/**
 * Plugin Name: VK Simple Copy Block
 * Plugin URI: https://github.com/vektor-inc/vk-simple-copy-block
 * Description: A block to copy the code of the block inside the copy target.
 * Version: 0.1.4.0
 * Stable tag: 0.1.4.0
 * Requires at least: 6.1
 * Author: Vektor,Inc.
 * Author URI: https://vektor-inc.co.jp
 * Text Domain: vk-simple-copy-block
 *
 * @package vk-simple-copy-block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VK_SIMPLE_COPY_BLOCK_DIR_PATH', plugin_dir_path( __FILE__ ) );
require_once __DIR__ . '/inc/load.php';
