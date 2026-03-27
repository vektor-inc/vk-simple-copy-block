//プラグイン名
const pluginName = 'vk-simple-copy-block';

// モジュールをロード
const gulp = require('gulp');

// ディストリビューションを作成
gulp.task('dist', function (done) {
	const files = gulp.src(
		[
			'./build/**',
			'./vendor/**',
			'./inc/**',
			'./readme.txt',
			'./vk-simple-copy-block.php',
			'!./node_mudules/**',
			'!./tests/**',
		],
		{
			base: './',
			// Gulp 5 ではデフォルトの encoding が utf8 に変更されたため、
			// バイナリファイル（画像など）が破損する。encoding: false を指定して回避する。
			encoding: false,
		}
	);
	files.pipe(gulp.dest(`dist/${pluginName}`));
	done();
});
