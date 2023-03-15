/**
 * WordPress dependencies
 */
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { getBlockTypes, hasBlockSupport } from '@wordpress/blocks';

export default function Edit() {
	const blockProps = useBlockProps();

	// インナーブロックで許可するブロック vk-simple-copy-blockは除く
	const allowBlockTypes = [];
	getBlockTypes().forEach((blockType) => {
		if (hasBlockSupport(blockType, 'inserter', true) && !blockType.parent) {
			allowBlockTypes.push(blockType);
		}
	});
	const AllBlockName = allowBlockTypes.map((blockType) => blockType.name);
	const ALLOWED_BLOCKS = AllBlockName.filter(
		(item) => !item.match(/vk-simple-copy-block/)
	);

	return (
		<>
			<div {...blockProps}>
				<InnerBlocks
					allowedBlocks={ALLOWED_BLOCKS}
					templateLock={false}
				/>
			</div>
		</>
	);
}
