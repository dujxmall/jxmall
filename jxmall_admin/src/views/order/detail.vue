<template>
	<div class="app-container">

		<el-card v-loading="is_loading" class="box-card">
			<div class="el-card__header">
				<slot name="header">订单详情</slot>
			</div>

			<div style="margin-top: 20px;">
				<div style="width: 60%;margin: 0 auto;" v-if="order">
					<el-steps :active="step" v-if="step>0&&step<=4">
						<el-step title="买家下单" icon="el-icon-circle-check"></el-step>
						<el-step title="买家付款" icon="el-icon-circle-check"></el-step>
						<el-step title="商家发货" icon="el-icon-circle-check"></el-step>
						<el-step title="确认收货" icon="el-icon-circle-check"></el-step>
						<el-step title="订单完成" icon="el-icon-circle-check"></el-step>
					</el-steps>
					<div class="flex-x-center flex-y-center" style="font-size:40px;" v-if="order.status==-1">
						<i class="el-icon-warning" style="color:#409EFF ;"></i>
						<span style="font-size: 25px;">订单已关闭</span>
					</div>
				</div>
				<div style="padding: 30px;" v-if="order&&user">
					<div class="flex-row">
						<div style="width: 33.33333%;">
							<div style="height: 50px;font-weight: bold;font-size: 17px;" class="flex-y-center">当前订单状态</div>
							<div class="flex-y-center" v-if="order.status==5">
								<span class="order-status-logo"> <i class="el-icon-warning"></i> </span> <span class="order-status-text">订单已关闭</span>
							</div>
							<div class="flex-y-center" v-if="order.status==0">
								<span class="order-status-logo"> <i class="el-icon-s-finance"></i> </span> <span class="order-status-text">待付款</span>
								<el-button type="primary" :size="'mini'" @click="pay">确认付款</el-button>
							</div>
							<div class="flex-y-center" v-if="order.status==1">
								<span class="order-status-logo"> <i class="el-icon-s-finance"></i> </span> <span class="order-status-text">待发货</span>
								<el-button type="primary" :size="'mini'" @click="showOrderSend=!showOrderSend">立即发货</el-button>
							</div>

							<div class="flex-y-center" v-if="order.status==2">
								<span class="order-status-logo"> <i class="el-icon-s-finance"></i> </span> <span class="order-status-text">待确认</span>
								<el-button type="primary" :size="'mini'" @click="confirm">确认收货</el-button>
							</div>
							<div class="flex-y-center" v-if="order.status==3">
								<span class="order-status-logo"> <i class="el-icon-s-finance"></i> </span> <span class="order-status-text">已确认收货</span>
								<el-button type="primary" :size="'mini'" @click="finish">完成订单</el-button>
							</div>
							<div class="flex-y-center" v-if="order.status==4">
								<span class="order-status-logo"> <i class="el-icon-s-finance"></i> </span> <span class="order-status-text">订单已完成</span>
							</div>
							<div style="margin-top: 20px;">
								<el-button type="primary" v-if="step<3" @click='addressModify' size='mini' plain>修改收货信息</el-button>
								<el-button type="primary" v-if="step<3" @click='close' size='mini' plain>关闭订单</el-button>
							<!-- 	<el-button type="primary" size='mini' plain>打印小票</el-button> -->

							</div>


						</div>
						<div style="width: 33.33333%;">
							<div style="height: 50px;font-weight: bold;font-size: 17px;" class="flex-y-center">发货信息</div>
							<div style="color: #2C3E50;font-size: 11px;" v-if="order">
								<div style="padding-bottom: 10px;">
									<span style="width: 100px;">配送方式：</span>
									<span> <i class="el-icon-box" style="color:#409EFF ;"></i> 快递</span>
								</div>
								<div style="padding-bottom: 10px;">
									<span style="width: 100px;">收件人姓名：</span>
									<span>{{order.name}}</span>
								</div>
								<div style="padding-bottom: 10px;">
									<span style="width: 100px;">联系方式：</span>
									<span>{{order.mobile}}</span>
								</div>
								<div style="padding-bottom: 10px;">
									<span style="width: 100px;">收货地址：</span>
									<span>{{order.address}}</span>
								</div>

								<div style="padding-bottom: 10px;">
									<span style="width: 100px;">买家备注：</span>
									<span>{{order.remarks}}</span>
								</div>
							</div>
						</div>
						<div style="width: 33.33333%;">
							<div style="height: 50px;font-weight: bold;font-size: 17px;" class="flex-y-center">订单信息</div>
							<div style="color: #2C3E50;font-size: 11px;">

								<div style="padding-bottom: 10px;" v-if="user">
									<span style="width: 100px;">用户：</span>
									<span style="color:#409EFF;">{{user.nickname}}</span>
								</div>
								<div style="padding-bottom: 10px;" v-if="order">
									<span style="width: 100px;">订单编号：</span>
									<span>{{order.order_no}}</span>
								</div>
								<div style="padding-bottom: 10px;" class="flex-y-center" v-if="order">
									<span style="width: 67px;">支付方式：</span>
									<div class="flex-y-center" v-if="order.pay_type==0">
										<img src="@/assets/statics/mall/wechat_pay.png" style="width: 20px;height: 20px;margin-left: 2px;"></img>
										<span style="padding: 0 2px;">
											微信支付
										</span>
									</div>
									<div class="flex-y-center" v-if="order.pay_type==1">
										<img src="@/assets/statics/mall/balance_pay.png" style="width: 20px;height: 20px;margin-left: 2px;"></img>
										<span style="padding: 0 2px;">余额支付</span>
									</div>
									<div class="flex-y-center" v-if="order.pay_type==2">
										<span>
											后台付款
										</span>
									</div>
								</div>
							</div>
						</div>

					</div>

					<div>
						<div style="height: 40px;background-color: #F3F3F3;padding: 0 10px;font-weight: bold;" class="flex-y-center">商品信息</div>

						<div>
							<!--
							 商品信息
							 -->
							<el-table :data="list" style="width: 100%">
								<el-table-column label="商品信息" width="500">
									<template slot-scope='scope'>
										<div class="flex-row">
										<div>
                      <el-image :src='scope.row.goods.cover_pic' fit="fill" class="goods-pic"></el-image>
                    </div>
											<div style="padding-top: 30px;">
												<div>{{scope.row.goods.name}}</div>
											</div>
										</div>
									</template>
								</el-table-column>
								<el-table-column label="编码" width="180">
									<template slot-scope='scope'>
										<div>
											<div>
												编号：{{scope.row.goods.no}}
											</div>
										</div>
									</template>
								</el-table-column>
								<el-table-column label="规格" width="180">
									<template slot-scope='scope'>
										<div v-if="scope.row.attr">
											<div v-for="(item,index) in scope.row.attr">
												{{item.attr_group_name}}：<el-tag size='mini'>{{item.attr_name}}</el-tag>
											</div>
										</div>
										<div v-if="!scope.row.attr">
											规格：<el-tag size='mini'>默认</el-tag>
										</div>
									</template>
								</el-table-column>
								<el-table-column label="售卖价">
									<template slot-scope='scope'>
										{{scope.row.goods.price}}
									</template>
								</el-table-column>
								<el-table-column prop="num" label="数量">
								</el-table-column>
								<el-table-column label="优惠金额">
									<template slot-scope="scope">
										<div>
											优惠券：<span style="color: #429EFB;">- {{scope.row.coupon_cut_price}}元</span>
										</div>
										<div>
											积分抵扣：<span style="color: #429EFB;">- {{scope.row.integral_cut_price}}元</span>
										</div>
									</template>
								</el-table-column>
								<el-table-column prop="total_price" label="小计">
								</el-table-column>
								<el-table-column label="状态">
									<template slot-scope="scope">
										<div v-if="scope.row.has_refund">
											<span v-if="scope.row.refund_status==0">
												待处理
											</span>
											<span v-if="scope.row.refund_status==1">
												<span v-if="scope.row.refund_type==1">
													已同意换货
												</span>
												<span v-if="scope.row.is_refund==0&&scope.row.refund_type==0">
													待退款
												</span>
												<span v-if="scope.row.is_refund==1&&scope.row.refund_type==0">
													已退款
												</span>
											</span>
											<span v-if="scope.row.refund_status==2">
												已拒绝
											</span>
										</div>

									</template>
								</el-table-column>
								<el-table-column label="物流信息">
									<template slot-scope="scope">
										<span v-if="scope.row.is_send==1">
											<el-button type="text" @click="showExpressDialog(scope.row)">物流信息</el-button>
										</span>
										<span v-if="scope.row.is_send==0">暂无信息
										</span>
									</template>
								</el-table-column>
							</el-table>

							<div style="padding: 20px;position: relative;height: 100px;" v-if="order">
								<div style="position: absolute;right: 0;">
									<div style="width: 250px;" class="flex-y-center">
										<div style="text-align: end;width: 100px;">商品小计：</div>
										<div>￥{{order.goods_total_price}}</div>
									</div>
									<div style="width: 250px;" class="flex-y-center">
										<div style="text-align: end;width: 100px;">运费：</div>
										<div>￥{{order.express_price}}</div>
									</div>
									<!-- 	<div style="width: 250px;" class="flex-y-center">
										<div style="text-align: end;width: 100px;">会员折扣：</div>
										<div style="color: #FF1F2C;">￥124.00</div>
									</div> -->
									<div style="width: 250px;font-weight: bold;font-size: large;margin-top: 10px;" class="flex-y-center">
										<div style="text-align: end;width: 150px;">实际付款：</div>
										<div style="color: #FF1F2C;">￥{{order.pay_price}}</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</el-card>


		<OrderSend @refresh="refresh" :showOrderSend="showOrderSend" :order="order" :order_detail_list="list"></OrderSend>

		<div v-if="current_goods">
			<Express :showExpress="showExpress" :express_no="current_goods.express_no" :express_code="current_goods.express_code"
			 :express_name="current_goods.express_name" @expressClose='expressClose'>
			</Express>
		</div>

		<order-address-info :showOrderAddressInfo="showOrderAddressInfo" :order="order" @addressModifyClose='addressModifyClose'></order-address-info>
	</div>

