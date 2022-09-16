module.exports = { 
  publicPath: process.env.NODE_ENV === 'production',
  "transpileDependencies": [
    "vuetify",
	"babel"
  ],
  chainWebpack: (config) => {
    config.plugin("copy").tap(([options]) => {
      options[0].ignore.push("ajax/**");
      return [options];
    });
  },
	filenameHashing: true,
	// eslint-disable-next-line no-dupe-keys
	publicPath: '/ahstep/services/',
  indexPath: 'app.php',
}