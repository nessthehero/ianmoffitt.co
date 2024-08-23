const markdownIt = require("markdown-it");

module.exports = function(eleventyConfig) {

	// Templating
	eleventyConfig.setTemplateFormats("html,njk,pug");

    // Pug
    // eleventyConfig.setPugOptions({ debug: true });

    // Assets
    eleventyConfig.addPassthroughCopy("src/assets/**/*.*");

    // Watch
    eleventyConfig.addWatchTarget("./src/_includes/**/*.njk");
    eleventyConfig.addWatchTarget("./src/_includes/**/*.pug");
    eleventyConfig.addWatchTarget("./src/assets/css/**/*.css");

    // Global Data
    eleventyConfig.addGlobalData("getYear", () => new Date().getFullYear());

    // Markdown
    let mdOptions = {
        html: true,
        breaks: true,
        linkify: true
    };

    eleventyConfig.setLibrary("md", markdownIt(mdOptions));

	const md = new markdownIt({
		html: true
	});

	eleventyConfig.addPairedShortcode("markdown", (content) => {
		return md.render(content);
	});

    return {
        dir: {
			input: "src",
            output: "dist"
        }
    }

};
