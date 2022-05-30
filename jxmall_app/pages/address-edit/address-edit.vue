<template>
	<tui-page>
		<view class="tui-addr-box">
			<form :report-submit="true">
				<tui-list-cell :hover="false" padding="0">
					<view class="tui-line-cell">
						<view class="tui-title">收货人</view>
						<input placeholder-class="tui-phcolor" class="tui-input" v-model="form.name" placeholder="请输入收货人姓名" maxlength="15"
						 type="text" />
					</view>
				</tui-list-cell>
				<tui-list-cell :hover="false" padding="0">
					<view class="tui-line-cell">
						<view class="tui-title">手机号码</view>
						<input placeholder-class="tui-phcolor" class="tui-input" v-model="form.mobile" placeholder="请输入收货人手机号码" maxlength="11"
						 type="text" />
					</view>
				</tui-list-cell>
				<tui-list-cell :arrow="true" padding="0">
					<view class="tui-line-cell">
						<view class="tui-title"><text class="tui-title-city-text">所在区域</text></view>
						<tui-address-picker @selected="selected">
							<input placeholder-class="tui-phcolor" class="tui-input" disabled v-model="form.area" placeholder="请选择区域"
							 maxlength="50" type="text" />
						</tui-address-picker>
					</view>
				</tui-list-cell>
				<tui-list-cell :arrow="true" padding="0">
					<view class="tui-line-cell">
						<view class="tui-title"><text class="tui-title-city-text">所在街道/镇</text></view>

						<picker range-key="name" @change="selectedTown" :range="town_list">

							<input v-if="town" placeholder-class="tui-phcolor" class="tui-input" disabled placeholder="请选择所在街道/镇" v-model="town.name"
							 maxlength="50" type="text" />
							<input v-if="!town" placeholder-class="tui-phcolor" class="tui-input" disabled placeholder="请选择所在街道/镇" maxlength="50"
							 type="text" />
						</picker>

					</view>
				</tui-list-cell>
				<tui-list-cell :hover="false" padding="0">
					<view class="tui-line-cell">
						<view class="tui-title">详细地址</view>
						<input placeholder-class="tui-phcolor" class="tui-input" v-model="form.detail" placeholder="请输入详细的收货地址" maxlength="50"
						 type="text" />
					</view>
				</tui-list-cell>
				<!-- 默认地址 -->
				<tui-list-cell :hover="false" padding="0">
					<view class="tui-swipe-cell">
						<view>设为默认地址</view>
						<switch style="transform:scale(0.7)"  :checked="form.is_default==1?true:false" @change="defaultChange" color="#EB0909" class="tui-switch-small" />
					</view>
				</tui-list-cell>
				<!-- 保存地址 -->
				<view class="tui-addr-save">
					<tui-button shadow type="danger" height="88rpx" shape="circle" @click="save">保存地址</tui-button>
				</view>
				<view class="tui-del" v-if="this.form.id">
					<tui-button shadow type="gray" height="88rpx" shape="circle" @click="deleteAddress">删除地址</tui-button>
				</view>
			</form>
		</view>

	</tui-page>
</template>

