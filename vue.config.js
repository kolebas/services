module.exports = {
  "transpileDependencies": [
    "vuetify",
	"babel"
  ],
	filenameHashing: false,
	publicPath: '/ahstep/services_dev/',
	devServer: {
			proxy: 'https://portal.ahstep.ru',
    }
}