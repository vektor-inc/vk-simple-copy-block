<?php
/**
 * Class SimpleCopyTest
 *
 * @package vk-simple-copy-block
 */

class SimpleCopyTest extends WP_UnitTestCase {

	/**
	 * InnerCopyブロックを挿入する投稿
	 *
	 * @var int|\WP_Error $post_id
	 */
	public $post_id;

	/**
	 * 各テストケースの実行直前に投稿を作る
	 */
	public function setUp(): void {
		parent::setUp();
		$post = array(
			'post_title'   => 'Post Title',
			'post_content' => '<!-- wp:paragraph -->
			<p>コア静的ブロック</p>
			<!-- /wp:paragraph -->

			<!-- wp:vk-simple-copy-block/simple-copy {"blockId":"6e8c1c5d-4fe2-428f-85c6-bafd8d73bf34"} -->
			<div class="wp-block-vk-simple-copy-block-simple-copy"><!-- wp:vk-simple-copy-block/copy-target -->
			<div class="wp-block-vk-simple-copy-block-copy-target"><!-- wp:paragraph {"backgroundColor":"black","textColor":"white"} -->
			<p class="has-white-color has-black-background-color has-text-color has-background">sample text</p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:vk-simple-copy-block/copy-target -->

			<!-- wp:vk-simple-copy-block/copy-button-wrap -->
			<div class="wp-block-vk-simple-copy-block-copy-button-wrap"><!-- wp:vk-simple-copy-block/copy-button -->
			<div class="wp-block-vk-simple-copy-block-copy-button"><input type="hidden"/><button class="vk-simple-copy-button"><span class="vk-simple-copy-button-do">コピーする</span><span class="vk-simple-copy-button-done">コピー完了</span></button></div>
			<!-- /wp:vk-simple-copy-block/copy-button --></div>
			<!-- /wp:vk-simple-copy-block/copy-button-wrap --></div>
			<!-- /wp:vk-simple-copy-block/simple-copy -->

			<!-- wp:vk-simple-copy-block/simple-copy {"blockId":"4ece45a5-5ab8-4e5d-a164-868817b1ead0"} -->
			<div class="wp-block-vk-simple-copy-block-simple-copy"><!-- wp:vk-simple-copy-block/copy-target -->
			<div class="wp-block-vk-simple-copy-block-copy-target"><!-- wp:vk-blocks/slider {"blockId":"bf8bf5f1-b2da-4cdd-bca1-8aebf36d8553"} -->
			<div class="wp-block-vk-blocks-slider swiper swiper-container vk_slider vk_slider_bf8bf5f1-b2da-4cdd-bca1-8aebf36d8553" data-vkb-slider="{&quot;autoPlay&quot;:true,&quot;autoPlayStop&quot;:false,&quot;autoPlayDelay&quot;:2500,&quot;pagination&quot;:&quot;bullets&quot;,&quot;blockId&quot;:&quot;bf8bf5f1-b2da-4cdd-bca1-8aebf36d8553&quot;,&quot;width&quot;:&quot;&quot;,&quot;loop&quot;:true,&quot;effect&quot;:&quot;slide&quot;,&quot;speed&quot;:500,&quot;slidesPerViewMobile&quot;:1,&quot;slidesPerViewTablet&quot;:1,&quot;slidesPerViewPC&quot;:1,&quot;slidesPerGroup&quot;:&quot;one-by-one&quot;}"><div class="swiper-wrapper"><!-- wp:vk-blocks/slider-item {"blockId":"da7d7911-82ed-4a48-85ce-1dcea3fa7c65"} -->
			<div class="wp-block-vk-blocks-slider-item vk_slider_item swiper-slide vk_valign-center vk_slider_item-da7d7911-82ed-4a48-85ce-1dcea3fa7c65  vk_slider_item-paddingLR-none vk_slider_item-paddingVertical-none"><div class="vk_slider_item-background-area has-background-dim has-background-dim-5"></div><div class="vk_slider_item_container container"></div></div>
			<!-- /wp:vk-blocks/slider-item --></div><div class="swiper-button-next swiper-button-mobile-bottom"></div><div class="swiper-button-prev swiper-button-mobile-bottom"></div><div class="swiper-pagination swiper-pagination-bullets"></div></div>
			<!-- /wp:vk-blocks/slider --></div>
			<!-- /wp:vk-simple-copy-block/copy-target -->

			<!-- wp:vk-simple-copy-block/copy-button-wrap -->
			<div class="wp-block-vk-simple-copy-block-copy-button-wrap"><!-- wp:vk-simple-copy-block/copy-button -->
			<div class="wp-block-vk-simple-copy-block-copy-button"><input type="hidden"/><button class="vk-simple-copy-button"><span class="vk-simple-copy-button-do">コピーする</span><span class="vk-simple-copy-button-done">コピー完了</span></button></div>
			<!-- /wp:vk-simple-copy-block/copy-button --></div>
			<!-- /wp:vk-simple-copy-block/copy-button-wrap --></div>
			<!-- /wp:vk-simple-copy-block/simple-copy -->
			',
			'post_status'  => 'publish',
		);
		$this->post_id = wp_insert_post( $post );
	}

