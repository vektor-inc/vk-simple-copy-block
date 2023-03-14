<?php
/**
 * Block functions specific for the Gutenberg editor plugin.
 *
 * @package vk-copy-inner-block
 */

add_action(
	'init',
	function () {
		register_block_type(
			VK_COPY_INNER_BLOCK_DIR_PATH . 'build/'
		);
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
						'title' => __( 'VK Blocks', 'vk-copy-inner-block' ),
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
function vk_copy_inner_block_render( $block_content, $block ) {
	$block_id = $block['attrs']['blockId'];
	if ( empty( $block_id ) ) {
		return $block_content;
	}

	$pattern = '@
	<!--\s*wp:vk-copy-inner-block/copy-inner\s*{"blockId":"(?<block_id>[a-z0-9-]+)"(.*?)}\s*-->\s*
	<div\s*class="wp-block-vk-copy-inner-block-copy-inner(.*?)"><div\s*class="vk-copy-inner-inner-blocks-wrapper">(?<inner_text>[\s\S]*?)</div>\s*
	<div\s*class="vk-copy-inner-button-wrapper"><div\s*class="vk-copy-inner-button">
	.+?\s*
	</div></div></div>\s*
	<!--\s*/wp:vk-copy-inner-block/copy-inner\s*-->
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
		$block_content = str_replace( '<div class="vk-copy-inner-button">', '<div data-clipboard-text="' . esc_attr( $array[ $block_id ] ) . '" class="vk-copy-inner-button">', $block_content );
		return $block_content;
	}
}
add_filter( 'render_block_vk-copy-inner-block/copy-inner', 'vk_copy_inner_block_render', 10, 2 );

