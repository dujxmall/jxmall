<template>
	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>优惠券编辑</span>
				<el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
			</div>
			<div v-loading="is_loading">


				<el-row>
					<el-col :span="12">
						<el-form ref="form" :model="form" label-width="160px">
							<el-form-item label="优惠券名称">
								<el-input v-model="form.name"></el-input>
							</el-form-item>

							<el-form-item label="优惠券类型">
								<el-radio-group v-model="form.type">
									<el-radio :label="0">满减券</el-radio>
									<el-radio :label="1">折扣券</el-radio>
								</el-radio-group>
							</el-form-item>
							<el-form-item label="金额设置">
								<div v-if="form.type==0" class="flex-y-center">
									<div style="margin-right: 10px;">消费满</div>
									<el-input v-model="form.price" style="width:200px;">
										<template slot="append">元</template>
									</el-input>
									<span style="padding: 0 20px;">减</span>
									<el-input v-model="form.discount" style="width:200px;">
										<template slot="append">元</template>
									</el-input>
								</div>
								<div v-if="form.type==1" class="flex-y-center">
									<div style="margin-right: 10px;">消费满</div>
									<el-input v-model="form.price" style="width:200px;">
										<template slot="append">元</template>
									</el-input>
									<span style="padding: 0 20px;">打</span>
									<el-input v-model="form.discount" style="width:200px;">
										<template slot="append">折</template>
									</el-input>
								</div>
							</el-form-item>
							<el-form-item label="发放总量">
								<el-radio-group v-model="form.is_limit_total">
									<el-radio :label="0">不限制</el-radio>
									<el-radio :label="1">限制</el-radio>
								</el-radio-group>
								<div v-if="form.is_limit_total==1">

									<el-input v-model="form.total" style="width:200px;" placeholder="请输入发放总数">
										<template slot="append">张</template>
									</el-input>

								</div>
							</el-form-item>
							<el-form-item label="使用时间">
								<el-radio-group v-model="form.time_type">
									<el-radio :label="0">获得后</el-radio>
									<el-radio :label="1">日期范围</el-radio>
								</el-radio-group>
								<div v-if="form.time_type==0">
									<el-input v-model="form.day" style="width:200px;" placeholder="发放后多少天内">
										<template slot="append">天</template>
									</el-input>
								</div>
								<div v-if="form.time_type==1">
									<el-date-picker v-model="form.date_range" type="daterange" range-separator="至"
										start-placeholder="开始日期" end-placeholder="结束日期" value-format="yyyy-MM-dd">
									</el-date-picker>
								</div>
							</el-form-item>
							<el-form-item label="加入到领券中心">
								<el-radio-group v-model="form.is_join">
									<el-radio :label="0">否</el-radio>
									<el-radio :label="1">是</el-radio>
								</el-radio-group>
							</el-form-item>
							<el-form-item label="用户最多领取数量">
								<el-radio-group v-model="form.user_total_limit">
									<el-radio :label="0">不限制</el-radio>
									<el-radio :label="1">限制</el-radio>
								</el-radio-group>
								<div v-if="form.user_total_limit==1">
									<el-input v-model="form.user_total" style="width:200px;" placeholder="请输入领取总数">
										<template slot="append">张</template>
									</el-input>
								</div>
							</el-form-item>
							<el-form-item label="商品使用限制">
								<el-radio-group v-model="form.is_goods_limit">
									<el-radio :label="0">不限制</el-radio>
									<el-radio :label="1">指定商品</el-radio>
								</el-radio-group>
								<div v-if="form.is_goods_limit==1">
									<el-table :data="form.goods_list" style="width: 100%">
										<el-table-column label="商品" width="350">
											<template slot-scope="scope">
												<div class="flex-row">
													<el-image :src="scope.row.cover_pic"
														style="width: 60px;height: 60px;" fit="fit">
														<div slot="error" class="image-slot">
															<i class="el-icon-picture-outline"></i>
														</div>
													</el-image>
													<div style="padding: 10px;">
														{{scope.row.name}}
													</div>
												</div>
											</template>
										</el-table-column>
										<el-table-column label="价格" width="80">
											<template slot-scope="scope">
												<span
													style="font-weight: bold;color: #000000;">￥{{scope.row.price}}</span>
											</template>
										</el-table-column>
										<el-table-column label="操作">
											<template slot-scope="scope">
												<el-button size="mini" type="text" @click="goodsDelete(scope.$index)">
													删除
												</el-button>
											</template>
										</el-table-column>
									</el-table>
									<div style="width: 100%;text-align: center;">
										<goods-picker @selected="selectedGoods" style="margin-right: 5px;" width="88"
											height="88">
											<el-button type="text">+添加商品({{form.goods_list.length}}/50)</el-button>
										</goods-picker>
									</div>
								</div>
							</el-form-item>
						</el-form>


					</el-col>
				</el-row>


			</div>
		</el-card>
	</div>
</template>
<script>
	export default {
		data() {
			return {

				is_loading: false,

				form: {
					id: '',
					type: 0,
					name: '',
					discount: '',
					price: '',
					is_limit_total: 0,
					time_type: 0,
					total: 0,
					day: 1,
					date_range: [],
					is_join: 0,
					user_total: 1,
					user_total_limit: 0,
					is_goods_limit: 0,
					goods_list: [],

				},
			}
		},
		created() {
			if (this.$route.query.id) {
				this.form.id = this.$route.query.id
				this.getCoupon()
			}
		},
		methods: {
			getCoupon() {
				this.is_loading = true

				let form = this.form

				this.$request({
					url: '/mall/market/coupon',
					data: {
						id: this.form.id,
					},

				}).then(res => {
					console.log(res)
					this.is_loading = false

					if (res.code == 0) {
						this.form = res.data.coupon

					}
				})

			},
			save() {
				this.is_loading = true
				let form = JSON.parse(JSON.stringify(this.form))
				form.goods_list = JSON.stringify(this.form.goods_list)
				this.$request({
					url: '/mall/market/coupon',
					data: form,
					method: 'post',
				}).then(res => {
					console.log(res)
					this.is_loading = false

					if (res.code == 0) {
						this.$go.back();
						this.$message.success(res.msg)
					}
				})
			},
			goodsDelete(index) {
				this.form.goods_list.splice(index, 1)
			},
			selectedGoods(e) {
				if (this.form.goods_list.length == 0) {
					this.form.goods_list = (e)
				} else {
					for (let i in e) {
						this.form.goods_list.push(e[i])
					}
				}
			},

		},
	}
</script>

<style>
</style>
