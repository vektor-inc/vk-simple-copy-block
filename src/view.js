/* global ClipboardJS */

window.addEventListener( 'load', function () {
	// ClipboardJSを実行
	const clipboard = new ClipboardJS( '.vk-copy-inner-button' );
	// クリップボード コピーボタン
	clipboard.on( 'success', function ( e ) {
		const btn = e.trigger;
		btn.classList.add( 'copy-success' );
		let html = btn.innerHTML;
		html = html.replace( 'コピーする', 'コピー完了' );
		btn.innerHTML = html;
		setTimeout( function () {
			btn.classList.remove( 'copy-success' );
			let html = btn.innerHTML;
			html = html.replace( 'コピー完了', 'コピーする' );
			btn.innerHTML = html;
		}, 3000 );
	} );
} );
