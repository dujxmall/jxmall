<template>
	<div class='app-container'>
		<el-card class="box-card" v-loading="is_loading">
			<div slot="header" class="clearfix">
				<span>会员编辑</span>
				<el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
			</div>
			<div>
				<el-row>
					<el-col :span="12">
						<el-form ref="form" :model="form" label-width="160px">
							<el-form-item label="等级名称">
								<el-input v-model="form.name"></el-input>
							</el-form-item>
							<el-form-item label="是否具有推广权限">
								<el-radio-group v-model="form.is_inviter">
									<el-radio :label="0">否</el-radio>
									<el-radio :label="1">是</el-radio>
								</el-radio-group>
							</el-form-item>
							<el-form-item label="启用等级折扣">
								<el-radio-group v-model="form.is_discount">
									<el-radio :label="0">不启用</el-radio>
									<el-radio :label="1">启用</el-radio>
								</el-radio-group>
							</el-form-item>

							<el-form-item label="享受折扣" v-if="form.is_discount == 1">
								<el-input v-model="form.discount"></el-input>
							</el-form-item>
							<el-form-item label="选择等级">
								<el-select v-model="form.level" placeholder="请选择等级" style="width: 100%;">
									<el-option v-if="list.length" v-for="(level, i) in list" :label="level.name"
										:value="level.level">
									</el-option>
								</el-select>
							</el-form-item>
							<el-form-item label="等级图标">
								<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
									v-if="form.pic_url">
									<img :src="form.pic_url" alt="" style="width: 100%;height: 100%;">
									<span class="flex-x-center flex-y-center pic-del" @click="deleteIcon"> <i
											class="el-icon-close"></i></span>
								</div>
								<file-picker v-if="!form.pic_url" style="margin-right: 5px;" v-model="form.pic_url"
									width="88" height="88">
									<div flex="main:center cross:center"
										class="add-image-btn flex-x-center flex-y-center">
										<i class="el-icon-upload"></i>
									</div>
								</file-picker>
							</el-form-item>
							<el-form-item label="升级之前是否需要签电子合同">
								<el-radio-group v-model="form.is_contract_update">
									<el-radio :label="0">否</el-radio>
									<el-radio :label="1">是</el-radio>
								</el-radio-group>
								<div>开启此项目能需要开启电子合同插件，否则无效</div>
							</el-form-item>
							<el-form-item label="合同模板ID" v-if="form.is_contract_update == 1">
								<el-input v-model="form.contract_tpl_id"></el-input>
								<div>开启此项目能需要开启电子合同插件，否则无效</div>
							</el-form-item>
							<el-form-item label="说明">
								<el-input type="textarea" v-model="form.detail"></el-input>
							</el-form-item>
							<el-form-item label="是否启用">
								<el-radio-group v-model="form.enabled">
									<el-radio :label="0">不启用</el-radio>
									<el-radio :label="1">启用</el-radio>
								</el-radio-group>
							</el-form-item>
							<el-form-item label="开启自动升级">
								<el-radio-group v-model="form.is_auto">
									<el-radio :label="0">不启用</el-radio>
									<el-radio :label="1">启用</el-radio>
								</el-radio-group>
							</el-form-item>
							<el-form-item label="选择升级条件" v-if="form.is_auto == 1">
								<div style="display: flex;flex-wrap: wrap;justify-content: start;">
									<div class="flex-x-center flex-y-center condition-item"
										:class="item.selected == 1 ? 'condition-item-active' : ''"
										@click="clickItem(item)" v-if="condition_list.length"
										v-for="item in condition_list">{{ item.name }}</div>
								</div>

								<div style="margin-top: 20px;background-color: #F3F3F3;padding: 20px;">
									<el-form-item style="margin-bottom: 10px;" :label="item.name"
										v-if="item.selected == 1" v-for="item in condition_list">
										<el-input v-model="item.value" v-if="item.type == 0">
											<template slot="append">
												<span>{{ item.unit }}</span>
											</template>
										</el-input>
										<div v-if="item.type == 1">
											<el-table :data="item.value" style="width: 100%">
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
																{{ scope.row.name }}
															</div>
														</div>
													</template>
												</el-table-column>
												<el-table-column label="价格" width="80">
													<template slot-scope="scope">
														<span style="font-weight: bold;color: #000000;">￥{{
																scope.row.price
														}}</span>
													</template>
												</el-table-column>
												<el-table-column label="操作">
													<template slot-scope="scope">
														<el-button size="mini" type="text"
															@click="goodsDelete(scope.$index)">
															删除
														</el-button>
													</template>
												</el-table-column>
											</el-table>
											<div style="width: 100%;text-align: center;">
												<goods-picker @selected="selectedGoods" style="margin-right: 5px;"
													width="88" height="88">
													<el-button type="text">+添加商品({{ item.value.length }}/50)</el-button>
												</goods-picker>
											</div>
										</div>
										<div class="flex-y-center" v-if="item.type == 2">
											<el-input v-model="item.value">
												<template slot="prepend">
													<span>已经是等级ID</span>
												</template>
											</el-input>
											<el-input v-model="item.value1">
												<template slot="prepend">
													<span>且购买商品ID</span>
												</template>
											</el-input>
										</div>
									</el-form-item>
								</div>
							</el-form-item>
							<el-form-item label="升级时机">
								<el-radio-group v-model="form.buy_type">
									<el-radio :label="0">付款</el-radio>
									<el-radio :label="1">订单完成</el-radio>
								</el-radio-group>
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
			list: [],
			form: {
				id: '',
				name: '',
				level: '',
				detail: '',
				pic_url: '',
				enabled: 0,
				is_auto: 0,
				upgrade: '',
				discount: '',
				is_inviter: 0,
				is_contract_update: 0,
				contract_tpl_id: '',
			},
			condition_list: [
				{
					name: '条件1(消费满)',
					type: 0,
					value: '',
					selected: 0,
					unit: '元'
				},
				{
					name: '条件2(购买指定商品)',
					type: 1,
					value: [],
					selected: 0,
					unit: ''
				},
				{
					name: '条件3(自身等级基础)',
					type: 2,
					value: '',
					value1: '',
					selected: 0,
					unit: ''
				},

			],
		}
	},
	created() {

		if (this.$route.query.id) {
			this.form.id = this.$route.query.id;
			this.getLevel();
		}
		this.getDefaultList();
	},
	methods: {
		goodsDelete(index) {
			this.condition_list[1].value.splice(index, 1);
		},
		selectedGoods(e) {
			if (this.condition_list[1].value.length == 0) {
				this.condition_list[1].value = (e)
			} else {
				for (let i in e) {
					this.condition_list[1].value.push(e[i])
				}
			}
		},
		clickItem(row) {
			if (row.selected == 1) {
				row.selected = 0;
				return;
			}
			row.selected = 1;
		},
		getLevel() {
			this.is_loading = true;
			this.$request({
				url: '/mall/user/level-edit',
				data: this.form,
			}).then(res => {
				this.is_loading = false;
				if (res.code == 0) {
					this.form = res.data.level
					if (res.data.level.upgrade) {
						this.condition_list = Object.assign(this.condition_list, res.data.level.upgrade);
					}
				}
			})
		},
		save() {
			this.is_loading = true;

			if (this.form.is_discount == 1) {
				if (this.form.discount > 10 || this.form.discount < 0) {
					this.$message.warning('等级折扣不正确');
					return;
				}
			}
			this.form.upgrade = JSON.stringify(this.condition_list);
			this.$request({
				url: '/mall/user/level-edit',
				data: this.form,
				method: 'post'
			}).then(res => {
				this.is_loading = false;
				if (res.code == 0) {
					this.$message.success(res.msg);
					this.$go.back();
				}
			})

		},

		deleteIcon() {
			this.form.pic_url = '';
		},
		getDefaultList() {
			this.$request({
				url: '/mall/user/default-level'
			}).then(res => {
				if (res.code == 0) {
					this.list = res.data.list
				}

			})

		}
	}

}
</script>
<style>
.condition-item {
	width: 130px;
	height: 40px;
	border: solid #f3f3f3 1px;
	margin: 5px;
}

.condition-item:hover {
	cursor: pointer;
}

.condition-item-active {
	width: 130px;
	height: 40px;
	border: solid #22A1FF 1px;
	margin: 5px;
	color: #22A1FF;
}
</style>
