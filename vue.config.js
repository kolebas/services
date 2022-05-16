module.exports = {
  publicPath: process.env.NODE_ENV === 'production',
  "transpileDependencies": [
    "vuetify",
	"babel"
  ],
	filenameHashing: true,
	publicPath: '/ahstep/services/',
  indexPath: 'app.php'
}