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
	InspectorControls,
	BlockControls,
	AlignmentControl,
	JustifyContentControl,
	__experimentalUseBorderProps as useBorderProps,
	__experimentalUseColorProps as useColorProps,
	getTypographyClassesAndStyles as useTypographyProps,
	__experimentalGetSpacingClassesAndStyles as useSpacingProps,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import { RawHTML } from '@wordpress/element';
import { TextControl, PanelBody, RangeControl } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';

export default function Edit(props) {
	const { attributes, setAttributes, clientId } = props;
	const { text, successText, textAlign, width } = attributes;
	const defaultText = 'コピーする';
	const defaultSuccessText = 'コピー完了';

	const { rootAttributes, rootClientId } = useSelect(
		(select) => {
			const { getBlockAttributes, getBlockRootClientId } =
				select(blockEditorStore);

			const rootId = getBlockRootClientId(clientId);
			const rootAttrs = getBlockAttributes(rootId);

			return {
				rootClientId: rootId,
				rootAttributes: rootAttrs,
			};
		},
		[clientId]
	);
	const { updateBlockAttributes } = useDispatch(blockEditorStore);
	const updateAlignment = (value) => {
		// Update parent alignment.
		updateBlockAttributes(rootClientId, {
			buttonAlign: value,
		});
	};

	const blockProps = useBlockProps();

	const colorProps = useColorProps(attributes);
	const borderProps = useBorderProps(attributes);
	const typographyProps = useTypographyProps(attributes);
	const spacingProps = useSpacingProps(attributes);

	return (
		<>
			<BlockControls group="block">
				<JustifyContentControl
					value={rootAttributes.buttonAlign}
					onChange={updateAlignment}
					allowedControls={['left', 'center', 'right']}
				/>
			</BlockControls>
			<BlockControls group="inline">
				<AlignmentControl
					value={textAlign}
					onChange={(nextAlign) => {
						setAttributes({ textAlign: nextAlign });
					}}
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
					<RangeControl
						label={__('幅の設定 (%)')}
						value={width}
						onChange={(value) => {
							setAttributes({ width: value });
						}}
						min={0}
						max={100}
						allowReset={true}
					/>
				</PanelBody>
			</InspectorControls>
			<div
				{...blockProps}
				className={classnames(
					blockProps.className,
					typographyProps.className,
					{
						[`has-custom-width`]: width,
						[`has-custom-font-size`]: blockProps.style.fontSize,
					}
				)}
				style={{
					...typographyProps.style,
					width: width === undefined ? undefined : `${width}%`,
				}}
			>
				<input type="hidden" />
				<button
					className={classnames(
						'vk-simple-copy-button',
						colorProps.className,
						borderProps.className,
						{
							[`has-text-align-${textAlign}`]: textAlign,
						}
					)}
					style={{
						...borderProps.style,
						...colorProps.style,
						...spacingProps.style,
					}}
				>
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
		</>
	);
}
