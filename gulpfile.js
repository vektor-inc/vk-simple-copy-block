//プラグイン名
const pluginName = 'vk-copy-inner-block';

// モジュールをロード
const gulp = require( 'gulp' );

// ディストリビューションを作成
gulp.task( 'dist', function( done ) {
	const files = gulp.src(
		[
			'./build/**',
			'./vendor/**',
			'./inc/**',
			'./readme.txt',
			'./vk-copy-inner-block.php',
			'!./node_mudules/**',
			'!./tests/**',
		],
		{
			base: './',
		}
	);
	files.pipe( gulp.dest( `dist/${ pluginName }` ) );
	done();
} );
