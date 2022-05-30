<template>
	<view class="body" style="font-size: 10pt;">
		<tui-tabs :itemWidth="'50%'" style="width: 100%;" :tabs="tabs" :currentTab="currentTab" selectedColor="#EB0909"
		 sliderBgColor="#EB0909" @change="change"></tui-tabs>
		<view style="font-size: 10pt;" v-if="currentTab==0">
			<view v-if="setting&&setting.price.is_cash==1">
				<view class="balance" v-if="info">可提现金额：{{info.price}}</view>
				<view class="sub-money">
					<text>￥</text><input type="digit" placeholder="输入提现金额" v-model="cash_price" />
				</view>
				<view class="way">
					<view class="pay-list">
						<view class="pay-item" v-for="(item,index) in setting.price.cash_type" @tap="selectedPay(item)" :class="selected==item.cash_type?'active':''">
							<image class="pay-img" :src="item.icon"></image>
							<view>{{item.name}}</view>
							<image v-if="selected==item.cash_type" class="select-img" src="/static/img/mch/icon-share-selected.png" />
						</view>
					</view>
				</view>
				<view class="info" v-if="selected">
					<block v-if="selected=='bank'">
						<view class="name">姓名<text>*</text><input type="text" placeholder="请输入正确姓名" name="name" v-model="name" /></view>
						<view class="account">开户行<text>*</text><input type="text" placeholder="请输入正确的银行名称" name="bank_name" v-model="bank_name" /></view>
						<view class="account">账户<text>*</text><input type="text" placeholder="请输入正确的银行账号" name="account" v-model="account" /></view>
					</block>
					<block v-if="selected=='wechat'">
						<view class="account">账户<text>*</text><input type="text" placeholder="请输入你的微信号" name="account" v-model="account" /></view>
					</block>

					<block v-if="selected=='alipay'">
						<view class="name">姓名<text>*</text><input type="text" placeholder="请输入支付宝户名" v-model="name" /></view>
						<view class="account">账户<text>*</text><input type="text" placeholder="请输入正确支付宝的账户" v-model="account" /></view>
					</block>

				</view>
				<view class="tips">

					<view>
						可提现金额：{{setting.price.min_price}}~{{info.price}}元
					</view>
					<view>
						最低提现金额：{{setting.price.min_price}}元
					</view>
					<view>
						提现服务费：{{setting.price.service_price}}%
					</view>
				</view>
			</view>
			<view v-if="setting&&setting.price.is_cash==0">
				<view>暂未开启佣金提现</view>
			</view>
			<!-- <button form-type="submit" class="sub">提交申请</button> -->
		</view>
		<view style="font-size: 10pt;" v-if="currentTab==1">
			<view v-if="setting&&setting.balance.is_cash==1">
				<view class="balance">可提现金额：{{info.money}}</view>
				<view class="sub-money">
					<text>￥</text><input type="digit" placeholder="输入提现金额" v-model="cash_price" />
				</view>

				<view class="way">
					<view class="pay-list">
						<view class="pay-item" v-for="(item,index) in setting.balance.cash_type" @tap="selectedPay(item)" :class="selected==item.cash_type?'active':''">
							<image class="pay-img" :src="item.icon"></image>
							<view>{{item.name}}</view>
							<image v-if="selected==item.cash_type" class="select-img" src="/static/img/mch/icon-share-selected.png" />
						</view>
					</view>
				</view>
				<view class="info" v-if="selected">
					<block v-if="selected=='bank'">
						<view class="name">姓名<text>*</text><input type="text" placeholder="请输入正确姓名" v-model="name" /></view>
						<view class="account">开户行<text>*</text><input type="text" placeholder="请输入正确的银行名称" v-model="bank_name" /></view>
						<view class="account">账户<text>*</text><input type="text" placeholder="请输入正确的银行账号" v-model="account" /></view>
					</block>
					<block v-if="selected=='alipay'">
						<view class="name">姓名<text>*</text><input type="text" placeholder="请输入支付宝户名" v-model="name" /></view>
						<view class="account">账户<text>*</text><input type="text" placeholder="请输入正确支付宝的账户" v-model="account" /></view>
					</block>
				</view>



				<view class="tips">
					<view>
						可提现金额：{{setting.balance.min_price}}~{{info.price}}元
					</view>
					<view>
						最低提现金额：{{setting.balance.min_price}}元
					</view>
					<view>
						提现服务费：{{setting.balance.service_price}}%
					</view>
				</view>
			</view>
			<view v-if="setting&&setting.balance.is_cash==0">
				<view>暂未开启余额提现</view>
			</view>
		</view>
		<view class="flex-x-center" style="margin-top: 100rpx;">
			<tui-button :width="'600rpx'" height="88rpx" type="danger" shape="circle" shadow @click="submit">确认提现</tui-button>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				currentTab: 0,
				status: -1,
				page: 1,
				type: 0,
				list: [],
				tabs: [{
					name: "佣金提现"
				}, {
					name: "余额提现"
				}, ],
				pay_list: [],
				info: null,
				cash_price: null,
				selected: null,
				name: "",
				bank_name: "",
				account: "",
				setting: null,
				share_setting: {}, //分销中心设置
				price: 0 //可提现金额
			}
		},
		onLoad: function(e) {
			this.getCashInfo();
		},
		onShow() {


		},
		methods: {
			change(e) {
				this.currentTab = e.index
				this.type = e.index

			},
			getCashInfo() {

				uni.showLoading({
					title: '正在加载...'
				})
				this.$request({
					url: "/api/cash/cash-info"
				}).then(res => {
					uni.hideLoading()
					if (res.code == 0) {
						this.info = res.data.info
						this.setting = res.data.setting
					}
					if (res.code == 1) {
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

			selectedPay(row) {
				console.log(row);
				this.selected = row.cash_type;
				this.name = "";
				this.no = "";
				this.bank_name = "";
			},
			submit() {
				let name = this.name;
				let account = this.account;
				let bank_name = this.bank_name;
				if (!this.cash_price) {
					uni.showToast({
						title: "提现金额不能为空",
						icon: "none"
					});
					return;
				}

				if (this.type == 0) {
					if (this.cash_price > Number(this.info.price)) {
						uni.showToast({
							title: "提现金额不能超过" + this.info.price + "元",
							icon: "none"
						});

						return;
					}
					if (this.cash_price < this.setting.price.min_price) {
						uni.showToast({
							title: "提现金额不能小于" + this.setting.price.min_price + "元",
							icon: "none"
						});
						return;
					}
				}
				if (this.type == 1) {
					if (this.cash_price > this.info.money) {
						uni.showToast({
							title: "提现金额不能超过" + this.info.money + "元",
							icon: "none"
						});
						return;
					}
					if (this.cash_price < this.setting.balance.min_price) {
						uni.showToast({
							title: "提现金额不能小于" + this.setting.balance.min_price + "元",
							icon: "none"
						});
						return;
					}
				}
				if (this.selected == 'bank') {
					if (this.name == "") {
						uni.showToast({
							title: "姓名不能为空",
							icon: "none"
						});
						return;
					}
					if (this.selected == 'bank' && this.bank_name == "") {
						uni.showToast({
							title: "开户行不能为空",
							icon: "none"
						});
						return;
					}
					if (this.account == "") {
						uni.showToast({
							title: "账号不能为空",
							icon: "none"
						});
						return;
					}
				}
				uni.showLoading({
					title: "正在提交"
				});
				this.$request({
					url: "/api/cash/apply",
					method: 'POST',
					data: {
						cash_price: this.cash_price,
						name: this.name,
						account: this.account,
						bank_name: this.bank_name,
						cash_type: this.selected,
						type: this.type
					}
				}).then((res) => {
					uni.hideLoading();
					uni.showModal({

						title: '提示',
						content: res.msg,
						icon: "none",
						success: function(e) {
							if (e.confirm) {
								uni.navigateBack({
									delta: 1
								})
							}
						}
					})
					if (res.code == 0) {

					}
				})

			},


		}
	}
</script>

<style scoped>
	.balance {
		background-color: #FFFFFF;

		padding: 25rpx;
		border-top: 1px solid #F0F0F0;
		border-bottom: 1px solid #F0F0F0;
	}

	.sub-money {
		padding: 25rpx;
		display: flex;
		align-items: center;
		background-color: #FFFFFF;
		border-bottom: 1px solid #F0F0F0;
	}

	.sub-money>text {

		color: #EE4521;
	}

	.sub-money>input {
		margin-left: 20rpx;
	}

	.tips {
		color: #555555;
		padding: 25rpx;
		font-size: 8pt;
	}

	.way {
		background-color: #FFFFFF;
		padding: 25rpx;

	}

	.pay-list {
		display: flex;
		margin-top: 30rpx;
		flex-wrap: wrap;
		justify-content: space-between;
		padding: 0 35rpx;
	}

	.pay-item {
		min-width: 140rpx;
		text-align: center;
		display: flex;
		justify-content: center;
		align-items: center;
		border: 1px solid #e1e1e1;
		padding: 10rpx 20rpx;
		position: relative;
		margin-bottom: 20rpx;
	}

	.pay-img {
		width: 40rpx;
		height: 40rpx;
		margin-right: 10rpx;
	}

	.select-img {
		width: 32rpx;
		height: 32rpx;
		position: absolute;
		right: -2rpx;
		bottom: -2rpx;
	}

	.active {
		border: 1px solid #EE4521;
		color: #EE4521;
	}

	.sub {
		width: 85vw;
		height: 90rpx;
		line-height: 90rpx;
		color: #FFFFFF;
		margin-top: 40rpx;
		font-size: 34rpx;
		background-color: #EE4521;
	}

	.info>view {
		display: flex;
		align-items: center;
		background-color: #FFFFFF;
		margin-top: 20rpx;
		padding: 25rpx;

	}

	.info>view>text {
		color: #EE4521;
	}

	.info>view>input {
		margin-left: 20rpx;
	}
</style>
