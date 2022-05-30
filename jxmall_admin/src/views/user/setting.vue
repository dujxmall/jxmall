<template>
<div class="app-container">
	<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>推广设置</span>
				<el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
			</div>

			<el-row>
				<el-col :span="12">
					<el-form ref="form" :model="form" label-width="120px">
						<el-form-item label="成为下线条件">
							<el-radio-group v-model="form.child_type">
								<el-radio label="0">首次点击链接</el-radio>
								<el-radio label="1">首次下单</el-radio>
								<el-radio label="2">首次付款</el-radio>
							</el-radio-group>
						</el-form-item>

						<el-form-item label="分销资格设置">
							<el-radio-group v-model="form.invite_type">
								<el-radio label="0">无条件</el-radio>
								<el-radio label="1">购物</el-radio>
								<el-radio label="2">申请</el-radio>
								<el-radio label="3">消费满</el-radio>
							</el-radio-group>
						</el-form-item>
						<el-form-item label="消费满" v-if="form.invite_type==3">
							<el-input v-model="form.price">
								<template slot="append">元</template>
							</el-input>
						</el-form-item>

						<el-form-item label="选择商品" v-if="form.invite_type==1">
							<el-table :data="form.list" style="width: 100%">
								<el-table-column label="商品" width="350">
									<template slot-scope="scope">
										<div class="flex-row">
											<el-image :src="scope.row.cover_pic" style="width: 60px;height: 60px;" fit="fit">
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
							<goods-picker @selected="selectedGoods" style="margin-right: 5px;" width="88" height="88">
								<el-button type="text">+添加商品({{form.list.length}}/50)</el-button>
							</goods-picker>
						</el-form-item>

						<el-form-item label="购物结算方式" v-if="form.invite_type==1">
							<el-radio-group v-model="form.buy_type">
								<el-radio label="0">付款</el-radio>
								<el-radio label="1">订单完成</el-radio>
							</el-radio-group>
						</el-form-item>
						<el-form-item label="是否需要审核" v-if="form.invite_type==2">
							<el-radio-group v-model="form.is_check">
								<el-radio label="0">否</el-radio>
								<el-radio label="1">是</el-radio>
							</el-radio-group>
						</el-form-item>

						<el-form-item label="推广说明">
							<el-input type="textarea" v-model="form.detail"></el-input>
						</el-form-item>
					</el-form>
				</el-col>
			</el-row>
		</el-card>

</div>
</template>

<script>
	export default {
	  name:'user-setting',
		data() {
			return {
				is_loading: false,

				form: {
					price:'',
					buy_type: "0",
					child_type:"0",
					is_check: "0",
					invite_type: "0",
					detail: '',
					list: [],
					goods_ids: [],
				}
			}
		},
		created() {
			this.getSetting();
		},
		methods: {
			selectedGoods(e) {
				if (this.form.list.length == 0) {
					this.form.list = e
					for (let i in e) {
						this.form.goods_ids.push(e[i].id)
					}
				} else {
					this.form.list.concat(e);
					for (let i in e) {
						this.form.goods_ids.push(e[i].id)
					}
				}
				this.$forceUpdate();
			},
			goodsDelete(index) {
				this.form.list.splice(index, 1)
				this.form.goods_ids = [];
				for (let i in this.form.list) {
					this.form.goods_ids.push(this.form.list[i].id)
				}
			},
			getSetting() {
				this.is_loading = true;
				this.$request({
					url: '/mall/user/setting',
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.form = res.data.setting;
						if (!this.form.goods_ids) {
							this.form.goods_ids = [];
							this.form.list = [];
							this.form.buy_type = '0';
						}
					}
				})
			},
			save() {
				this.is_loading = true;
				if (this.form.invite_type == 1) {
					if (this.form.goods_ids.length == 0) {
						this.$message.error('请选择商品！');
						return;
					}
				}
				this.$request({
					url: '/mall/user/setting',
					data: this.form,
					method: 'post',
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.$message.success(res.msg);
					}
				})
			}
		}


	}
</script>

<style>
</style>
