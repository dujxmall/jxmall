<template>
	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>分销设置</span>
				<el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
			</div>
			<div>
				<el-row>
					<el-col :span="12">
						<el-form ref="form" :model="form" label-width="150px">
							<div class="title-bar flex-y-center"><span>基本信息</span></div>
							<el-form-item label="分销层级">
								<el-radio-group v-model="form.level">
									<el-radio :label="'0'">关闭</el-radio>
									<el-radio :label="'1'">一级分销</el-radio>
									<el-radio :label="'2'">二级分销</el-radio>
									<el-radio :label="'3'">三级分销</el-radio>
								</el-radio-group>

							</el-form-item>
							<el-form-item label="分销内购">
								<el-radio-group v-model="form.self_buy">
									<el-radio :label="'0'">关闭</el-radio>
									<el-radio :label="'1'">开启</el-radio>
								</el-radio-group>
								<br>
								<span class="info-color">开启分销内购后，分销商自己购买商品，享受一级佣金，上级享受二级佣金，上上级享受三级佣金</span>
							</el-form-item>
							<div class="title-bar flex-y-center"><span>资格设置</span></div>
							<el-form-item label="成为分销商的条件">
								<el-radio-group v-model="form.type">
									<el-radio :label="'0'">无条件</el-radio>
									<el-radio :label="'1'">申请</el-radio>
									<el-radio :label="'2'">购买商品</el-radio>
									<el-radio :label="'3'">消费金额</el-radio>
								</el-radio-group>
							</el-form-item>
							<el-form-item label="申请说明" v-if="form.type=='1'">
								<el-input type="textarea" v-model="form.explain" placeholder="申请说明">
								</el-input>
							</el-form-item>
							<el-form-item label="拒绝后可以再次申请" v-if="form.type=='1'">
								<el-radio-group v-model="form.re_apply">
									<el-radio :label="'0'">否</el-radio>
									<el-radio :label="'1'">是</el-radio>
								</el-radio-group>
							</el-form-item>
							<el-form-item label="购买商品" v-if="form.type=='2'">
								<el-table :data="form.list" style="width: 100%">
									<el-table-column label="商品" width="350">
										<template slot-scope="scope">
											<div class="flex-row">
												<div>
													<el-image :src="scope.row.cover_pic"
														style="width: 60px;height: 60px;" fit="fit">
														<div slot="error" class="image-slot">
															<i class="el-icon-picture-outline"></i>
														</div>
													</el-image>
												</div>
												<div style="padding: 10px;">
													{{scope.row.name}}
												</div>
											</div>
										</template>
									</el-table-column>
									<el-table-column label="价格" width="80">
										<template slot-scope="scope">
											<span style="font-weight: bold;color: #000000;">￥{{scope.row.price}}</span>
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
										<el-button type="text">+添加商品({{form.list.length}}/50)</el-button>
									</goods-picker>
								</div>
							</el-form-item>

							<el-form-item label="消费金额满" v-if="form.type=='3'">
								<el-input v-model="form.price" placeholder="消费金额满">
									<tmeplate slot="append">元</tmeplate>
								</el-input>
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
		name: 'plugins-share-setting',
		data() {
			return {
				is_loading: false,
				form: {
					level: '0',
					self_buy: '0',
					price: '',
					list: [],
					type: '0',
					re_apply: '0'

				}
			}
		},
		created() {
			this.getSetting();
		},
		methods: {
			save() {
				this.is_loading = true;
				this.$request({
					url: "/plugin/share/mall/share/setting",
					data: this.form,
					method: 'post'
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.$message.success(res.msg)
					}
				}).catch(e => {
					this.is_loading = false;

				})

			},
			goodsDelete(index) {
				this.form.list.splice(index, 1)
			},
			selectedGoods(e) {
				if (this.form.list.length == 0) {
					this.form.list = (e)
				} else {
					for (let i in e) {
						this.form.list.push(e[i])
					}
				}
				this.$forceUpdate()
			},
			getSetting() {
				this.is_loading = true;
				this.$request({
					url: "/plugin/share/mall/share/setting",
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						//	this.$message.success(res.msg)
						this.form = res.data.setting
					}
				}).catch(e => {
					this.is_loading = false;
				})
			},

		}
	}
</script>

<style scoped="scoped">
	.title-bar {
		width: 100%;
		height: 40px;
		background-color: #F4F6F8;
		padding: 0 20px;
		font-weight: bold;
		margin-bottom: 30px;
	}
</style>
