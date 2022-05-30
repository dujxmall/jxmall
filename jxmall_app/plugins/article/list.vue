<template>
	<tui-page>
		<tui-tabs v-if="cat_list.length" style="width: 100%;" :itemWidth="itemWidth+'%'" :tabs="cat_list" :currentTab="currentTab"
		 selectedColor="#EB0909" sliderBgColor="#EB0909" @change="change"></tui-tabs>
		<view style="padding: 0 20rpx;">
			<!--  文章 -->
			<view style="margin:40rpx 0;">
				<view v-if="list.length" v-for="item in list" @click="detail(item.id)" class="flex-row" style="background-color: #FFFFFF;padding: 20px;box-shadow: 0 0 10px 0px #d2d2d2;border-radius: 8px;margin-top: 20px;height: 300rpx;box-sizing: border-box;">
					<view style="width: 60%;">
						<view style="color: #3C3C3C;font-weight: bold;height: 208rpx;">
							{{item.title}}
						</view>
						<view class="flex-y-center" style="color: #A6A6A6;font-size: 9pt;">

							<view>{{item.created_at}}</view>
						</view>
					</view>
					<view style="width: 250rpx">
						<image :src="item.cover_pic" style="width: 250rpx;height: 250rpx;border-radius: 20rpx;" mode="aspectFill"></image>
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
				itemWidth: '',
				currentTab: 0,
				status: -1,
				page: 1,
				list: [],
				cat_list: [],
				is_no_more: false
			}
		},

		onLoad: function() {
			this.getCatList();
		},
		methods: {
			detail(id) {
				uni.navigateTo({
					url: "/plugins/article/article?id=" + id
				})
			},
			change(e) {
				this.currentTab = e.index;
				let cat_id = this.cat_list[e.index].id;
				this.is_no_more = false;
				this.list = [];
				this.page = 1;
				this.getArticleList(cat_id);
			},
			getCatList() {
				this.$request({
					url: "/plugin/article/api/article/cat-list",
				}).then(res => {
					if (res.code == 0) {
						this.cat_list = res.data.list;
						if (this.cat_list.length) {
							this.itemWidth = 100 / res.data.list.length
							this.getArticleList(this.cat_list[0].id);
						}
					}
				})
			},
			getArticleList(id) {
				if (this.is_no_more) {
					return;
				}

				this.$request({
					url: "/plugin/article/api/article/list",
					data: {
						page: this.page,
						cat_id: id
					}
				}).then(res => {
					if (res.code == 0) {
						this.list = res.data.list;
						if (this.page == 1) {
							this.list = res.data.list;
						} else {
							this.list = this.list.concat(res.data.list);
						}
						if (this.page <= res.data.pagination.pageCount) {
							this.is_no_more = true;

						} else {
							this.page++;
						}
					}
				})

			}

		}



	}
</script>

<style>
</style>
