<?php
/**
 * Block functions specific for the Gutenberg editor plugin.
 *
 * @package vk-simple-copy-block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add Block Category
 *
 * @param array  $categories categories.
 */
add_filter(
	'block_categories_all',
	function ( $categories ) {
		foreach ( $categories as $key => $value ) {
			$keys[] = $value['slug'];
		}
		if ( ! in_array( 'vk-simple-copy-block', $keys, true ) ) {
			$categories = array_merge(
				$categories,
				array(
					array(
						'slug'  => 'vk-simple-copy-block',
						'title' => __( 'VK Simple Copy Block', 'vk-simple-copy-block' ),
						'icon'  => '',
					),
				)
			);
		}
		return $categories;
	}
);

add_action(
	'init',
	function () {
		load_plugin_textdomain( 'vk-simple-copy-block' );
		$blocks = array(
			'simple-copy',
			'copy-target',
			'copy-button-wrap',
			'copy-button',
		);
		foreach ( $blocks as $block ) {
			register_block_type(
				VK_SIMPLE_COPY_BLOCK_DIR_PATH . 'build/' . $block . '/'
			);
			wp_set_script_translations(
				'vk-simple-copy-block-' . $block . '-editor-script-js',
				'vk-simple-copy-block'
			);
		}
	}
);



/**
 * Register Inner Copy block.
 *
 * @param string $block_content block_content.
 * @param array  $block block.
 * @return string
 */
function vk_simple_copy_block_render( $block_content, $block ) {
	$block_id = $block['attrs']['blockId'];
	if ( empty( $block_id ) ) {
		return $block_content;
	}

	$array = vk_simple_copy_block_get_copy_target_block_contents();
	if ( empty( $array ) ) {
		return $block_content;
	}

	if ( ! empty( $array[ $block_id ] ) ) {
		$raw_block_content = vk_simple_copy_block_decode_pattern_content( $array[ $block_id ] );
		$block_content     = str_replace( '<input type="hidden"/>', '<input type="hidden" value="' . rawurlencode( wp_json_encode( $raw_block_content ) ) . '" />', $block_content );
		return $block_content;
	}
}
add_filter( 'render_block_vk-simple-copy-block/simple-copy', 'vk_simple_copy_block_render', 10, 2 );

/**
 * Get_the_contentからblock_idとinner_textの配列を作る
 *
 * @return array
 */
function vk_simple_copy_block_get_copy_target_block_contents() {
	$pattern = '@
	<!--\s*wp:vk-simple-copy-block/simple-copy\s*{"blockId":"(?<block_id>[a-z0-9-]+)"(.*?)}\s*-->\s*
	<div\s*class="wp-block-vk-simple-copy-block-simple-copy(.*?)"><!--\s*wp:vk-simple-copy-block/copy-target(.*?)-->\s*
	<div\s*class="wp-block-vk-simple-copy-block-copy-target(.*?)"(.*?)>(?<inner_text>[\s\S]*?)</div>\s*
	<!--\s*/wp:vk-simple-copy-block/copy-target\s*-->\s*\s*
	<!--\s*wp:vk-simple-copy-block/copy-button-wrap(.*?)-->\s*
	<div(.*?)class="wp-block-vk-simple-copy-block-copy-button-wrap(.*?)"><!--\s*wp:vk-simple-copy-block/copy-button(.*?)-->\s*
	.+?\s*
	<!--\s*/wp:vk-simple-copy-block/copy-button\s*--></div>\s*
	<!--\s*/wp:vk-simple-copy-block/copy-button-wrap\s*--></div>\s*
	<!--\s*/wp:vk-simple-copy-block/simple-copy\s*-->
	@x';

	$content = get_the_content();
	preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER );
	$array = array();
	foreach ( $matches as $match ) {
		$add_array = array(
			$match['block_id'] => $match['inner_text'],
		);
		$array     = array_merge( $array, $add_array );
	}
	return $array;
}

/**
 * Process post content, replacing broken encoding & removing refs.
 *
 * @see https://github.com/WordPress/pattern-directory/blob/5847378beaafbeea1d34a5715f4e66f5b2ec9155/public_html/wp-content/plugins/pattern-directory/includes/pattern-post-type.php#L807-L820
 *
 * Some image URLs have &s, which are double-encoded and sanitized to become malformed,
 * for example, `https://img.rawpixel.com/s3fs-private/rawpixel_images/website_content/a010-markuss-0964.jpg?w=1200\u0026amp;h=1200\u0026amp;fit=clip\u0026amp;crop=default\u0026amp;dpr=1\u0026amp;q=75\u0026amp;vib=3\u0026amp;con=3\u0026amp;usm=15\u0026amp;cs=srgb\u0026amp;bg=F4F4F3\u0026amp;ixlib=js-2.2.1\u0026amp;s=7d494bd5db8acc2a34321c15ed18ace5`.
 *
 * @param string $content The raw post content.
 *
 * @return string
 */
function vk_simple_copy_block_decode_pattern_content( $content ) {
	// Sometimes the initial `\` is missing, so look for both versions.
	$content = str_replace( array( '\u0026amp;', 'u0026amp;' ), '&', $content );
	// Remove `ref` from all content.
	$content = preg_replace( '/"ref":\d+,?/', '', $content );
	return $content;
}
