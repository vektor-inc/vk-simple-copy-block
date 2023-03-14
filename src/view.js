/* global ClipboardJS */

window.addEventListener('load', function () {
	// ClipboardJSを実行
	const clipboard = new ClipboardJS('.vk-copy-inner-button');
	clipboard.on('success', function (e) {
		const btn = e.trigger;
		btn.classList.add('copy-success');
		const attributes = JSON.parse(
			btn.getAttribute('data-vk-copy-inner-block')
		);
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
});
