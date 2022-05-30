<script>
	//#ifdef MP
	import config from "./config.json"
	//#endif
	export default {
		onLaunch: function() {
			//#ifdef MP
			if (config.mall_id) {
				this.$http.setMallId(config.mall_id);
			}
			//#endif
			//#ifdef H5
			let pid = this.$http.getUrlParam('pid');
			if (pid) {
				this.$http.setPid(pid);
			}
			let mall_id = this.$http.getUrlParam('mall_id');
			if (mall_id) {
				this.$http.setMallId(mall_id);
			}
			//#endif
			
			this.$request({
					url: '/api/mall/setting'
			}).then(res => {
				if (res.code == 0) {
					uni.setStorageSync('TABBAR', res.data.tabbar);
					uni.setStorageSync('CHECKING_VERSIONS', res.data.checking_versions);
					uni.setStorageSync('MALL_SETTING',res.data.setting)
				}
			})
			
			
			this.$request({
				url: '/common/address/area-data'
			}).then(res => {
				if (res.code == 0) {
					uni.setStorageSync('AREA_DATA', res.data.list);
				}
			})
			//#ifndef MP
			//#endif
			this.$request({
				url: '/api/mall/alert-adv'
			}).then(res => {
				if (res.code == 0) {
					if (res.data.alert_data) {
						if (res.data.alert_data.status == 1) {
							uni.setStorageSync('ALERT_DATA', res.data.alert_data)
							if (res.data.alert_data.show_time == 1) {
								uni.setStorageSync('ALERT_LAUNCH_SHOW', 0)
							}
						} else {
							uni.removeStorageSync('ALERT_DATA')
							uni.removeStorageSync('ALERT_LAUNCH_SHOW')
							uni.removeStorageSync('ALERT_DATE')
						}
					}
				}
			})
		},


		onShow: function() {



		},
		onHide: function() {

		}
	}
</script>

<style lang="scss">
	@import url("/static/style/thorui.scss");

	/*每个页面公共css */
	* {
		box-sizing: border-box;
	}

	page {
		background-color: #f3f3f3;
	}

	.flex {
		display: -webkit-box;
		display: -webkit-flex;
		display: flex;
	}

	.flex-row {
		display: -webkit-box;
		display: -webkit-flex;
		display: flex;
		-webkit-box-orient: horizontal;
		-webkit-flex-direction: row;
		flex-direction: row;
	}

	.flex-col {
		display: -webkit-box;
		display: -webkit-flex;
		display: flex;
		-webkit-box-orient: vertical;
		-webkit-flex-direction: column;
		flex-direction: column;
	}

	.flex-grow-0 {
		min-width: 0;
		-webkit-box-flex: 0;
		-webkit-flex-grow: 0;
		-ms-flex-positive: 0;
		flex-grow: 0;
		-webkit-flex-shrink: 0;
		-ms-flex-negative: 0;
		flex-shrink: 0;
	}

	.flex-grow-1 {
		min-width: 0;
		-webkit-box-flex: 1;
		-webkit-flex-grow: 1;
		-ms-flex-positive: 1;
		flex-grow: 1;
		-webkit-flex-shrink: 1;
		-ms-flex-negative: 1;
		flex-shrink: 1;
	}

	.flex-x-center {
		display: -webkit-box;
		display: -webkit-flex;
		display: flex;
		-webkit-box-pack: center;
		-webkit-justify-content: center;
		-ms-flex-pack: center;
		justify-content: center;
	}

	.flex-y-center {
		display: -webkit-box;
		display: -webkit-flex;
		display: flex;
		-webkit-box-align: center;
		-webkit-align-items: center;
		-ms-flex-align: center;
		-ms-grid-row-align: center;
		align-items: center;
	}

	.flex-y-bottom {
		display: -webkit-box;
		display: -webkit-flex;
		display: flex;
		-webkit-box-align: end;
		-webkit-align-items: flex-end;
		-ms-flex-align: end;
		-ms-grid-row-align: flex-end;
		align-items: flex-end;
	}

	.flex-x-between {
		display: -webkit-box;
		display: -webkit-flex;
		display: flex;
		-webkit-box-align: center;
		-webkit-align-items: center;
		-ms-flex-align: center;
		-ms-grid-row-align: center;
		justify-content: space-between;
	}

	.flex-x-end {
		display: flex;
		align-items: center;
		justify-content: flex-end;
	}
</style>
