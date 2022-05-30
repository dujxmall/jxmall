<template>
	<view>
		<tui-tabs :tabs="navbar" selectedColor="#EB0909" sliderBgColor="#EB0909" :currentTab="currentTab" @change="tabChange"
		 itemWidth="33.333%"></tui-tabs>
		<view style="padding-top: 10rpx;">
		
			<view v-if="list.length>0" v-for="item in list"
				style="border-bottom: solid #F3F3F3 1rpx;background-color: #FFFFFF;padding: 20rpx;padding-top: 20rpx;padding-bottom: 5rpx;">
				<view class="flex-row flex-x-between" style="">
					<view style=" width:70%;">
						<view
							style="font-size: 11pt;overflow: hidden;text-overflow: ellipsis;word-wrap: normal;word-break: break-all;white-space: nowrap;">
							{{item.content}}
						</view>
					</view>
					<view style="width: 30%;text-align: end;">
						<text style="color: #EB0909;padding: 0 10rpx;font-weight: bold;" v-if="item.type==1">
							+{{item.money}} </text>
						<text style="color: #EB0909;padding: 0 10rpx;font-weight: bold;" v-if="item.type==0">
							-{{item.money}} </text>
						<text style="font-size: 9pt;">元</text>
					</view>
				</view>
				<view class="flex-x-between">
					<view style="color: #B3B3B3;font-size: 10pt;margin-top: 20rpx;">当前：{{item.current}}</view>
					<view style="color: #B3B3B3;font-size: 10pt;margin-top: 20rpx;">{{item.created_at}}</view>
				</view>
			</view>
		
		</view>
		<!--加载loadding-->
		<tui-loadmore v-if="loadding" :index="3" type="red"></tui-loadmore>
		<tui-nomore v-if="is_no_more" backgroundColor="#f5f5f5"></tui-nomore>
		<!--加载loadding-->
	</view>
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
					name: "支出"
				}, {
					name: "收入"
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
					url: "/api/finance/balance-log",
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
