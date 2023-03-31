/**
 * WordPress dependencies
 */
import {
	useInnerBlocksProps,
	useBlockProps,
	BlockControls,
	JustifyContentControl,
} from '@wordpress/block-editor';

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const { buttonAlign } = attributes;

	const ALLOWED_BLOCKS = ['vk-simple-copy-block/copy-button'];
	const TEMPLATE = [['vk-simple-copy-block/copy-button']];

	const blockProps = useBlockProps({
		style: {
			display: buttonAlign ? 'flex' : undefined,
			justifyContent: buttonAlign,
		},
	});

	const innerBlocksProps = useInnerBlocksProps(blockProps, {
		allowedBlocks: ALLOWED_BLOCKS,
		templateLock: 'all',
		template: TEMPLATE,
	});

	return (
		<>
			<BlockControls group="block">
				<JustifyContentControl
					value={buttonAlign}
					onChange={(value) => setAttributes({ buttonAlign: value })}
					allowedControls={['left', 'center', 'right']}
				/>
			</BlockControls>
			<div {...innerBlocksProps} />
		</>
	);
}
