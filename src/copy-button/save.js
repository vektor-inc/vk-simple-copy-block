/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	__experimentalGetBorderClassesAndStyles as getBorderClassesAndStyles,
	__experimentalGetColorClassesAndStyles as getColorClassesAndStyles,
	__experimentalGetSpacingClassesAndStyles as getSpacingClassesAndStyles,
} from '@wordpress/block-editor';
import { RawHTML } from '@wordpress/element';

export default function save(props) {
	const { attributes, className } = props;
	const { text, successText, fontSize, style, textAlign, width } = attributes;
	const defaultText = __(
		'Copy',
		'vk-simple-copy-block'
	);
	const defaultSuccessText = __(
		'Copied',
		'vk-simple-copy-block'
	);

	const borderProps = getBorderClassesAndStyles(attributes);
	const colorProps = getColorClassesAndStyles(attributes);
	const spacingProps = getSpacingClassesAndStyles(attributes);
	const buttonClasses = classnames(
		'vk-simple-copy-button',
		colorProps.className,
		borderProps.className,
		{
			[`has-text-align-${textAlign}`]: textAlign,
		}
	);
	const buttonStyle = {
		...borderProps.style,
		...colorProps.style,
		...spacingProps.style,
	};

	const wrapperClasses = classnames(className, {
		[`has-custom-width`]: width,
		[`has-custom-font-size`]: fontSize || style?.typography?.fontSize,
	});

	return (
		<div
			{...useBlockProps.save({ className: wrapperClasses })}
			style={{
				fontSize: style?.typography?.fontSize,
				width: width === undefined ? undefined : `${width}%`,
			}}
		>
			<input type="hidden" />
			<button className={buttonClasses} style={buttonStyle}>
				<span className="vk-simple-copy-button-do">
					<RawHTML>{!!text ? text : defaultText}</RawHTML>
				</span>
				<span className="vk-simple-copy-button-done">
					<RawHTML>
						{!!successText ? successText : defaultSuccessText}
					</RawHTML>
				</span>
			</button>
		</div>
	);
}
