<template>
	<tui-page>
		<tui-tabs :tabs="navbar" selectedColor="#EB0909" sliderBgColor="#EB0909" :currentTab="currentTab" @change="tabChange"
		 itemWidth="33.333%"></tui-tabs>
		<view style="padding-top: 10rpx;">
			<view class="flex-row flex-x-between" style="background-color: #FFFFFF;height: 130rpx;padding: 0 20rpx;border-bottom: solid #F3F3F3 1rpx;"
			 v-if="list.length>0" v-for="item in list">
				<view style=" width:70%;">
					<view style="font-size: 11pt;overflow: hidden;text-overflow: ellipsis;word-wrap: normal;word-break: break-all;white-space: nowrap;">
						<text v-if="item.type==0">佣金提现：</text> <text v-if="item.type==1">余额提现：</text><text><text style="color:#EB0909">{{item.cash_price}}</text>
							元</text>
					</view>
					<view style="color: #B3B3B3;font-size: 10pt;margin-top: 20rpx;">{{item.created_at}}</view>
				</view>
				<view style="width: 30%;text-align: end;">
					<view>
						<text style="color: #EB0909;padding: 0 10rpx;font-weight: bold;"> {{item.price}} </text>
						<text style="font-size: 9pt;">元</text>
					</view>
					<view style="font-size: 9pt;" v-if="item.status==0">
						待审核
					</view>
					<view style="font-size: 9pt;" v-if="item.status==1">
						通过审核
					</view>
					<view style="font-size: 9pt;" v-if="item.status==3">
						拒绝
					</view>
				</view>

			</view>
		</view>

		<!--加载loadding-->
		<tui-loadmore v-if="loadding" :index="3" type="red"></tui-loadmore>
		<tui-nomore v-if="is_no_more" backgroundColor="#f5f5f5"></tui-nomore>
		<!--加载loadding-->
	</tui-page>
</template>

<script>
	export default {
		data() {
			return {
				currentTab: 0,
				backgroundColor: "linear-gradient(90deg, rgb(255, 118, 38), rgb(252, 30, 82))",
				navbar: [{
					name: "全部"
				}, {
					name: "待打款"
				}, {
					name: "已打款"
				}, ],
				loadding: false,
				status: 0,
				page: 1,
				is_no_more: false,
				list: [],

			}
		},
		onLoad: function(e) {

			this.getList();
		},
		onReachBottom: function(e) {
			this.getList();
		},
		methods: {
			tabChange(e) {


				this.currentTab = e.index
				this.status = e.index
				this.page = 1;
				this.is_no_more = false;
				this.list = [];
				this.getList();

			},
			getList() {
				if (this.is_no_more) {
					uni.showToast({
						title: '已加载完所有数据'
					})
					return;
				}
				this.loadding = true;
				this.$request({
					url: "/api/cash/list",
					data: {
						status: this.status,
						page: this.page
					}
				}).then(res => {
					this.loadding = false;
					if (this.page == 1) {
						this.list = res.data.list;
					} else {
						this.list = this.list.concat(res.data.list);
					}
					if (res.data.pagination.page_count > this.page) {
						this.page++;

					} else {
						this.is_no_more = true;
					}

				})

			},

		}
	}
</script>

<style>

</style>
