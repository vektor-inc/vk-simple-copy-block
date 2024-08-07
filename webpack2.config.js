module.exports = [
	{
		entry: {
			'react-jsx-runtime': {
				import: 'react/jsx-runtime',
			},
		},
		output: {
			path: __dirname + '/build/react-jsx-runtime/',
			filename: 'react-jsx-runtime.js',
			library: {
				name: 'ReactJSXRuntime',
				type: 'window',
			},
		},
		externals: {
			react: 'React',
		},
	},
];
