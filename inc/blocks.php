<?php
/**
 * Block functions specific for the Gutenberg editor plugin.
 *
 * @package vk-simple-copy-block
 */

add_action(
	'init',
	function () {
		$blocks = array(
			'simple-copy',
			'copy-target',
			'copy-button',
		);
		foreach ( $blocks as $block ) {
			register_block_type(
				VK_SIMPLE_COPY_BLOCK_DIR_PATH . 'build/' . $block . '/'
			);
		}
	}
);

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
		if ( ! in_array( 'vk-blocks-cat', $keys, true ) ) {
			$categories = array_merge(
				$categories,
				array(
					array(
						'slug'  => 'vk-blocks-cat',
						'title' => __( 'VK Blocks', 'vk-simple-copy-block' ),
						'icon'  => '',
					),
				)
			);
		}
		return $categories;
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

	$pattern = '@
	<!--\s*wp:vk-simple-copy-block/simple-copy\s*{"blockId":"(?<block_id>[a-z0-9-]+)"(.*?)}\s*-->\s*
	<div\s*class="wp-block-vk-simple-copy-block-simple-copy(.*?)"><!--\s*wp:vk-simple-copy-block/copy-target(.*?)-->\s*
	<div\s*class="wp-block-vk-simple-copy-block-copy-target(.*?)"(.*?)>(?<inner_text>[\s\S]*?)</div>\s*
	.+?\s*
	<!--\s*wp:vk-simple-copy-block/copy-button(.*?)-->\s*
	<div\s*class="wp-block-vk-simple-copy-block-copy-button(.*?)">\s*
	.+?\s*
	<!--\s*/wp:vk-simple-copy-block/copy-button\s*--></div>\s*
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
	if ( empty( $array ) ) {
		return $block_content;
	}

	if ( ! empty( $array[ $block_id ] ) ) {
		wp_enqueue_script( 'clipboard' );
		$raw_block_content = htmlentities( $array[ $block_id ] , ENT_COMPAT, 'UTF-8');
		$block_content     = str_replace( '<div class="vk-simple-copy-button', '<div data-clipboard-text="' . esc_attr( $raw_block_content ) . '" class="vk-simple-copy-button', $block_content );
		return $block_content;
	}
}
add_filter( 'render_block_vk-simple-copy-block/simple-copy', 'vk_simple_copy_block_render', 10, 2 );

