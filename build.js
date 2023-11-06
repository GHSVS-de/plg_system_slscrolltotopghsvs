#!/usr/bin/env node
const path = require('path');

/* Configure START */
const pathBuildKram = path.resolve("../buildKramGhsvs");
const updateXml = `${pathBuildKram}/build/update_no-changelog.xml`;
// const changelogXml = `${pathBuildKram}/changelog.xml`;
const releaseTxt = `${pathBuildKram}/build/release_no-changelog.txt`;
/* Configure END */

const replaceXml = require(`${pathBuildKram}/build/replaceXml.js`);
const helper = require(`${pathBuildKram}/build/helper.js`);
const pc = require(`${pathBuildKram}/node_modules/picocolors`);

const {
	name,
	filename,
	version,
} = require("./package.json");

const manifestFileName = `${filename}.xml`;
const Manifest = `${__dirname}/package/${manifestFileName}`;
const pathMedia = `./media`;

let replaceXmlOptions = {};
let zipOptions = {};
let from = "";
let to = "";

(async function exec()
{
	let cleanOuts = [
		`./package`,
		`./dist`,
	];
	await helper.cleanOut(cleanOuts);

	console.log(pc.cyan(pc.bold(`Be patient! Some copy actions!`)));

	from = pathMedia;
	to = `./package/media`;
	await helper.copy(from, to)

	from = './src'
	to = './package'
	await helper.copy(from, to)

	await helper.mkdir('./dist');

	const zipFilename = `${name}-${version}.zip`;

	replaceXmlOptions = {
		"xmlFile": Manifest,
		"zipFilename": zipFilename,
		"checksum": "",
		"dirname": __dirname
	};

	await replaceXml.main(replaceXmlOptions);
	await helper.copy(`${Manifest}`, `./dist/${manifestFileName}`);

	// Create zip file and detect checksum then.
	const zipFilePath = path.resolve(`./dist/${zipFilename}`);

	zipOptions = {
		"source": path.resolve("package"),
		"target": zipFilePath
	};
	await helper.zip(zipOptions)

	const Digest = 'sha256'; //sha384, sha512
	const checksum = await helper.getChecksum(zipFilePath, Digest)
  .then(
		hash => {
			const tag = `<${Digest}>${hash}</${Digest}>`;
			console.log(pc.green(pc.bold((`Checksum tag is: ${tag}`))));
			return tag;
		}
	)
	.catch(error => {
		console.log(error);
		console.log(pc.red(pc.bold((`Error while checksum creation. I won't set one!`))));
		return '';
	});

	replaceXmlOptions.checksum = checksum;

	// Bei diesen werden zuerst Vorlagen nach dist/ kopiert und dort erst "replaced".
	for (const file of [updateXml, releaseTxt])
	{
		from = file;
		to = `./dist/${path.win32.basename(file)}`;
		await helper.copy(from, to);

		replaceXmlOptions.xmlFile = path.resolve(to);
		await replaceXml.main(replaceXmlOptions);
	}

	cleanOuts = [
		`./package`,
	];
	await helper.cleanOut(cleanOuts).then(
		answer => console.log(pc.cyan(pc.bold((pc.bgRed(
			`Finished. Good bye!`)))))
	);
})();
