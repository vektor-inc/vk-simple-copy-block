/**
 * WordPress dependencies
 */
import {
	InnerBlocks,
	useBlockProps,
	store as blockEditorStore,
	Warning,
	InspectorControls,
} from '@wordpress/block-editor';
import { useEffect, RawHTML } from '@wordpress/element';
import {
	store as blocksStore,
	getBlockTypes,
	hasBlockSupport,
	isReusableBlock,
	isTemplatePart,
} from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';
import { TextControl, PanelBody } from '@wordpress/components';

/**
 * Internal dependencies
 */
import './editor.scss';

export default function InnerCopyEdit(props) {
	const { attributes, setAttributes, clientId } = props;
	const { copyBtnText, copySuccessText } = attributes;
	const defaultCopyBtnText = 'コピーする';
	const defaultCopySuccessText = 'コピー完了';

	/**
	 * isParentsSynced 親ブロックに再利用ブロックが存在するか
	 * isParentsInnerCopyBlock 親ブロックにインナーコピーブロックが存在するか
	 */
	const { isParentsSynced, isParentsInnerCopyBlock } = useSelect(
		(select) => {
			const { getBlockParents, getBlockName } = select(blockEditorStore);
			const { getBlockType } = select(blocksStore);
			const parentsIdArray = getBlockParents(clientId);
			let _isParentsSynced = false;
			let _isParentsInnerCopyBlock = false;
			parentsIdArray.forEach((_clientId) => {
				const blockName = getBlockName(_clientId);
				const blockType = getBlockType(blockName);
				const isSynced =
					isReusableBlock(blockType) || isTemplatePart(blockType);
				if (isSynced) {
					_isParentsSynced = true;
				}
				if (blockName === 'vk-copy-inner-block/copy-inner') {
					_isParentsInnerCopyBlock = true;
				}
			});

			return {
				isParentsSynced: _isParentsSynced,
				isParentsInnerCopyBlock: _isParentsInnerCopyBlock,
			};
		},
		[clientId]
	);

	useEffect(() => {
		if (!isParentsSynced) {
			setAttributes({ blockId: clientId });
		}
	}, [clientId, setAttributes, isParentsSynced]);

	// インナーブロックで許可するブロック vk-copy-inner-blockは除く
	const allowBlockTypes = [];
	getBlockTypes().forEach((blockType) => {
		if (hasBlockSupport(blockType, 'inserter', true) && !blockType.parent) {
			allowBlockTypes.push(blockType);
		}
	});
	const AllBlockName = allowBlockTypes.map((blockType) => blockType.name);
	const ALLOWED_BLOCKS = AllBlockName.filter(
		(item) => !item.match(/vk-copy-inner-block/)
	);

	const dataAttribute = {
		copyBtnText: !!copyBtnText ? copyBtnText : defaultCopyBtnText,
		copySuccessText: !!copySuccessText
			? copySuccessText
			: defaultCopySuccessText,
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title="コピーインナーブロック設定">
					<TextControl
						label="コピーボタンテキスト"
						value={!!copyBtnText ? copyBtnText : ''}
						onChange={(value) => {
							setAttributes({ copyBtnText: value });
						}}
					/>
					<TextControl
						label="コピー完了テキスト"
						value={!!copySuccessText ? copySuccessText : ''}
						onChange={(value) => {
							setAttributes({ copySuccessText: value });
						}}
					/>
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps()}>
				{isParentsSynced && (
					<Warning>
						再利用ブロックにインナーコピーブロックを含めることはできません。通常のブロックへ変換するか、完全に削除してください。
					</Warning>
				)}
				{isParentsInnerCopyBlock && (
					<Warning>
						インナーコピーブロックの中にインナーコピーブロックを含めることはできません。削除してください。
					</Warning>
				)}
				<div className="vk-copy-inner-inner-blocks-wrapper">
					<InnerBlocks
						allowedBlocks={ALLOWED_BLOCKS}
						templateLock={false}
					/>
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
		</>
	);
}
