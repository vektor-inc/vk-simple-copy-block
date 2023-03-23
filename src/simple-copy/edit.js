/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	store as blockEditorStore,
	Warning,
	useInnerBlocksProps,
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
	 * isParentsInnerCopyBlock 親ブロックにシンプルコピーブロックが存在するか
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
				if (blockName === 'vk-simple-copy-block/simple-copy') {
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
		'vk-simple-copy-block/copy-target',
		'vk-simple-copy-block/copy-button-wrap',
	];
	const TEMPLATE = [
		['vk-simple-copy-block/copy-target'],
		['vk-simple-copy-block/copy-button-wrap'],
	];

	const blockProps = useBlockProps();

	const innerBlocksProps = useInnerBlocksProps(blockProps, {
		allowedBlocks: ALLOWED_BLOCKS,
		template: TEMPLATE,
		templateLock: 'all',
	});

	return (
		<>
			{isParentsSynced && (
				<Warning>
					{__(
						'再利用ブロックにシンプルコピーブロックを含めることはできません。通常のブロックへ変換するか、完全に削除してください。',
						// 'Reusable blocks cannot contain simple copy blocks. Either convert it to a normal block or remove it completely.',
						'vk-simple-copy-block'
					)}
				</Warning>
			)}
			{isParentsInnerCopyBlock && (
				<Warning>
					{__(
						'シンプルコピーブロックの中にシンプルコピーブロックを含めることはできません。削除してください。',
						// 'Simple copy blocks cannot be contained within simple copy blocks. Please delete it.',
						'vk-simple-copy-block'
					)}
				</Warning>
			)}
			<div {...innerBlocksProps} />
		</>
	);
}
