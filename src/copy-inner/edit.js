/**
 * WordPress dependencies
 */
import {
	InnerBlocks,
	useBlockProps,
	store as blockEditorStore,
	Warning,
} from '@wordpress/block-editor';
import { useEffect } from '@wordpress/element';
import {
	store as blocksStore,
	isReusableBlock,
	isTemplatePart,
} from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';

/**
 * Internal dependencies
 */
import './editor.scss';

export default function InnerCopyEdit(props) {
	const { setAttributes, clientId } = props;

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

	const ALLOWED_BLOCKS = [
		'vk-copy-inner-block/copy-target',
		'vk-copy-inner-block/copy-button',
	];
	const TEMPLATE = [
		['vk-copy-inner-block/copy-target'],
		['vk-copy-inner-block/copy-button'],
	];

	return (
		<>
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
				<InnerBlocks
					template={TEMPLATE}
					allowedBlocks={ALLOWED_BLOCKS}
					templateLock="all"
				/>
			</div>
		</>
	);
}
