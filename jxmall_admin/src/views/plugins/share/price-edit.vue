<template>

	<div class="app-container">
		<el-card class="box-card" v-loading='is_loading'>
			<div slot="header" class="clearfix">
				<span>佣金设置</span>
			</div>
			<div>
				<el-form ref="form" :model="form" label-width="180px">
					<el-tabs v-model="activeName">
						<el-tab-pane label="基础信息" name="basic">
							<el-row>
								<el-col :span="20">
									<el-form-item label="分类">
										<div class="flex-row">
											<div class="tag-box" v-if="form.cat_list.length > 0">
												<el-tag type="warning" class="tag-item" @close="deleteCat(index)" closable v-for="(item, index) in form.cat_list">
													{{item.name}}
												</el-tag>
											</div>
											<div class="tag-box" v-if="form.cat_list.length == 0">
												没有分类
											</div>
										</div>
									</el-form-item>

									<el-form-item label="商品名称">
										<el-input v-model="form.name" disabled></el-input>
									</el-form-item>
									<el-form-item label="缩略图">
										<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center" v-if="form.cover_pic">
											<img :src="form.cover_pic" alt="" style="width: 100%;height: 100%;">

										</div>
									</el-form-item>
									<el-form-item label="轮播图">
										<div style="display: flex;flex-wrap: wrap;">
											<template v-if="form.pic_list&&form.pic_list.length">
												<div class="flex-row">
													<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center" v-for="(item,index) in form.pic_list">
														<img :src="item" alt="" style="width: 100%;height: 100%;">

													</div>
												</div>
											</template>
										</div>
									</el-form-item>
									<el-form-item label="售价">
										<el-input type="number" disabled v-model="form.price" step="0.01"></el-input>
									</el-form-item>
									<el-form-item label="原价">
										<el-input type="number" disabled v-model="form.origin_price" step="0.01"></el-input>
									</el-form-item>
									<el-form-item label="成本价">
										<el-input type="number" disabled v-model="form.cost_price" step="0.01"></el-input>
									</el-form-item>
									<el-form-item label="参与分销">
										<el-radio-group v-model="form.is_share_goods">
											<el-radio :label="0">不参与</el-radio>
											<el-radio :label="1">参与</el-radio>
										</el-radio-group>
									</el-form-item>
									<el-form-item label="分销类型" v-if="form.is_share_goods==1">
										<el-radio-group v-model="form.is_alone">
											<el-radio :label="0">系统设置</el-radio>
											<el-radio :label="1">独立设置</el-radio>
										</el-radio-group>
									</el-form-item>
									<el-form-item label="佣金设置" v-if="form.is_alone==1&&form.is_share_goods==1">
										<el-radio-group v-model="form.price_type">
											<el-radio :label="0">固定金额</el-radio>
											<el-radio :label="1">百分比</el-radio>
										</el-radio-group>
										<div class="flex-row" style="border-bottom: solid #F3F3F3 1px;">
											<div style="width: 180px;margin: 10px;">层级 </div>
											<div style="width: 180px;margin: 10px;" v-if="form.share_level>0">
												一级
											</div>
											<div style="width: 180px;margin: 10px;" v-if="form.share_level>1">
												二级
											</div>
											<div style="width: 180px;margin: 10px;" v-if="form.share_level>2">
												三级
											</div>
										</div>
										<div class="flex-row" style="border-bottom: solid #F3F3F3 1px;">
											<div style="width: 180px;margin: 10px;">批量设置 </div>
											<div style="width: 180px;margin: 10px;" v-if="form.share_level>0">
												<el-input v-model="first_price" placeholder="设置金额">
													<template slot="append">
														<el-button @click="setPrice(1)"><i class="el-icon-arrow-down"></i></el-button>
													</template>
												</el-input>
											</div>
											<div style="width: 180px;margin: 10px;" v-if="form.share_level>1">
												<el-input v-model="second_price" placeholder="设置金额">
													<template slot="append">
														<el-button @click="setPrice(2)"><i class="el-icon-arrow-down"></i></el-button>
													</template>
												</el-input>
											</div>
											<div style="width: 180px;margin: 10px;" v-if="form.share_level>2">
												<el-input v-model="third_price" placeholder="设置金额">
													<template slot="append">
														<el-button @click="setPrice(3)"><i class="el-icon-arrow-down"></i></el-button></i>
													</template>
												</el-input>
											</div>
										</div>
										<div v-for="price in price_list" class="flex-row" style="border-bottom: solid #F3F3F3 1px;">
											<div style="width: 180px;margin: 10px;">{{price.level_name}} </div>
											<div style="width: 180px;margin: 10px;" v-if="form.share_level>0">
												<el-input v-model="price.first_price"> <template slot="append"> {{form.price_type==0?'元':'%'}}</template></el-input>
											</div>
											<div style="width: 180px;margin: 10px;" v-if="form.share_level>1">
												<el-input v-model="price.second_price"> <template slot="append"> {{form.price_type==0?'元':'%'}}</template></el-input>
											</div>
											<div style="width: 180px;margin: 10px;" v-if="form.share_level>2">
												<el-input v-model="price.third_price"> <template slot="append"> {{form.price_type==0?'元':'%'}}</template></el-input>
											</div>
										</div>

									</el-form-item>
								</el-col>
							</el-row>
						</el-tab-pane>
					</el-tabs>
					<el-form-item style="margin-top: 20px;">
						<el-button type="primary" @click="submit" :loading="is_submiting">保存</el-button>
						<el-button @click="cancel">取消</el-button>
					</el-form-item>
				</el-form>
			</div>
		</el-card>

	</div>
