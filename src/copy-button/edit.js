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
	__experimentalUseColorProps as useColorProps,
} from '@wordpress/block-editor';
import { RawHTML } from '@wordpress/element';
import { TextControl, PanelBody } from '@wordpress/components';

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const { text, successText } = attributes;
	const defaultText = 'コピーする';
	const defaultSuccessText = 'コピー完了';

	const dataAttribute = {
		text: !!text ? text : defaultText,
		successText: !!successText ? successText : defaultSuccessText,
	};

	const colorProps = useColorProps(attributes);

	return (
		<>
			<InspectorControls>
				<PanelBody title="コピーインナーブロック設定">
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
			<div {...useBlockProps()}>
				<div
					className={classnames(
						'vk-copy-inner-button',
						colorProps.className
					)}
					style={{
						...colorProps?.style,
					}}
					data-vk-copy-inner-block={JSON.stringify(dataAttribute)}
				>
					<RawHTML>{!!text ? text : defaultText}</RawHTML>
				</div>
			</div>
		</>
	);
}
