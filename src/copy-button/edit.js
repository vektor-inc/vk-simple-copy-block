/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { TextControl, PanelBody, RangeControl } from '@wordpress/components';
import {
	useBlockProps,
	InspectorControls,
	BlockControls,
	AlignmentControl,
	JustifyContentControl,
	__experimentalUseBorderProps as useBorderProps,
	__experimentalUseColorProps as useColorProps,
	__experimentalGetSpacingClassesAndStyles as useSpacingProps,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import { useSelect, useDispatch } from '@wordpress/data';

export default function Edit(props) {
	const { attributes, setAttributes, clientId } = props;
	const { text, successText, textAlign, width } = attributes;
	const defaultText = __('Copy', 'vk-simple-copy-block');
	const defaultSuccessText = __('Copied', 'vk-simple-copy-block');

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
				<PanelBody
					title={__('Copy button setting', 'vk-simple-copy-block')}
				>
					<TextControl
						label={__('Copy button Text', 'vk-simple-copy-block')}
						value={!!text ? text : ''}
						onChange={(value) => {
							setAttributes({ text: value });
						}}
					/>
					<TextControl
						label={__('Copy complete Text', 'vk-simple-copy-block')}
						value={!!successText ? successText : ''}
						onChange={(value) => {
							setAttributes({ successText: value });
						}}
					/>
					<RangeControl
						label={__('Width settings (%)', 'vk-simple-copy-block')}
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
				className={classnames(blockProps.className, {
					[`has-custom-width`]: width,
					[`has-custom-font-size`]: blockProps.style.fontSize,
				})}
				style={{
					...blockProps.style,
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
						{!!text ? text : defaultText}
					</span>
					<span className="vk-simple-copy-button-done">
						{!!successText ? successText : defaultSuccessText}
					</span>
				</button>
			</div>
		</>
	);
}