</template>
<script>
	import variables from '@/styles/variables.scss'
	import OrderAddressInfo from '@/components/mall/order/OrderAddressInfo'
	import OrderSend from '@/components/mall/order/OrderSend'
	import Express from '@/components/common/Express'

	export default {
		name: 'detail',
		components: {
			OrderAddressInfo,
			OrderSend,
			Express

		},
		data() {
			return {
				is_loading: false,
				query: null,
				order_id: '',
				list: [],
				order: null,
				user: null,
				step: 0,
				showOrderAddressInfo: false,
				showOrderSend: false,
				showExpress: false,
				current_goods: {
					express_no: '',
					express_name: '',
					express_code: ''
				}

			}
		},
		mounted() {
			if (this.$route.query.id) {
				this.order_id = this.$route.query.id
				this.getDetail();
			}
		},
		created() {

		},
		methods: {
			expressClose(e) {
				this.showExpress = false;
			},
			showExpressDialog(e) {

				 

				if (e.express_code == '' || e.express_no == '') {
					this.$message.warning('该订单物流信息有误，请排查后重试');
					return;
				}
				this.current_goods = e;
				this.showExpress = true;

			},
			pay() {
				this.$confirm('是否确认支付', '提示', {}).then(() => {
					this.is_loading = true;
					this.$request({
						url: '/mall/order/pay',
						data: {
							order_id: this.order_id
						},
						method: 'post'
					}).then(res => {
						this.is_loading = false;
						this.$message.success(res.msg)

						this.refresh()


					})
				}).catch(() => {})
			},
			close() {
				this.$confirm('是否关闭订单', '提示', {}).then(() => {
					this.is_loading = true;
					this.$request({
						url: '/mall/order/close',
						data: {
							order_id: this.order_id
						},
						method: 'post'
					}).then(res => {
						this.is_loading = false;
						this.$message.success(res.msg)
						this.getDetail();
					})
				}).catch(() => {})
			},
			confirm() {
				this.$confirm('是否确认收货', '提示', {}).then(() => {
					this.is_loading = true;
					this.$request({
						url: '/mall/order/confirm',
						data: {
							order_id: this.order_id
						},
						method: 'post'
					}).then(res => {
						this.is_loading = false;
						this.$message.success(res.msg)
						this.getDetail();
					})
				}).catch(() => {})
			},
			finish() {
				this.$confirm('是否完成订单', '提示', {}).then(() => {
					this.is_loading = true;
					this.$request({
						url: '/mall/order/finish',
						data: {
							order_id: this.order_id
						},
						method: 'post'
					}).then(res => {
						this.is_loading = false;
						this.$message.success(res.msg)
						this.getDetail();
					})
				}).catch(() => {})
			},
			addressModifyClose() {
				this.order = null;
				this.showOrderAddressInfo = false;
				this.getDetail();
			},
			getDetail() {
				this.is_loading = true;
				this.$request({
					url: '/mall/order/detail',
					data: {
						order_id: this.order_id
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.order = res.data.order;
						this.user = res.data.user;
						this.list = res.data.list;
						this.step = parseInt(res.data.order.status) + 1;
						 
						this.$forceUpdate();
					}
				})

			},
			//刷新页面
			refresh() {
				this.getDetail();
			},
			addressModify() {
				this.showOrderAddressInfo = true

			}

		}
	}
</script>


<style>
	.el-step.is-horizontal .el-step__line {
		height: 1px !important;
	}

	.order-status-logo {
		font-size: 30px;
		color: #409EFF;
	}

	.goods-pic {
		width: 100px;
		height: 100px;

	}

	.order-status-text {
		font-size: 20px;
		font-weight: bold;
		padding: 0 10px;
	}
</style>
