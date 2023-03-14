/* global ClipboardJS */

window.addEventListener('load', function () {
	// ClipboardJSを実行
	const clipboard = new ClipboardJS('.vk-copy-inner-button');

	// //data-vk-copy-inner-block属性のNodeを取得
	let copyInnerNodeList = document.querySelectorAll(
		'[data-vk-copy-inner-block]'
	);
	// 配列に変換。
	copyInnerNodeList = Array.from(copyInnerNodeList);

	if (copyInnerNodeList) {
		for (const index in copyInnerNodeList) {
			const copyInnerNode = copyInnerNodeList[index];
			const attributes = JSON.parse(
				copyInnerNode.getAttribute('data-vk-copy-inner-block')
			);
			// クリップボード コピーボタン
			clipboard.on('success', function (e) {
				const btn = e.trigger;
				btn.classList.add('copy-success');
				let btnBefore = btn.innerHTML;
				btnBefore = btnBefore.replace(
					attributes.copyBtnText,
					attributes.copySuccessText
				);
				btn.innerHTML = btnBefore;
				setTimeout(function () {
					btn.classList.remove('copy-success');
					let btnAfter = btn.innerHTML;
					btnAfter = btnAfter.replace(
						attributes.copySuccessText,
						attributes.copyBtnText
					);
					btn.innerHTML = btnAfter;
				}, 3000);
			});
		}
	}
});
