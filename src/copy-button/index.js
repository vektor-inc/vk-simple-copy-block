/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { CopyIcon } from './copy-icon';
import json from './block.json';
import edit from './edit';
import save from './save';

const { name, ...settings } = json;

// ウィジェット,サイトエディタでは登録させない
const pathString = window.location.pathname;
if (
	pathString.indexOf('site-editor.php') === -1 &&
	pathString.indexOf('widgets.php') === -1
) {
	registerBlockType(name, {
		title: __('Copy Button', 'vk-simple-copy-block'),
		description: __(
			'Button to copy the code of the copy target block.',
			'vk-simple-copy-block'
		),
		icon: <CopyIcon />,
		...settings,
		save,
		edit,
	});
}
