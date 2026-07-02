/**
 * Uses a hidden textarea that is added and removed from the DOM in order to copy to clipboard via the Browser.
 *
 * @see https://github.com/WordPress/pattern-directory/blob/7241d0ba9cc511723b3f850a5f241d1c13d29a43/public_html/wp-content/themes/pattern-directory/src/utils/copy-to-clipboard.js#L7
 *
 * @param {string} stringToCopy A string that will be copied to the clipboard
 * @return {boolean} Whether the copy function succeeded
 */
function copyToClipboard(stringToCopy) {
	const element = document.createElement('textarea');

	// We don't want the text area to be selected since it's temporary.
	element.setAttribute('readonly', '');

	// We don't want screen readers to read the content since it's pattern markup
	element.setAttribute('aria-hidden', 'true');

	// We don't want the text area to be visible since it's temporary.
	element.style.position = 'absolute';
	element.style.left = '-9999px';

	element.value = stringToCopy;

	document.body.appendChild(element);
	element.select();

	const success = document.execCommand('copy');
	document.body.removeChild(element);

	return success;
}

window.addEventListener('load', function () {
	const buttons = document.querySelectorAll('.vk-simple-copy-button');

	const timeoutId = [];

	// Main click handler (uses the Clipboard API first, falling back to execCommand). クリック時のメインハンドラ（Clipboard API 優先・execCommand フォールバック）
	async function handleClick(event) {
		const button = event.currentTarget;

		// Grab the pattern markup from hidden input. 隠し input からパターンのマークアップを取得する
		const blockDataInput = button.previousElementSibling;

		// If JSON.parse fails, log the error and stop processing. JSON.parse 失敗時はエラーを出力して処理を中断する
		let content;
		try {
			content = JSON.parse(decodeURIComponent(blockDataInput.value));
		} catch (e) {
			// eslint-disable-next-line no-console
			console.error(
				'vk-simple-copy-block: Failed to parse block data',
				e
			);
			return;
		}

		// Use the Clipboard API as the primary method when available, and fall back to execCommand if it fails. Clipboard API が利用可能な場合は主処理として使用し、失敗した場合は execCommand にフォールバックする
		let success = false;
		if (navigator.clipboard) {
			try {
				await navigator.clipboard.writeText(content);
				success = true;
			} catch (e) {
				success = copyToClipboard(content);
			}
		} else {
			success = copyToClipboard(content);
		}

		// Make sure we reset focus in case it was lost in the 'copy' command. 'copy' コマンド実行中にフォーカスが失われた場合に備えてフォーカスを戻す
		button.focus();

		if (success) {
			// クリックされたボタンのidxを設定
			const idx = [...buttons].indexOf(button);
			clearTimeout(timeoutId[idx]);
			button.classList.add('copy-success');
			timeoutId[idx] = setTimeout(() => {
				button.classList.remove('copy-success');
			}, 3000);
		} else {
			// Log the failure when both the Clipboard API and execCommand fail. Clipboard API と execCommand の両方が失敗した場合にログを出力する
			// eslint-disable-next-line no-console
			console.error('vk-simple-copy-block: Failed to copy to clipboard');
		}
	}

	buttons.forEach((button) => {
		button.addEventListener('click', handleClick);
	});
});
