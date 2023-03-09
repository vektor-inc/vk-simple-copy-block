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
import { store as blocksStore, getBlockTypes, hasBlockSupport, isReusableBlock, isTemplatePart } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';

/**
 * Internal dependencies
 */
import { CopyIcon } from './copy-icon';

export default function InnerCopyEdit( props ) {
	const { setAttributes, clientId } = props;

	// 親ブロックに再利用ブロックが存在するか
	const { isParentsSynced } = useSelect(
		( select ) => {
			const { getBlockParents, getBlockName } = select( blockEditorStore );
			const { getBlockType } = select( blocksStore );
			const parentsIdArray = getBlockParents( clientId );
			let _isParentsSynced = false;
			parentsIdArray.forEach( ( _clientId ) => {
				const blockName = getBlockName( _clientId );
				const blockType = getBlockType( blockName );
				const isSynced =
				isReusableBlock( blockType ) || isTemplatePart( blockType );
				if ( isSynced ) {
					_isParentsSynced = true;
				}
			} );

			return {
				isParentsSynced: _isParentsSynced,
			};
		},
		[ clientId ]
	);

	useEffect( () => {
		if ( ! isParentsSynced ) {
			setAttributes( { blockId: clientId } );
		}
	}, [ clientId, setAttributes, isParentsSynced ] );

	// インナーブロックで許可するブロック vk-copy-inner-blockは除く
	const allowBlockTypes = [];
	getBlockTypes().forEach( ( blockType ) => {
		if (
			hasBlockSupport( blockType, 'inserter', true ) &&
			! blockType.parent
		) {
			allowBlockTypes.push( blockType );
		}
	} );
	const AllBlockName = allowBlockTypes.map( ( blockType ) => blockType.name );
	const ALLOWED_BLOCKS = AllBlockName.filter(
		( item ) => ! item.match( /vk-copy-inner-block/ )
	);

	return (
		<>
			<div { ...useBlockProps() }>
				{ isParentsSynced && <Warning>再利用ブロックにインナーコピーブロックを含めることはできません。通常のブロックへ変換するか、完全に削除してください。</Warning> }
				<div className="vk-copy-inner-inner-blocks-wrapper">
					<InnerBlocks
						allowedBlocks={ ALLOWED_BLOCKS }
						templateLock={ false }
					/>
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
