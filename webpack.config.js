const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const Main = defaultConfig;
const Frontend = {
	...defaultConfig,
	entry: {
		frontend: "./src/frontend.js"
	}
};
module.exports = [Main, Frontend];
