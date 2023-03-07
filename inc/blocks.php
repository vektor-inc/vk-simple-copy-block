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
	<!--\s*wp:vk-copy-inner-block/copy-inner\s*{"blockId":"(?<block_id>[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12})"(.*?)}\s*-->\s*
	<div\s*class="wp-block-vk-copy-inner-block-copy-inner(.*?)"><div\s*class="vk-copy-inner-inner-blocks-wrapper">(?<inner_text>[\s\S]*?)</div>\s*
	<div\s*class="vk-copy-inner-button-wrapper"><div\s*class="vk-copy-inner-button\s*btn\s*btn-primary"><span\s*class="vk-copy-inner-button-icon"><svg\s*xmlns="http://www.w3.org/2000/svg"\s*viewBox="0\s*0\s*512\s*512"><path\s*d="M224\s*0c-35.3\s*0-64\s*28.7-64\s*64V288c0\s*35.3\s*28.7\s*64\s*64\s*64H448c35.3\s*0\s*64-28.7\s*64-64V64c0-35.3-28.7-64-64-64H224zM64\s*160c-35.3\s*0-64\s*28.7-64\s*64V448c0\s*35.3\s*28.7\s*64\s*64\s*64H288c35.3\s*0\s*64-28.7\s*64-64V384H288v64H64V224h64V160H64z"></path></svg></span><span\s*class="vk-copy-inner-button-text">コピーする</span></div></div></div>\s*
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
		$block_content = str_replace( '<div class="vk-copy-inner-button btn btn-primary">', '<div data-clipboard-text="' . esc_attr( $array[ $block_id ] ) . '" class="vk-copy-inner-button btn btn-primary">', $block_content );
		return $block_content;
	}
}
add_filter( 'render_block_vk-copy-inner-block/copy-inner', 'vk_copy_inner_block_render', 10, 2 );

/**
 * SVGを保存できるようにする
 *
 * Fix block saving for Non-Super-Admins (no unfiltered_html capability).
 * For Non-Super-Admins, some styles & HTML tags/attributes are removed upon saving,
 * this allows vkblocks HTML tags & attributes from being saved.
 *
 * For every vkblocks block, add the HTML tags and attributes used here.
 *
 * @see The list of tags & attributes currently allowed: https://core.trac.wordpress.org/browser/tags/5.2/src/wp-includes/kses.php#L61
 *
 * @param array $tags Allowed HTML tags & attributes.
 *
 * @return array Modified HTML tags & attributes.
 */
function vk_copy_inner_block_allow_wp_kses_allowed_html( $tags ) {
	// Used by svg
	$tags['svg']  = array(
		'viewbox' => true,
		'xmlns'   => true,
	);
	$tags['path'] = array(
		'd' => true,
	);
	return $tags;
}
add_filter( 'wp_kses_allowed_html', 'vk_copy_inner_block_allow_wp_kses_allowed_html' );
