<?php
/**
 * Class InnerCopyTest
 *
 * @package vk-simple-copy-block
 */

class InnerCopyTest extends WP_UnitTestCase {

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

			<!-- wp:vk-simple-copy-block/copy-inner {"blockId":"5ae0b505-5e44-4fea-9e1e-9c9645b31051"} -->
			<div class="wp-block-vk-simple-copy-block-copy-inner"><div class="vk-copy-inner-inner-blocks-wrapper"><!-- wp:paragraph {"backgroundColor":"black","textColor":"white"} -->
			<p class="has-white-color has-black-background-color has-text-color has-background">sample text</p>
			<!-- /wp:paragraph --></div><div class="vk-copy-inner-button-wrapper"><div class="vk-copy-inner-button" data-vk-simple-copy-block="{&quot;copyBtnText&quot;:&quot;コピーする&quot;,&quot;copySuccessText&quot;:&quot;コピー完了&quot;}">コピーする</div></div></div>
			<!-- /wp:vk-simple-copy-block/copy-inner -->

			<!-- wp:vk-simple-copy-block/copy-inner {"blockId":"6d2abe76-5de2-4b5e-a1f0-24b45ff0ab09"} -->
			<div class="wp-block-vk-simple-copy-block-copy-inner"><div class="vk-copy-inner-inner-blocks-wrapper"><!-- wp:vk-blocks/slider {"blockId":"3d9abe44-433e-4a28-9c57-82be59439732"} -->
			<div class="wp-block-vk-blocks-slider swiper swiper-container vk_slider vk_slider_3d9abe44-433e-4a28-9c57-82be59439732" data-vkb-slider="{&quot;autoPlay&quot;:true,&quot;autoPlayStop&quot;:false,&quot;autoPlayDelay&quot;:2500,&quot;pagination&quot;:&quot;bullets&quot;,&quot;blockId&quot;:&quot;3d9abe44-433e-4a28-9c57-82be59439732&quot;,&quot;width&quot;:&quot;&quot;,&quot;loop&quot;:true,&quot;effect&quot;:&quot;slide&quot;,&quot;speed&quot;:500,&quot;slidesPerViewMobile&quot;:1,&quot;slidesPerViewTablet&quot;:1,&quot;slidesPerViewPC&quot;:1,&quot;slidesPerGroup&quot;:&quot;one-by-one&quot;}"><div class="swiper-wrapper"><!-- wp:vk-blocks/slider-item {"blockId":"c74b26a4-b231-433d-861a-c221dbf45053"} -->
			<div class="wp-block-vk-blocks-slider-item vk_slider_item swiper-slide vk_valign-center vk_slider_item-c74b26a4-b231-433d-861a-c221dbf45053  vk_slider_item-paddingLR-none vk_slider_item-paddingVertical-none"><div class="vk_slider_item-background-area has-background-dim has-background-dim-5"></div><div class="vk_slider_item_container container"></div></div>
			<!-- /wp:vk-blocks/slider-item --></div><div class="swiper-button-next swiper-button-mobile-bottom"></div><div class="swiper-button-prev swiper-button-mobile-bottom"></div><div class="swiper-pagination swiper-pagination-bullets"></div></div>
			<!-- /wp:vk-blocks/slider --></div><div class="vk-copy-inner-button-wrapper"><div class="vk-copy-inner-button" data-vk-simple-copy-block="{&quot;copyBtnText&quot;:&quot;コピーする&quot;,&quot;copySuccessText&quot;:&quot;コピー完了&quot;}">コピーする</div></div></div>
			<!-- /wp:vk-simple-copy-block/copy-inner -->',
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
				'block_content' => '<div class="wp-block-vk-simple-copy-block-copy-inner"><div class="vk-copy-inner-inner-blocks-wrapper">
				<p class="has-white-color has-black-background-color has-text-color has-background">sample text</p>
				</div><div class="vk-copy-inner-button-wrapper"><div class="vk-copy-inner-button" data-vk-simple-copy-block="{"copyBtnText":"コピーする","copySuccessText":"コピー完了"}">コピーする</div></div></div>',
				'block' => array(
					'blockName' => 'vk-simple-copy-block/copy-inner',
					'attrs' => array(
						'blockId' => '5ae0b505-5e44-4fea-9e1e-9c9645b31051'
					),
					'innerBlocks' => array(
						'blockName' => 'core/paragraph',
						'attrs' => array(),
					),
				),
				'correct' => '<div class="wp-block-vk-simple-copy-block-copy-inner"><div class="vk-copy-inner-inner-blocks-wrapper">
				<p class="has-white-color has-black-background-color has-text-color has-background">sample text</p>
				</div><div class="vk-copy-inner-button-wrapper"><div data-clipboard-text="&lt;!-- wp:paragraph {&quot;backgroundColor&quot;:&quot;black&quot;,&quot;textColor&quot;:&quot;white&quot;} --&gt;
			&lt;p class=&quot;has-white-color has-black-background-color has-text-color has-background&quot;&gt;sample text&lt;/p&gt;
			&lt;!-- /wp:paragraph --&gt;" class="vk-copy-inner-button" data-vk-simple-copy-block="{"copyBtnText":"コピーする","copySuccessText":"コピー完了"}">コピーする</div></div></div>',
			),
			// vk-blocks/slider
			array(
				'block_content' => '<div class="wp-block-vk-simple-copy-block-copy-inner"><div class="vk-copy-inner-inner-blocks-wrapper">
				<div class="wp-block-vk-blocks-slider swiper swiper-container vk_slider vk_slider_3d9abe44-433e-4a28-9c57-82be59439732 swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events" data-vkb-slider="{&quot;autoPlay&quot;:true,&quot;autoPlayStop&quot;:false,&quot;autoPlayDelay&quot;:2500,&quot;pagination&quot;:&quot;bullets&quot;,&quot;blockId&quot;:&quot;3d9abe44-433e-4a28-9c57-82be59439732&quot;,&quot;width&quot;:&quot;&quot;,&quot;loop&quot;:true,&quot;effect&quot;:&quot;slide&quot;,&quot;speed&quot;:500,&quot;slidesPerViewMobile&quot;:1,&quot;slidesPerViewTablet&quot;:1,&quot;slidesPerViewPC&quot;:1,&quot;slidesPerGroup&quot;:&quot;one-by-one&quot;}"><div class="swiper-wrapper" id="swiper-wrapper-f8105de9767a993e0" aria-live="off" style="transition-duration: 0ms; transform: translate3d(-1744px, 0px, 0px);"><div class="wp-block-vk-blocks-slider-item vk_slider_item swiper-slide vk_valign-center vk_slider_item-c74b26a4-b231-433d-861a-c221dbf45053 vk_slider_item-paddingLR-none vk_slider_item-paddingVertical-none swiper-slide-duplicate swiper-slide-next swiper-slide-duplicate-prev" data-swiper-slide-index="0" style="width: 872px;" role="group" aria-label="1 / 1"><div class="vk_slider_item-background-area has-background-dim has-background-dim-5"></div><div class="vk_slider_item_container container"></div></div>
				<div class="wp-block-vk-blocks-slider-item vk_slider_item swiper-slide vk_valign-center vk_slider_item-c74b26a4-b231-433d-861a-c221dbf45053 vk_slider_item-paddingLR-none vk_slider_item-paddingVertical-none swiper-slide-duplicate-active swiper-slide-prev swiper-slide-duplicate-next" data-swiper-slide-index="0" style="width: 872px;" role="group" aria-label="1 / 1"><div class="vk_slider_item-background-area has-background-dim has-background-dim-5"></div><div class="vk_slider_item_container container"></div></div>
				<div class="wp-block-vk-blocks-slider-item vk_slider_item swiper-slide vk_valign-center vk_slider_item-c74b26a4-b231-433d-861a-c221dbf45053 vk_slider_item-paddingLR-none vk_slider_item-paddingVertical-none swiper-slide-duplicate swiper-slide-active swiper-slide-duplicate-prev" data-swiper-slide-index="0" style="width: 872px;" role="group" aria-label="1 / 1"><div class="vk_slider_item-background-area has-background-dim has-background-dim-5"></div><div class="vk_slider_item_container container"></div></div></div><div class="swiper-button-next swiper-button-mobile-bottom" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-f8105de9767a993e0"></div><div class="swiper-button-prev swiper-button-mobile-bottom" tabindex="0" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-f8105de9767a993e0"></div><div class="swiper-pagination swiper-pagination-bullets swiper-pagination-clickable"><span class="swiper-pagination-bullet swiper-pagination-bullet-active" tabindex="0" role="button" aria-label="Go to slide 1"></span></div><span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span><span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
				</div><div class="vk-copy-inner-button-wrapper"><div class="vk-copy-inner-button" data-vk-simple-copy-block="{&quot;copyBtnText&quot;:&quot;コピーする&quot;,&quot;copySuccessText&quot;:&quot;コピー完了&quot;}">コピーする</div></div></div>',
				'block' => array(
					'blockName' => 'vk-simple-copy-block/copy-inner',
					'attrs' => array(
						'blockId' => '6d2abe76-5de2-4b5e-a1f0-24b45ff0ab09'
					),
					'innerBlocks' => array(
						'blockName' => 'vk-blocks/slider',
						'attrs' => array(),
					),
				),
				'correct' => '<div class="wp-block-vk-simple-copy-block-copy-inner"><div class="vk-copy-inner-inner-blocks-wrapper">
				<div class="wp-block-vk-blocks-slider swiper swiper-container vk_slider vk_slider_3d9abe44-433e-4a28-9c57-82be59439732 swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events" data-vkb-slider="{&quot;autoPlay&quot;:true,&quot;autoPlayStop&quot;:false,&quot;autoPlayDelay&quot;:2500,&quot;pagination&quot;:&quot;bullets&quot;,&quot;blockId&quot;:&quot;3d9abe44-433e-4a28-9c57-82be59439732&quot;,&quot;width&quot;:&quot;&quot;,&quot;loop&quot;:true,&quot;effect&quot;:&quot;slide&quot;,&quot;speed&quot;:500,&quot;slidesPerViewMobile&quot;:1,&quot;slidesPerViewTablet&quot;:1,&quot;slidesPerViewPC&quot;:1,&quot;slidesPerGroup&quot;:&quot;one-by-one&quot;}"><div class="swiper-wrapper" id="swiper-wrapper-f8105de9767a993e0" aria-live="off" style="transition-duration: 0ms; transform: translate3d(-1744px, 0px, 0px);"><div class="wp-block-vk-blocks-slider-item vk_slider_item swiper-slide vk_valign-center vk_slider_item-c74b26a4-b231-433d-861a-c221dbf45053 vk_slider_item-paddingLR-none vk_slider_item-paddingVertical-none swiper-slide-duplicate swiper-slide-next swiper-slide-duplicate-prev" data-swiper-slide-index="0" style="width: 872px;" role="group" aria-label="1 / 1"><div class="vk_slider_item-background-area has-background-dim has-background-dim-5"></div><div class="vk_slider_item_container container"></div></div>
				<div class="wp-block-vk-blocks-slider-item vk_slider_item swiper-slide vk_valign-center vk_slider_item-c74b26a4-b231-433d-861a-c221dbf45053 vk_slider_item-paddingLR-none vk_slider_item-paddingVertical-none swiper-slide-duplicate-active swiper-slide-prev swiper-slide-duplicate-next" data-swiper-slide-index="0" style="width: 872px;" role="group" aria-label="1 / 1"><div class="vk_slider_item-background-area has-background-dim has-background-dim-5"></div><div class="vk_slider_item_container container"></div></div>
				<div class="wp-block-vk-blocks-slider-item vk_slider_item swiper-slide vk_valign-center vk_slider_item-c74b26a4-b231-433d-861a-c221dbf45053 vk_slider_item-paddingLR-none vk_slider_item-paddingVertical-none swiper-slide-duplicate swiper-slide-active swiper-slide-duplicate-prev" data-swiper-slide-index="0" style="width: 872px;" role="group" aria-label="1 / 1"><div class="vk_slider_item-background-area has-background-dim has-background-dim-5"></div><div class="vk_slider_item_container container"></div></div></div><div class="swiper-button-next swiper-button-mobile-bottom" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-f8105de9767a993e0"></div><div class="swiper-button-prev swiper-button-mobile-bottom" tabindex="0" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-f8105de9767a993e0"></div><div class="swiper-pagination swiper-pagination-bullets swiper-pagination-clickable"><span class="swiper-pagination-bullet swiper-pagination-bullet-active" tabindex="0" role="button" aria-label="Go to slide 1"></span></div><span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span><span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
				</div><div class="vk-copy-inner-button-wrapper"><div data-clipboard-text="&lt;!-- wp:vk-blocks/slider {&quot;blockId&quot;:&quot;3d9abe44-433e-4a28-9c57-82be59439732&quot;} --&gt;
			&lt;div class=&quot;wp-block-vk-blocks-slider swiper swiper-container vk_slider vk_slider_3d9abe44-433e-4a28-9c57-82be59439732&quot; data-vkb-slider=&quot;{&amp;quot;autoPlay&amp;quot;:true,&amp;quot;autoPlayStop&amp;quot;:false,&amp;quot;autoPlayDelay&amp;quot;:2500,&amp;quot;pagination&amp;quot;:&amp;quot;bullets&amp;quot;,&amp;quot;blockId&amp;quot;:&amp;quot;3d9abe44-433e-4a28-9c57-82be59439732&amp;quot;,&amp;quot;width&amp;quot;:&amp;quot;&amp;quot;,&amp;quot;loop&amp;quot;:true,&amp;quot;effect&amp;quot;:&amp;quot;slide&amp;quot;,&amp;quot;speed&amp;quot;:500,&amp;quot;slidesPerViewMobile&amp;quot;:1,&amp;quot;slidesPerViewTablet&amp;quot;:1,&amp;quot;slidesPerViewPC&amp;quot;:1,&amp;quot;slidesPerGroup&amp;quot;:&amp;quot;one-by-one&amp;quot;}&quot;&gt;&lt;div class=&quot;swiper-wrapper&quot;&gt;&lt;!-- wp:vk-blocks/slider-item {&quot;blockId&quot;:&quot;c74b26a4-b231-433d-861a-c221dbf45053&quot;} --&gt;
			&lt;div class=&quot;wp-block-vk-blocks-slider-item vk_slider_item swiper-slide vk_valign-center vk_slider_item-c74b26a4-b231-433d-861a-c221dbf45053  vk_slider_item-paddingLR-none vk_slider_item-paddingVertical-none&quot;&gt;&lt;div class=&quot;vk_slider_item-background-area has-background-dim has-background-dim-5&quot;&gt;&lt;/div&gt;&lt;div class=&quot;vk_slider_item_container container&quot;&gt;&lt;/div&gt;&lt;/div&gt;
			&lt;!-- /wp:vk-blocks/slider-item --&gt;&lt;/div&gt;&lt;div class=&quot;swiper-button-next swiper-button-mobile-bottom&quot;&gt;&lt;/div&gt;&lt;div class=&quot;swiper-button-prev swiper-button-mobile-bottom&quot;&gt;&lt;/div&gt;&lt;div class=&quot;swiper-pagination swiper-pagination-bullets&quot;&gt;&lt;/div&gt;&lt;/div&gt;
			&lt;!-- /wp:vk-blocks/slider --&gt;" class="vk-copy-inner-button" data-vk-simple-copy-block="{&quot;copyBtnText&quot;:&quot;コピーする&quot;,&quot;copySuccessText&quot;:&quot;コピー完了&quot;}">コピーする</div></div></div>',
			),
		);
		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'vk_simple_copy_block_render()' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;

		foreach ( $test_data as $test_value ) {
			$this->go_to( get_permalink( $this->post ) );
			$return = vk_simple_copy_block_render( $test_value['block_content'], $test_value['block'] );

			// print 'return  :';
			// print PHP_EOL;
			// var_dump( $return );
			// print PHP_EOL;
			// print 'correct  :';
			// print PHP_EOL;
			// var_dump( $test_value['correct'] );
			// print PHP_EOL;
			$this->assertSame( $test_value['correct'], $return );
		}
	}
}