</template>

<script>
	export default {

		data() {
			return {
				is_loading: false,
				is_submiting: false,
				activeName: 'basic',
				level_list: [],
				first_price: '',
				second_price: '',
				third_price: '',
				price_list: [],
				form: {
					is_share_goods: 1,
					price_type: 0,
					is_alone: 0,
					id: '',
					name: '',
					cover_pic: '',
					pic_list: [],
					is_attr: 0,
					origin_price: '',
					price: '',
					cost_price: '',
					cat_list: [],

				}
			}
		},
		created() {

		},
		mounted() {
			this.form.id = this.$route.query.id;
			if (this.form.id) {
				this.getGoods();
			}
		},
		methods: {
			setPrice(e) {
				if (e == 1) {
					this.price_list.forEach((item => {
						item.first_price = this.first_price;
					}))
				}
				if (e == 2) {
					this.price_list.forEach((item => {
						item.second_price = this.second_price;
					}))
				}
				if (e == 3) {
					this.price_list.forEach((item => {
						item.third_price = this.third_price;
					}))
				}

			},
			cancel() {
				this.$go.back()
			},
			getGoods() {
				this.is_loading = true;
				this.$request({
					url: '/plugin/share/mall/goods/edit',
					data: {
						id: this.form.id
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.form = res.data.info;
						this.price_list = res.data.price_list
					}
				})
			},

			submit() {
				this.is_submiting = true;
				this.$request({
					url: '/plugin/share/mall/goods/edit',
					data: {
						id: this.form.id,
						is_alone: this.form.is_alone,
						price_type: this.form.price_type,
						is_share_goods: this.form.is_share_goods,
						price_list: this.price_list
					},
					method: 'post'
				}).then(res => {
					this.is_submiting = false;
					if (res.code == 0) {
						this.$message.success(res.msg);
						this.$go.back()
					} else {
						this.$message.error(res.msg);
					}
				})
				console.log(this.form);
			},
		}
	}
</script>

<style lang="scss">
	.add-image-btn {
		position: relative;
		width: 100px;
		height: 100px;
		color: #419EFB;
		border: 1px solid #e2e2e2;
		cursor: pointer;
		padding: 2px;
		margin-right: 10px;

		.pic-del {
			position: absolute;
			top: -10px;
			height: 20px;
			width: 20px;
			background-color: #F56C6C;
			border-radius: 50%;
			color: #FFF0FF;
			right: -10px;
		}
	}

	.tag-box {
		margin: 0px;

		.tag-item {
			margin-right: 5px;
		}
	}
</style>
