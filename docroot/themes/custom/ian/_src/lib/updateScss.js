'use strict';

const fs = require('fs');
const _ = require('lodash');
const collection = [
	{
		'name': 'atoms',
		'searchName': 'atoms',
		'dir': './assets/scss/atoms',
		'recursive': true
	},
	{
		'name': 'common',
		'searchName': 'common',
		'dir': './assets/scss/common',
		'recursive': true
	},
	{
		'name': 'layout',
		'searchName': 'layout',
		'dir': './assets/scss/layout',
		'recursive': true
	},
	{
		'name': 'molecules',
		'searchName': 'molecules',
		'dir': './assets/scss/molecules',
		'recursive': true
	},
	{
		'name': 'organisms',
		'searchName': 'organisms',
		'dir': './assets/scss/organisms',
		'recursive': true
	},
	{
		'name': 'templates',
		'searchName': 'templates',
		'dir': './assets/scss/templates',
		'recursive': true
	}
];

const ignored = [
	'.git',
	'node_modules',
	'.DS_Store',
	'thumbs.db',
	'Thumbs.db',
	'_all.scss'
];

/*

This script generates an _all.scss in each of the above directories, with imports for all scss files in those directories. This is so that new components are easily accommodated without extensively
editing main.scss

 */

collection.forEach(function (data) {

	let ff = fs.readdirSync(data.dir);

	ff = _.difference(ff, ignored);

	ff = ff.filter(hbs => hbs.indexOf('.scss') !== -1);

	var names = [];
	var finalScssFile = '';
	var finalPath = './assets/scss/' + data.name + '/_all.scss';

	ff.forEach(function (entry) {
		// Add names to be added to .scss file
		var regex = new RegExp('^.+' + data.searchName + '/');

		if (!/^_+/.test(entry)/* && data.name !== 'templates'*/) {
			entry = '_' + entry;
		}

		names.push(entry);
	});

	names.sort();

	names.forEach(function (name) {
		var importPath = '@import \'';

		name = name.replace('.scss', '');

		importPath = importPath + name;
		finalScssFile = finalScssFile + importPath + '\';\n';
	});

	if (finalScssFile !== '') {

		fs.writeFileSync(finalPath, finalScssFile, function (err) {
			if (err) {
				throw err;
			}
		});

	}

});

function singular(str) {
	return str.replace(/s$/, '');
}

function properName(str) {
	let name = str[0].toUpperCase() + str.substring(1);
	return singular(name);
}
