/**
 * WordPress dependencies
 */
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import { CopyIcon } from './copy-icon';

export default function save() {
	return (
		<div { ...useBlockProps.save() }>
			<div className="vk-copy-inner-inner-blocks-wrapper">
				<InnerBlocks.Content />
			</div>
			<div className="vk-copy-inner-button-wrapper">
				<a className="vk-copy-inner-button btn btn-primary">
					<span className="vk-copy-inner-button-icon">
						<CopyIcon />
					</span>
					<span className="vk-copy-inner-button-text">
						コピーする
					</span>
				</a>
			</div>
		</div>
	);
}
