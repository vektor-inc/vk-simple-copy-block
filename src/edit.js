/**
 * WordPress dependencies
 */
import { PanelBody } from '@wordpress/components';
import {
	InspectorControls,
	InnerBlocks,
	useBlockProps,
} from '@wordpress/block-editor';
import { useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { CopyIcon } from './copy-icon';

export default function InnerCopyEdit( props ) {
	const { setAttributes, clientId } = props;

	useEffect( () => {
		setAttributes( { blockId: clientId } );
	}, [ clientId ] );

	return (
		<>
			<InspectorControls>
				<PanelBody title="注意事項">
					インナーコピーブロックの中にインナーコピーブロックは含めないでください。再利用ブロックに使用はできません。
				</PanelBody>
			</InspectorControls>
			<div { ...useBlockProps() }>
				<div className="vk-copy-inner-inner-blocks-wrapper">
					<InnerBlocks />
				</div>
				<div className="vk-copy-inner-button-wrapper">
					<div className="vk-copy-inner-button btn btn-primary">
						<span className="vk-copy-inner-button-icon">
							<CopyIcon />
						</span>
						<span className="vk-copy-inner-button-text">
							コピーする
						</span>
					</div>
				</div>
			</div>
		</>
	);
}
