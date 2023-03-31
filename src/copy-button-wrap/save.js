/**
 * WordPress dependencies
 */
import { useInnerBlocksProps, useBlockProps } from '@wordpress/block-editor';

export default function save(props) {
	const { attributes } = props;
	const { buttonAlign } = attributes;

	const blockProps = useBlockProps.save({
		style: {
			display: buttonAlign ? 'flex' : undefined,
			justifyContent: buttonAlign,
		},
	});
	const innerBlocksProps = useInnerBlocksProps.save(blockProps);

	return <div {...innerBlocksProps} />;
}
