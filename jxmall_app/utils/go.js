export default {
	go: function(url) {
		uni.navigateTo({
			url
		})
	},
	redirect: function(url) {
		uni.redirectTo({
			url
		})
	},
	reLaunch: function(url) {
		 
		uni.reLaunch({
			url
		})
	},
	back: function(delta = 1) {
		uni.navigateBack({
			delta: delta
		})
	}
}
