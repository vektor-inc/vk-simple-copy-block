module.exports = {
	extends: ['plugin:@wordpress/eslint-plugin/recommended'],
	env: {
		browser: true,
	},
	rules: {
		'import/no-unresolved': 'off',
		'import/no-extraneous-dependencies': 'off',
		camelcase: 'off',
		'@wordpress/no-unsafe-wp-apis': 'off',
	},
};
