import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import './style.scss';
import { CopyIcon } from './copy-icon';
import json from './block.json';
import edit from './edit';
import save from './save';

const { name, ...settings } = json;

registerBlockType( name, {
	icon: <CopyIcon />,
	...settings,
	save,
	edit,
} );
