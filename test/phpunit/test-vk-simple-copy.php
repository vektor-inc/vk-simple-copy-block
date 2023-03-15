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

			<!-- wp:vk-simple-copy-block/simple-copy {"blockId":"b3c17ebe-5010-4d3b-bebc-90e280e3efbb"} -->
			<div class="wp-block-vk-simple-copy-block-simple-copy"><!-- wp:vk-simple-copy-block/copy-target -->
			<div class="wp-block-vk-simple-copy-block-copy-target"><!-- wp:paragraph {"backgroundColor":"black","textColor":"white"} -->
			<p class="has-white-color has-black-background-color has-text-color has-background">sample text</p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:vk-simple-copy-block/copy-target -->

			<!-- wp:vk-simple-copy-block/copy-button -->
			<div class="wp-block-vk-simple-copy-block-copy-button"><div class="vk-simple-copy-button" data-vk-simple-copy-block="{&quot;text&quot;:&quot;コピーする&quot;,&quot;successText&quot;:&quot;コピー完了&quot;}">コピーする</div></div>
			<!-- /wp:vk-simple-copy-block/copy-button --></div>
			<!-- /wp:vk-simple-copy-block/simple-copy -->

			<!-- wp:vk-simple-copy-block/simple-copy {"blockId":"69109f35-8179-4195-9d5e-55b1bf4755c9"} -->
			<div class="wp-block-vk-simple-copy-block-simple-copy"><!-- wp:vk-simple-copy-block/copy-target -->
			<div class="wp-block-vk-simple-copy-block-copy-target"><!-- wp:vk-blocks/slider {"blockId":"aaf938a3-01df-4c9c-88b2-44ab2ac0864c"} -->
			<div class="wp-block-vk-blocks-slider swiper swiper-container vk_slider vk_slider_aaf938a3-01df-4c9c-88b2-44ab2ac0864c" data-vkb-slider="{&quot;autoPlay&quot;:true,&quot;autoPlayStop&quot;:false,&quot;autoPlayDelay&quot;:2500,&quot;pagination&quot;:&quot;bullets&quot;,&quot;blockId&quot;:&quot;aaf938a3-01df-4c9c-88b2-44ab2ac0864c&quot;,&quot;width&quot;:&quot;&quot;,&quot;loop&quot;:true,&quot;effect&quot;:&quot;slide&quot;,&quot;speed&quot;:500,&quot;slidesPerViewMobile&quot;:1,&quot;slidesPerViewTablet&quot;:1,&quot;slidesPerViewPC&quot;:1,&quot;slidesPerGroup&quot;:&quot;one-by-one&quot;}"><div class="swiper-wrapper"><!-- wp:vk-blocks/slider-item {"blockId":"7b55ae46-880f-4466-bc1e-b4d6818c0fa8"} -->
			<div class="wp-block-vk-blocks-slider-item vk_slider_item swiper-slide vk_valign-center vk_slider_item-7b55ae46-880f-4466-bc1e-b4d6818c0fa8  vk_slider_item-paddingLR-none vk_slider_item-paddingVertical-none"><div class="vk_slider_item-background-area has-background-dim has-background-dim-5"></div><div class="vk_slider_item_container container"></div></div>
			<!-- /wp:vk-blocks/slider-item --></div><div class="swiper-button-next swiper-button-mobile-bottom"></div><div class="swiper-button-prev swiper-button-mobile-bottom"></div><div class="swiper-pagination swiper-pagination-bullets"></div></div>
			<!-- /wp:vk-blocks/slider --></div>
			<!-- /wp:vk-simple-copy-block/copy-target -->

			<!-- wp:vk-simple-copy-block/copy-button -->
			<div class="wp-block-vk-simple-copy-block-copy-button"><div class="vk-simple-copy-button" data-vk-simple-copy-block="{&quot;text&quot;:&quot;コピーする&quot;,&quot;successText&quot;:&quot;コピー完了&quot;}">コピーする</div></div>
			<!-- /wp:vk-simple-copy-block/copy-button --></div>
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
	 * コピーボタンのdata-clipboard-textにインナーブロックのコンテンツが追加されているかテスト
	 */
	public function test_render_simple_copy() {

		$test_data = array(
			// core/paragraph
			array(
				'block_content' => '<div class="wp-block-vk-simple-copy-block-simple-copy">
				<div class="wp-block-vk-simple-copy-block-copy-target">
				<p class="has-white-color has-black-background-color has-text-color has-background">sample text</p>
				</div>
				<div class="wp-block-vk-simple-copy-block-copy-button"><div class="vk-simple-copy-button" data-vk-simple-copy-block="{&quot;text&quot;:&quot;コピーする&quot;,&quot;successText&quot;:&quot;コピー完了&quot;}">コピーする</div></div>
				</div>',
				'block' => array(
					'blockName' => 'vk-simple-copy-block/simple-copy',
					'attrs' => array(
						'blockId' => 'b3c17ebe-5010-4d3b-bebc-90e280e3efbb'
					),
					'innerBlocks' => array(
						'blockName' => 'core/paragraph',
						'attrs' => array(),
					),
				),
				'correct' => '<div class="wp-block-vk-simple-copy-block-simple-copy">
				<div class="wp-block-vk-simple-copy-block-copy-target">
				<p class="has-white-color has-black-background-color has-text-color has-background">sample text</p>
				</div>
				<div class="wp-block-vk-simple-copy-block-copy-button"><div data-clipboard-text="&lt;!-- wp:paragraph {&quot;backgroundColor&quot;:&quot;black&quot;,&quot;textColor&quot;:&quot;white&quot;} --&gt;
			&lt;p class=&quot;has-white-color has-black-background-color has-text-color has-background&quot;&gt;sample text&lt;/p&gt;
			&lt;!-- /wp:paragraph --&gt;" class="vk-simple-copy-button" data-vk-simple-copy-block="{&quot;text&quot;:&quot;コピーする&quot;,&quot;successText&quot;:&quot;コピー完了&quot;}">コピーする</div></div>
				</div>',
			),
			// vk-blocks/slider
			array(
				'block_content' => '<div class="wp-block-vk-simple-copy-block-simple-copy">
				<div class="wp-block-vk-simple-copy-block-copy-target">
				<div class="wp-block-vk-blocks-slider swiper swiper-container vk_slider vk_slider_aaf938a3-01df-4c9c-88b2-44ab2ac0864c" data-vkb-slider="{&quot;autoPlay&quot;:true,&quot;autoPlayStop&quot;:false,&quot;autoPlayDelay&quot;:2500,&quot;pagination&quot;:&quot;bullets&quot;,&quot;blockId&quot;:&quot;aaf938a3-01df-4c9c-88b2-44ab2ac0864c&quot;,&quot;width&quot;:&quot;&quot;,&quot;loop&quot;:true,&quot;effect&quot;:&quot;slide&quot;,&quot;speed&quot;:500,&quot;slidesPerViewMobile&quot;:1,&quot;slidesPerViewTablet&quot;:1,&quot;slidesPerViewPC&quot;:1,&quot;slidesPerGroup&quot;:&quot;one-by-one&quot;}"><div class="swiper-wrapper">
				<div class="wp-block-vk-blocks-slider-item vk_slider_item swiper-slide vk_valign-center vk_slider_item-7b55ae46-880f-4466-bc1e-b4d6818c0fa8  vk_slider_item-paddingLR-none vk_slider_item-paddingVertical-none"><div class="vk_slider_item-background-area has-background-dim has-background-dim-5"></div><div class="vk_slider_item_container container"></div></div>
				</div><div class="swiper-button-next swiper-button-mobile-bottom"></div><div class="swiper-button-prev swiper-button-mobile-bottom"></div><div class="swiper-pagination swiper-pagination-bullets"></div></div>
				</div>
				<div class="wp-block-vk-simple-copy-block-copy-button"><div class="vk-simple-copy-button" data-vk-simple-copy-block="{&quot;text&quot;:&quot;コピーする&quot;,&quot;successText&quot;:&quot;コピー完了&quot;}">コピーする</div></div>
				</div>',
				'block' => array(
					'blockName' => 'vk-simple-copy-block/simple-copy',
					'attrs' => array(
						'blockId' => '69109f35-8179-4195-9d5e-55b1bf4755c9'
					),
					'innerBlocks' => array(
						'blockName' => 'vk-blocks/slider',
						'attrs' => array(),
					),
				),
				'correct' => '<div class="wp-block-vk-simple-copy-block-simple-copy">
				<div class="wp-block-vk-simple-copy-block-copy-target">
				<div class="wp-block-vk-blocks-slider swiper swiper-container vk_slider vk_slider_aaf938a3-01df-4c9c-88b2-44ab2ac0864c" data-vkb-slider="{&quot;autoPlay&quot;:true,&quot;autoPlayStop&quot;:false,&quot;autoPlayDelay&quot;:2500,&quot;pagination&quot;:&quot;bullets&quot;,&quot;blockId&quot;:&quot;aaf938a3-01df-4c9c-88b2-44ab2ac0864c&quot;,&quot;width&quot;:&quot;&quot;,&quot;loop&quot;:true,&quot;effect&quot;:&quot;slide&quot;,&quot;speed&quot;:500,&quot;slidesPerViewMobile&quot;:1,&quot;slidesPerViewTablet&quot;:1,&quot;slidesPerViewPC&quot;:1,&quot;slidesPerGroup&quot;:&quot;one-by-one&quot;}"><div class="swiper-wrapper">
				<div class="wp-block-vk-blocks-slider-item vk_slider_item swiper-slide vk_valign-center vk_slider_item-7b55ae46-880f-4466-bc1e-b4d6818c0fa8  vk_slider_item-paddingLR-none vk_slider_item-paddingVertical-none"><div class="vk_slider_item-background-area has-background-dim has-background-dim-5"></div><div class="vk_slider_item_container container"></div></div>
				</div><div class="swiper-button-next swiper-button-mobile-bottom"></div><div class="swiper-button-prev swiper-button-mobile-bottom"></div><div class="swiper-pagination swiper-pagination-bullets"></div></div>
				</div>
				<div class="wp-block-vk-simple-copy-block-copy-button"><div data-clipboard-text="&lt;!-- wp:vk-blocks/slider {&quot;blockId&quot;:&quot;aaf938a3-01df-4c9c-88b2-44ab2ac0864c&quot;} --&gt;
			&lt;div class=&quot;wp-block-vk-blocks-slider swiper swiper-container vk_slider vk_slider_aaf938a3-01df-4c9c-88b2-44ab2ac0864c&quot; data-vkb-slider=&quot;{&amp;quot;autoPlay&amp;quot;:true,&amp;quot;autoPlayStop&amp;quot;:false,&amp;quot;autoPlayDelay&amp;quot;:2500,&amp;quot;pagination&amp;quot;:&amp;quot;bullets&amp;quot;,&amp;quot;blockId&amp;quot;:&amp;quot;aaf938a3-01df-4c9c-88b2-44ab2ac0864c&amp;quot;,&amp;quot;width&amp;quot;:&amp;quot;&amp;quot;,&amp;quot;loop&amp;quot;:true,&amp;quot;effect&amp;quot;:&amp;quot;slide&amp;quot;,&amp;quot;speed&amp;quot;:500,&amp;quot;slidesPerViewMobile&amp;quot;:1,&amp;quot;slidesPerViewTablet&amp;quot;:1,&amp;quot;slidesPerViewPC&amp;quot;:1,&amp;quot;slidesPerGroup&amp;quot;:&amp;quot;one-by-one&amp;quot;}&quot;&gt;&lt;div class=&quot;swiper-wrapper&quot;&gt;&lt;!-- wp:vk-blocks/slider-item {&quot;blockId&quot;:&quot;7b55ae46-880f-4466-bc1e-b4d6818c0fa8&quot;} --&gt;
			&lt;div class=&quot;wp-block-vk-blocks-slider-item vk_slider_item swiper-slide vk_valign-center vk_slider_item-7b55ae46-880f-4466-bc1e-b4d6818c0fa8  vk_slider_item-paddingLR-none vk_slider_item-paddingVertical-none&quot;&gt;&lt;div class=&quot;vk_slider_item-background-area has-background-dim has-background-dim-5&quot;&gt;&lt;/div&gt;&lt;div class=&quot;vk_slider_item_container container&quot;&gt;&lt;/div&gt;&lt;/div&gt;
			&lt;!-- /wp:vk-blocks/slider-item --&gt;&lt;/div&gt;&lt;div class=&quot;swiper-button-next swiper-button-mobile-bottom&quot;&gt;&lt;/div&gt;&lt;div class=&quot;swiper-button-prev swiper-button-mobile-bottom&quot;&gt;&lt;/div&gt;&lt;div class=&quot;swiper-pagination swiper-pagination-bullets&quot;&gt;&lt;/div&gt;&lt;/div&gt;
			&lt;!-- /wp:vk-blocks/slider --&gt;" class="vk-simple-copy-button" data-vk-simple-copy-block="{&quot;text&quot;:&quot;コピーする&quot;,&quot;successText&quot;:&quot;コピー完了&quot;}">コピーする</div></div>
				</div>',
			),
		);
		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'vk_simple_copy_block_render()' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;

		foreach ( $test_data as $test_value ) {
			$this->go_to( get_permalink( $this->post ) );
			$return = vk_simple_copy_block_render( $test_value['block_content'], $test_value['block'] );

			print 'return  :';
			print PHP_EOL;
			var_dump( $return );
			print PHP_EOL;
			print 'correct  :';
			print PHP_EOL;
			var_dump( $test_value['correct'] );
			print PHP_EOL;
			$this->assertSame( $test_value['correct'], $return );
		}
	}
}
