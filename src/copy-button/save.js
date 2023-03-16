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
	const { attributes, className } = props;
	const { text, successText, buttonAlign, fontSize, style } = attributes;
	const defaultText = 'コピーする';
	const defaultSuccessText = 'コピー完了';

	const dataAttribute = {
		text: !!text ? text : defaultText,
		successText: !!successText ? successText : defaultSuccessText,
	};

	const colorProps = getColorClassesAndStyles(attributes);

	const wrapperClasses = classnames(className, {
		[`has-custom-font-size`]: fontSize || style?.typography?.fontSize,
	});

	return (
		<div
			{...useBlockProps.save({ className: wrapperClasses })}
			style={{
				display: buttonAlign ? 'flex' : undefined,
				justifyContent: buttonAlign,
				fontSize: style?.typography?.fontSize,
			}}
		>
			<div
				className={classnames(
					'vk-simple-copy-button',
					colorProps.className
				)}
				style={{
					...colorProps.style,
				}}
				data-vk-simple-copy-block={JSON.stringify(dataAttribute)}
			>
				<RawHTML>{!!text ? text : defaultText}</RawHTML>
			</div>
		</div>
	);
}
