<?php
/**
 * Plugin Name: VK Copy Inner Block
 * Plugin URI: https://github.com/vektor-inc/vk-copy-inner-block
 * Description: インナーブロックのコードをコピーするためのブロックです。
 * Version: 0.0.1
 * Stable tag: 0.0.1
 * Requires at least: 6.2
 * Author: Vektor,Inc.
 * Author URI: https://vektor-inc.co.jp
 * Text Domain: vk-copy-inner-block
 *
 * @package vk-copy-inner-block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VK_COPY_INNER_BLOCK_DIR_PATH', plugin_dir_path( __FILE__ ) );
require_once __DIR__ . '/inc/load.php';
