<template>
	<div class="app-container" v-loading="is_loading">
	 
		<el-card>
			<div style="height: 160px;width: 100%;background-color: #FFFFFF;padding: 0 40px;" class="flex-x-between">
				<div class="flex-y-center">
					<div>
						<img style="width: 60px;height: 60px;" src="@/assets/statics/mall/send.png">
					</div>
					<div style="padding: 20px;font-size: 15px;">
						<div>待发货订单</div>
						<div style="font-size: 25px;font-weight: bold;line-height: 40px;">{{info.send_order}}</div>
					</div>
				</div>
				<div class="flex-y-center">
					<div>
						<img style="width: 60px;height: 60px;" src="@/assets/statics/mall/refund.png">
					</div>
					<div style="padding: 20px;font-size: 15px;">
						<div>待处理维权</div>
						<div style="font-size: 25px;font-weight: bold;line-height: 40px;">{{info.refund_order}}</div>
					</div>
				</div>
				<div class="flex-y-center">
					<div>
						<img style="width: 60px;height: 60px;" src="@/assets/statics/mall/member.png">
					</div>
					<div style="padding: 20px;font-size: 15px;">
						<div>总会员数</div>
						<div style="font-size: 25px;font-weight: bold;line-height: 40px;">{{info.user}}</div>
					</div>
				</div>
				<div class="flex-y-center">
					<div>
						<img style="width: 60px;height: 60px;" src="@/assets/statics/mall/customer.png">
					</div>
					<div style="padding: 20px;font-size: 15px;">
						<div>消费会员数量</div>
						<div style="font-size: 25px;font-weight: bold;line-height: 40px;">{{info.customer}}</div>
					</div>
				</div>
				<div class="flex-y-center">
					<div>
						<img style="width: 60px;height: 60px;" src="@/assets/statics/mall/goods.png">
					</div>
					<div style="padding: 20px;font-size: 15px;">
						<div>商品数量</div>
						<div style="font-size: 25px;font-weight: bold;line-height: 40px;">{{info.goods}}</div>
					</div>
				</div>
				<div class="flex-y-center">
					<div>
						<img style="width: 60px;height: 60px;" src="@/assets/statics/mall/order.png">
					</div>
					<div style="padding: 20px;font-size: 15px;">
						<div>订单数量</div>
						<div style="font-size: 25px;font-weight: bold;line-height: 40px;">{{info.order}}</div>
					</div>
				</div>
			
			</div>
			
			<div style="margin-top: 30px;width: 100%;background-color: #FFFFFF;padding:20px;">
				<div style="" class="flex-x-between">
					<div class="flex-row flex-y-center">
						<div style="font-size:18px;font-weight: bold;color: #000000;">经营状况</div>
					</div>
					<div>
						<div class="flex-y-center">
							<div class="flex-x-center" style="margin-right: 20px;border-radius: 4px;">
								<div style="width: 50px;height: 39px;border-top-left-radius: 3px;border-bottom-left-radius: 3px;" class="flex-y-center flex-x-center date-item"
								 :class="{'date-active':select_date==1?true:false}" @click="dateClick(1)">日</div>
								<div style="width: 50px;height: 39px;border-right: none;border-left: none;" class="flex-y-center flex-x-center date-item"
								 @click="dateClick(2)" :class="{'date-active':select_date==2?true:false}" :style="{'border-right':select_date==2?'solid #22A1FE 1px':'none','border-left':select_date==2?'solid #22A1FE 1px':'none'}">月</div>
								<div style="width: 50px;height: 39px;border-top-right-radius: 3px;border-bottom-right-radius: 3px;" class="flex-y-center flex-x-center date-item"
								 @click="dateClick(3)" :class="{'date-active':select_date==3?true:false}">年</div>
							</div>
			
							<div>
								<el-date-picker value-format="yyyy-MM-dd" v-model="search_date" type="daterange" range-separator="至"
								 start-placeholder="开始日期" end-placeholder="结束日期" @change="dateChange">
								</el-date-picker>
							</div>
						</div>
			
					</div>
			
			
				</div>
				<div style="width: 100%;background-color: #FBFCFF;margin-top: 30px;border: solid #E9EDEF 1px;">
					<div class="flex-row flex-x-between" style="width: 100%;">
						<div class="status-item flex-y-center">
							<div>
								<div>总成交额（元）</div>
								<div class="number">{{info.total_complete_price}}</div>
							</div>
						</div>
			
						<div class="status-item flex-y-center">
							<div>
								<div>订单数量（元）</div>
								<div class="number">{{info.select_order}}</div>
							</div>
						</div>
						<div class="status-item flex-y-center">
							<div>
								<div>商品销量（件）</div>
								<div class="number">{{info.sales}}</div>
							</div>
						</div>
						<div class="status-item flex-y-center">
							<div>
								<div>支付人数（人）</div>
								<div class="number">{{info.buyer}}</div>
							</div>
						</div>
						<div class="status-item flex-y-center">
							<div>
								<div>客平均（元）</div>
								<div class="number">{{info.single_price}}</div>
							</div>
						</div>
			
						<div class="status-item flex-y-center">
							<div>
								<div>新增会员（人）</div>
								<div class="number">{{info.new_user}}</div>
							</div>
						</div>
					</div>
				</div>
			
			</div>
			<div style="width: 100%;margin-top: 30px;" class="flex-x-between">
				<div style="width: 49%;background-color: #FBFCFF;height: 500px;padding: 20px;">
					<div>
						<div style="font-weight: bold; font-size: 16px;color: #000000;">商品销量排行</div>
					</div>
					<el-table height="400" :data="goods_list" style="width: 100%">
						<el-table-column label="名次" width="100">
							<template slot-scope="scope">
								<span style="font-size: 20px;font-weight: bold;">{{scope.$index+1}}</span>
							</template>
						</el-table-column>
						<el-table-column label="商品" >
							<template slot-scope="scope">
								<div class="flex-y-center" style="color: #000000;">
									<div>
										<el-image style="width: 100px;height: 100px;" fit='fit' :src="scope.row.cover_pic"></el-image>
									</div>
									<span style="padding: 0 10px;">{{scope.row.name}}</span>
								</div>
							</template>
						</el-table-column>
						<el-table-column prop="sales" label="销量" width="100">
						</el-table-column>
					</el-table>
				</div>
				<div style="height: 100%;width:2%;">
			
				</div>
			
				<div style="width: 49%;background-color: #FBFCFF;height:500px;padding: 20px;">
					<div>
						<div style="font-weight: bold; font-size: 16px;color: #000000;">
							购买力排行
						</div>
					</div>
					<el-table height="400" :data="user_list" style="width: 100%">
						<el-table-column label="名次">
							<template slot-scope="scope">
								<span style="font-size: 20px;font-weight: bold;">{{scope.$index+1}}</span>
							</template>
						</el-table-column>
						<el-table-column label="用户">
							<template slot-scope="scope">
								<div class="flex-y-center" style="color: #000000;">
									<el-avatar :src="scope.row.avatar_url"></el-avatar>
									<span style="padding: 0 10px;">{{scope.row.nickname}}</span>
								</div>
							</template>
						</el-table-column>
						<el-table-column prop="pay_price" label="消费金额">
						</el-table-column>
					</el-table>
			
				</div>
			</div>
			 
		</el-card>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				is_loading: false,
				search_date: '',
				select_date: 1,
				goods_list: [],
				user_list: [],
				info: {
					buyer: "0",
					customer: "0",
					goods: "0",
					new_user: "0",
					order: "0",
					refund_order: "1",
					sales: 0,
					select_order: "0",
					send_order: "0",
					single_price: 0,
					total_complete_price: null,
					user: "0",
				}
			}
		},
		created() {

			this.getData();
		},
		methods: {
			dateChange(e) {
				
				this.search_date = e;
				this.select_date = -1;
				this.getData();
			},
			dateClick(e) {
				this.select_date = e;
				this.getData();
				this.search_date = [];

			},
			getData() {
				this.is_loading = true;
				this.$request({
					url: '/mall/mall/data',
					data: {
						select_date: this.select_date,
						search_date: this.search_date
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.info = res.data.info;
						this.goods_list = res.data.goods_list;
						this.user_list = res.data.user_list;
					}
				})


			},

		},


	}
</script>

<style>
	.date-item:hover {
		cursor: pointer;
	}

	.date-item {

		border: solid #DCDFE6 1px;
		overflow: hidden;
	}

	.date-active {
		border: solid #22A1FE 1px;
		background-color: #D2E0EB;
		color: #22A1FE;
	}

	.status-item {
		padding-left: 20px;
		border-right: solid #E9EDEF 1px;
		width: 100%;
		height: 110px;
	}

	.status-item .number {

		font-size: 25px;
		font-weight: bold;
	}
</style>