	/**
	 * Tear down each test method.
	 */
	public function tearDown(): void {
		parent::tearDown();
		wp_delete_post( $this->post_id, true );
		$this->post_id = 0;
	}

	/**
	 * get_the_contentからblock_idとinner_textの配列か作られているかテスト
	 */
	public function test_get_copy_target_block_contents() {

		$correct = array(
			'6e8c1c5d-4fe2-428f-85c6-bafd8d73bf34' => '<!-- wp:paragraph {"backgroundColor":"black","textColor":"white"} -->
			<p class="has-white-color has-black-background-color has-text-color has-background">sample text</p>
			<!-- /wp:paragraph -->',
			'4ece45a5-5ab8-4e5d-a164-868817b1ead0' => '<!-- wp:vk-blocks/slider {"blockId":"bf8bf5f1-b2da-4cdd-bca1-8aebf36d8553"} -->
			<div class="wp-block-vk-blocks-slider swiper swiper-container vk_slider vk_slider_bf8bf5f1-b2da-4cdd-bca1-8aebf36d8553" data-vkb-slider="{&quot;autoPlay&quot;:true,&quot;autoPlayStop&quot;:false,&quot;autoPlayDelay&quot;:2500,&quot;pagination&quot;:&quot;bullets&quot;,&quot;blockId&quot;:&quot;bf8bf5f1-b2da-4cdd-bca1-8aebf36d8553&quot;,&quot;width&quot;:&quot;&quot;,&quot;loop&quot;:true,&quot;effect&quot;:&quot;slide&quot;,&quot;speed&quot;:500,&quot;slidesPerViewMobile&quot;:1,&quot;slidesPerViewTablet&quot;:1,&quot;slidesPerViewPC&quot;:1,&quot;slidesPerGroup&quot;:&quot;one-by-one&quot;}"><div class="swiper-wrapper"><!-- wp:vk-blocks/slider-item {"blockId":"da7d7911-82ed-4a48-85ce-1dcea3fa7c65"} -->
			<div class="wp-block-vk-blocks-slider-item vk_slider_item swiper-slide vk_valign-center vk_slider_item-da7d7911-82ed-4a48-85ce-1dcea3fa7c65  vk_slider_item-paddingLR-none vk_slider_item-paddingVertical-none"><div class="vk_slider_item-background-area has-background-dim has-background-dim-5"></div><div class="vk_slider_item_container container"></div></div>
			<!-- /wp:vk-blocks/slider-item --></div><div class="swiper-button-next swiper-button-mobile-bottom"></div><div class="swiper-button-prev swiper-button-mobile-bottom"></div><div class="swiper-pagination swiper-pagination-bullets"></div></div>
			<!-- /wp:vk-blocks/slider -->'
		);
		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'vk_simple_copy_block_get_copy_target_block_contents()' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;

		$this->go_to( get_permalink( $this->post ) );
		$return = vk_simple_copy_block_get_copy_target_block_contents();

		// print 'return  :';
		// print PHP_EOL;
		// var_dump( $return );
		// print PHP_EOL;
		// print 'correct  :';
		// print PHP_EOL;
		// var_dump( $correct );
		// print PHP_EOL;
		$this->assertSame( $correct, $return );
	}
}
