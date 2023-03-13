/* global ClipboardJS */

window.addEventListener('load', function () {
	// ClipboardJSを実行
	const clipboard = new ClipboardJS('.vk-copy-inner-button');
	// クリップボード コピーボタン
	clipboard.on('success', function (e) {
		const btn = e.trigger;
		btn.classList.add('copy-success');
		let btnBefore = btn.innerHTML;
		btnBefore = btnBefore.replace('コピーする', 'コピー完了');
		btn.innerHTML = btnBefore;
		setTimeout(function () {
			btn.classList.remove('copy-success');
			let btnAfter = btn.innerHTML;
			btnAfter = btnAfter.replace('コピー完了', 'コピーする');
			btn.innerHTML = btnAfter;
		}, 3000);
	});
});
