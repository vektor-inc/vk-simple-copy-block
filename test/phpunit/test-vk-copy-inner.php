<?php
/**
 * Class InnerCopyTest
 *
 * @package vk-copy-inner-block
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

<!-- wp:vk-copy-inner-block/copy-inner {"blockId":"5ae0b505-5e44-4fea-9e1e-9c9645b31051"} -->
<div class="wp-block-vk-copy-inner-block-copy-inner"><div class="vk-copy-inner-inner-blocks-wrapper"><!-- wp:paragraph {"backgroundColor":"black","textColor":"white"} -->
<p class="has-white-color has-black-background-color has-text-color has-background">sample text</p>
<!-- /wp:paragraph --></div><div class="vk-copy-inner-button-wrapper"><div class="vk-copy-inner-button btn btn-primary"><span class="vk-copy-inner-button-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M224 0c-35.3 0-64 28.7-64 64V288c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H224zM64 160c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H288c35.3 0 64-28.7 64-64V384H288v64H64V224h64V160H64z"></path></svg></span><span class="vk-copy-inner-button-text">コピーする</span></div></div></div>
<!-- /wp:vk-copy-inner-block/copy-inner -->',
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
	public function test_render_copy_inner() {

		$test_data = array(
			// core/paragraph
			array(
				'block_content' => '<div class="wp-block-vk-copy-inner-block-copy-inner"><div class="vk-copy-inner-inner-blocks-wrapper">
				<p class="has-white-color has-black-background-color has-text-color has-background">sample text</p>
				</div><div class="vk-copy-inner-button-wrapper"><div class="vk-copy-inner-button btn btn-primary"><span class="vk-copy-inner-button-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M224 0c-35.3 0-64 28.7-64 64V288c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H224zM64 160c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H288c35.3 0 64-28.7 64-64V384H288v64H64V224h64V160H64z"></path></svg></span><span class="vk-copy-inner-button-text">コピーする</span></div></div></div>',
				'block' => array(
					'blockName' => 'vk-copy-inner-block/copy-inner',
					'attrs' => array(
						'blockId' => '5ae0b505-5e44-4fea-9e1e-9c9645b31051'
					),
					'innerBlocks' => array(
						'blockName' => 'core/paragraph',
						'attrs' => array(),
					),
				),
				'correct' => '<div class="wp-block-vk-copy-inner-block-copy-inner"><div class="vk-copy-inner-inner-blocks-wrapper">
				<p class="has-white-color has-black-background-color has-text-color has-background">sample text</p>
				</div><div class="vk-copy-inner-button-wrapper"><div data-clipboard-text="&lt;!-- wp:paragraph {&quot;backgroundColor&quot;:&quot;black&quot;,&quot;textColor&quot;:&quot;white&quot;} --&gt;
&lt;p class=&quot;has-white-color has-black-background-color has-text-color has-background&quot;&gt;sample text&lt;/p&gt;
&lt;!-- /wp:paragraph --&gt;" class="vk-copy-inner-button btn btn-primary"><span class="vk-copy-inner-button-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M224 0c-35.3 0-64 28.7-64 64V288c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H224zM64 160c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H288c35.3 0 64-28.7 64-64V384H288v64H64V224h64V160H64z"></path></svg></span><span class="vk-copy-inner-button-text">コピーする</span></div></div></div>',
			),
		);
		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'vk_copy_inner_block_render()' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;

		foreach ( $test_data as $test_value ) {
			$this->go_to( get_permalink( $this->post ) );
			$return = vk_copy_inner_block_render( $test_value['block_content'], $test_value['block'] );

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
