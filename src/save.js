/**
 * WordPress dependencies
 */
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { RawHTML } from '@wordpress/element';

export default function save(props) {
	const { attributes } = props;
	const { copyBtnText, copySuccessText } = attributes;
	const defaultCopyBtnText = 'コピーする';
	const defaultCopySuccessText = 'コピー完了';

	const dataAttribute = {
		copyBtnText: !!copyBtnText ? copyBtnText : defaultCopyBtnText,
		copySuccessText: !!copySuccessText
			? copySuccessText
			: defaultCopySuccessText,
	};

	return (
		<div {...useBlockProps.save()}>
			<div className="vk-copy-inner-inner-blocks-wrapper">
				<InnerBlocks.Content />
			</div>
			<div className="vk-copy-inner-button-wrapper">
				<div
					className="vk-copy-inner-button"
					data-vk-copy-inner-block={JSON.stringify(dataAttribute)}
				>
					<RawHTML>
						{!!copyBtnText ? copyBtnText : defaultCopyBtnText}
					</RawHTML>
				</div>
			</div>
		</div>
	);
}
