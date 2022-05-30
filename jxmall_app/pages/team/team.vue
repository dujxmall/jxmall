<template>
	<tui-page>

		<view>
			<view class="" v-if="list.length" v-for="item in list" style="background-color: #FFFFFF;border-bottom: solid #f0f0f0 1rpx;padding-bottom: 20rpx;">
				<view class="flex-x-between">
					<view class="flex-y-center">
						<view>
							<image :src="item.avatar_url" style="height: 80rpx;width: 80rpx;border-radius: 50%;" mode="aspectFill"></image>
						</view>
						<view style="font-size: 11pt;font-weight: bold;padding: 0 20rpx;">
							{{item.nickname}}
						</view>
					</view>
					<view style="font-size: 9pt;padding: 0 30rpx;">
						<view class="flex-y-center">
							<view style="text-align: end;width: 150rpx;">消费：</view>
							<view style="color: #F04D1E;">{{item.self_price}}</view>
						</view>
						<view class="flex-y-center">
							<view style="text-align: end;width: 150rpx;">团队消费： </view>
							<view style="color: #F04D1E;">{{item.team_price}}</view>
						</view>
					</view>
				</view>
				<view class="flex-y-center" style="font-size: 9pt;padding: 0 10rpx;height: 50rpx;color: #444444;">
					加入时间：{{item.created_at}}
				</view>
				<view class="flex-y-center" style="font-size: 9pt;">
					<view class="flex-y-center" style="padding: 0 10rpx;">
						<view>累计佣金：</view>
						<view style="color: #F04D1E;">{{item.total_price}}</view>
					</view>
					<view class="flex-y-center" style="padding: 0 10rpx;">
						<view>当前佣金：</view>
						<view style="color: #F04D1E;">{{item.price}}</view>
					</view>
					<view class="flex-y-center" style="padding: 0 10rpx;">
						<view>团队人数：</view>
						<view style="color: #F04D1E;">{{item.count}}</view>
					</view>
				</view>
			</view>
		</view>
	</tui-page>
</template>

<script>
	export default {
		data() {
			return {
				page: 1,
				is_no_more: false,
				list: [],

			}
		},
		onReachBottom: function() {

			this.getList();
		},
		onLoad: function() {
			this.getList();
		},
		methods: {

			getList() {

				if (this.is_no_more) {
					return;

				}

				this.$request({
					url: '/api/user/team',
					data: {
						page: this.page,

					}
				}).then(res => {
					if (res.code == 0) {
						if (this.page == 0) {
							this.list = res.data.list;
						} else {
							this.list = this.list.concat(res.data.list);
						}
						if (res.data.pagination.page_count > this.page) {
							this.page++;
						} else {
							this.is_no_more = true;

						}

					}
				})
			}
		}
	}
</script>

<style>

</style>
