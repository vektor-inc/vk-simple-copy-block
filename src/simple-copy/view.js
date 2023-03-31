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
	function handleClick(event) {
		const button = event.currentTarget;

		// Grab the pattern markup from hidden input
		const blockDataInput = button.previousElementSibling;

		const content = JSON.parse(decodeURIComponent(blockDataInput.value));

		const success = copyToClipboard(content);

		// Make sure we reset focus in case it was lost in the 'copy' command.
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
			// TODO Handle error case
		}
	}

	buttons.forEach((button) => {
		button.addEventListener('click', handleClick);
	});
});
