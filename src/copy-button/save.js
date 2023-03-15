/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import {
	useBlockProps,
	__experimentalGetColorClassesAndStyles as getColorClassesAndStyles,
} from '@wordpress/block-editor';
import { RawHTML } from '@wordpress/element';

export default function save(props) {
	const { attributes } = props;
	const { text, successText } = attributes;
	const colorProps = getColorClassesAndStyles(attributes);
	const defaultText = 'コピーする';
	const defaultSuccessText = 'コピー完了';

	const dataAttribute = {
		text: !!text ? text : defaultText,
		successText: !!successText ? successText : defaultSuccessText,
	};

	return (
		<div {...useBlockProps.save()}>
			<div
				className={classnames(
					'vk-copy-inner-button',
					colorProps.className
				)}
				style={{
					...colorProps.style,
				}}
				data-vk-copy-inner-block={JSON.stringify(dataAttribute)}
			>
				<RawHTML>{!!text ? text : defaultText}</RawHTML>
			</div>
		</div>
	);
}
