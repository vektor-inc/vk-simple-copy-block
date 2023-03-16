/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import {
	useBlockProps,
	InspectorControls,
	BlockControls,
	JustifyContentControl,
	__experimentalUseColorProps as useColorProps,
	getTypographyClassesAndStyles as useTypographyProps,
} from '@wordpress/block-editor';
import { RawHTML } from '@wordpress/element';
import { TextControl, PanelBody } from '@wordpress/components';

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const { text, successText, buttonAlign } = attributes;
	const defaultText = 'コピーする';
	const defaultSuccessText = 'コピー完了';

	const dataAttribute = {
		text: !!text ? text : defaultText,
		successText: !!successText ? successText : defaultSuccessText,
	};

	const blockProps = useBlockProps();

	const colorProps = useColorProps(attributes);
	const typographyProps = useTypographyProps(attributes);

	return (
		<>
			<BlockControls group="block">
				<JustifyContentControl
					value={buttonAlign}
					onChange={(value) => setAttributes({ buttonAlign: value })}
					allowedControls={['left', 'center', 'right']}
				/>
			</BlockControls>
			<InspectorControls>
				<PanelBody title="コピーボタン設定">
					<TextControl
						label="コピーボタンテキスト"
						value={!!text ? text : ''}
						onChange={(value) => {
							setAttributes({ text: value });
						}}
					/>
					<TextControl
						label="コピー完了テキスト"
						value={!!successText ? successText : ''}
						onChange={(value) => {
							setAttributes({ successText: value });
						}}
					/>
				</PanelBody>
			</InspectorControls>
			<div
				{...blockProps}
				className={classnames(
					blockProps.className,
					typographyProps.className,
					{
						[`has-custom-font-size`]: blockProps.style.fontSize,
					}
				)}
				style={{
					display: buttonAlign ? 'flex' : undefined,
					justifyContent: buttonAlign,
					...typographyProps.style,
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
		</>
	);
}