<script>
	export default {
		data() {
			return {
				form: {
					id: '',
					name: '',
					mobile: '',
					address: '',
					city_code: '',
					province_code: '',
					county_code: '',
					town_code: '',
					province_name: '',
					county_name: '',
					town_name: '',
					city_name: '',
					area: '',
					detail: '',
					is_default: 0,
				},
				town: null,
				town_list: [],
			}
		},
		onLoad: function(options) {
			if (options.id) {
				this.form.id = options.id;
				this.getAddress();
			}
		},
		methods: {
			selectedTown(e) {
				console.log(e);
				this.town = this.town_list[e.detail.value];
				console.log(this.town);
			},
			getAddress() {
				this.$request({
					url: '/api/user/address-edit',
					data: {
						id: this.form.id
					}
				}).then(res => {
					if (res.code == 0) {
						this.form = res.data.address;
						this.town = {
							name: this.form.town_name,
							code: this.form.town_code
						}
					}
				})
			},
			selected(e) {
				this.town = null;
				let data = {
					area: e.area,
					city_name: e.city_name,
					province_name: e.province_name,
					county_name: e.county_name,
					//town_name: e.town_name,
					city_code: e.city_code,
					province_code: e.province_code,
					county_code: e.county_code,
					//town_code: e.town_code,
				}
				Object.assign(this.form, data)
				this.$request({
					url: '/common/address/town-list',
					data: {
						county_code: e.county_code
					}
				}).then(res => {

					if (res.code == 0) {
						this.town_list = res.data.list
						this.town = this.town_list[0]
					}
				})

			},
			save() {
				if (!this.town) {
					uni.showToast({
						title: '请选择街道'
					})
					return;
				}
				uni.showLoading({
					title: '正在保存...'
				})
				this.form.address = this.form.area + " " + this.town.name + " " + this.form.detail
				this.form.town_code = this.town.code;
				this.form.town_name = this.town.name;
				this.$request({
					url: '/api/user/address-edit',
					data: this.form,
					method: 'post'
				}).then(res => {
					uni.hideLoading();
					if (res.code == 0) {
						uni.showToast({
							title: '保存成功'
						})
						setTimeout(() => {
							uni.navigateBack({
								delta: 1
							})
						}, 1000)
					}
				})
			},
			deleteAddress() {
				this.$request({
					url: '/api/user/address-delete',
					data: {
						id: this.form.id
					},
					method: post
				}).then(res => {
					if (res.code == 0) {
						uni.showModal({
							title: '提示',
							content: res.msg,
							success: function(e) {
								uni.navigateBack({
									delta: 1
								})
							}
						})
					}
				})
			},
			defaultChange(e) {
				this.form.is_default = this.form.is_default == 0 ? 1 : 0
			}
		}
	}
</script>

<style>
	.tui-addr-box {
		padding: 20rpx 0;
	}

	.tui-line-cell {
		width: 100%;
		padding: 24rpx 30rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
	}

	.tui-title {
		width: 180rpx;
		font-size: 28rpx;
	}

	.tui-title-city-text {
		width: 180rpx;
		height: 40rpx;
		display: block;
		line-height: 46rpx;
	}

	.tui-input {
		width: 500rpx;
	}

	.tui-input-city {
		flex: 1;
		height: 40rpx;
		font-size: 28rpx;
		padding-right: 30rpx;
	}

	.tui-phcolor {
		color: #ccc;
		font-size: 28rpx;
	}

	.tui-cell-title {
		font-size: 28rpx;
	}

	.tui-addr-label {
		margin-left: 70rpx;
	}

	.tui-label-item {
		width: 76rpx;
		height: 40rpx;
		border: 1rpx solid rgb(136, 136, 136);
		border-radius: 6rpx;
		font-size: 26rpx;
		text-align: center;
		line-height: 40rpx;
		margin-right: 20rpx;
		display: inline-block;
		transform: scale(0.9);
	}

	.tui-label-active {
		background: #E41F19;
		border-color: #E41F19;
		color: #fff;
	}

	.tui-swipe-cell {
		width: 100%;
		display: flex;
		justify-content: space-between;
		align-items: center;
		background: #fff;
		padding: 10rpx 24rpx;
		box-sizing: border-box;
		border-radius: 6rpx;
		overflow: hidden;
		font-size: 28rpx;
	}

	.tui-switch-small {
		transform: scale(0.8);
		transform-origin: 100% center;
	}

	/* #ifndef H5 */
	.tui-switch-small .wx-switch-input {
		margin: 0 !important;
	}

	/* #endif */

	/* #ifdef H5 */
	>>>uni-switch .uni-switch-input {
		margin-right: 0 !important;
	}

	/* #endif */

	.tui-addr-save {
		padding: 24rpx;
		margin-top: 100rpx;
	}

	.tui-del {
		padding: 24rpx;
	}
</style>
