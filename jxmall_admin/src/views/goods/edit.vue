<template>

	<div class="app-container">
		<el-card class="box-card" v-loading='is_loading'>
			<div slot="header" class="clearfix">
				<span>商品编辑</span>
				<div style="float: right;">
					<el-button type="text" @click="cancel" style="color: #606266;">取消</el-button>
					<el-button type="text" @click="submit" :loading="is_submiting">保存</el-button>
				</div>
			</div>
			<div>
				<el-form ref="form" :model="form" label-width="180px">
					<el-tabs v-model="activeName">
						<el-tab-pane label="基础信息" name="basic">

							<el-row>
								<el-col :span="10">
									<el-form-item label="已选分类" v-if="form.cat_list.length > 0">
										<div class="flex-row">
											<div class="tag-box">
												<el-tag type="warning" class="tag-item" @close="deleteCat(index)"
													closable v-for="(item, index) in form.cat_list">
													{{ item.name }}
												</el-tag>
											</div>
										</div>
									</el-form-item>
									<el-form-item label="选择分类">
										<el-cascader style="width: 100%;" @change="change" :options="cat_level_list"
											:show-all-levels="true"
											:props="{ multiple: true, checkStrictly: true, label: 'name', value: 'id' }"
											clearable>
											<template slot-scope="{ node, data }">
												<span>{{ data.name }}</span>
												<span v-if="!node.isLeaf"> ({{ data.children.length }}) </span>
											</template>
										</el-cascader>
									</el-form-item>
									<el-form-item label="商品名称">
										<el-input v-model="form.name"></el-input>
									</el-form-item>
									<el-form-item label="小标题">
										<el-input type="textarea" v-model="form.subtitle"></el-input>
									</el-form-item>
									<el-form-item label="排序">
										<el-input v-model="form.sort" type="number"></el-input>
										<span style="color: #C1C1C1;font-size: 10px;">数字越大，越靠前</span>
									</el-form-item>
									<el-form-item label="商品编号">
										<el-input v-model="form.no"></el-input>
									</el-form-item>
									<el-form-item label="缩略图">
										<single-image-selector size="768*768" :url="form.cover_pic"
											v-model="form.cover_pic">
										</single-image-selector>
									</el-form-item>
									<el-form-item label="轮播图">
										<div style="display: flex;flex-wrap: wrap;">
											<images-selector size="768*868" :count="6" :list="form.pic_list"
												v-model="form.pic_list"></images-selector>
										</div>
									</el-form-item>
									<el-form-item label="售价">
										<el-input type="number" v-model="form.price" step="0.01"></el-input>
									</el-form-item>
									<el-form-item label="原价">
										<el-input type="number" v-model="form.origin_price" step="0.01"></el-input>
									</el-form-item>
									<el-form-item label="成本价">
										<el-input type="number" v-model="form.cost_price" step="0.01"></el-input>
									</el-form-item>
									<el-form-item label="是否限购">
										<el-radio-group v-model="form.is_limit">
											<el-radio :label="0">否</el-radio>
											<el-radio :label="1">是</el-radio>
										</el-radio-group>
									</el-form-item>
									<el-form-item label="限购数量" v-if="form.is_limit == 1">
										<el-input type="number" v-model="form.limit_num" step="1"></el-input>
									</el-form-item>
									<el-form-item label="开启面议">
										<el-radio-group v-model="form.is_negotiable">
											<el-radio :label="0">不开启</el-radio>
											<el-radio :label="1">开启</el-radio>
										</el-radio-group>
									</el-form-item>
									<!--  -->
									<el-form-item label="自定义分享标题">
										<el-input v-model="form.share_title"></el-input>
									</el-form-item>
									<el-form-item label="单位">
										<el-input v-model="form.unit"></el-input>
									</el-form-item>
									<el-form-item label="商品重量">
										<el-input v-model="form.weight" placeholder="请填写重量" type="number" step="0.01">
										</el-input>
									</el-form-item>
									<el-form-item label="总库存">
										<el-input type="number" v-model="form.total_stock">
										</el-input>
									</el-form-item>
									<el-form-item label="当前库存">
										<el-input type="number" v-model="form.stock" :disabled="form.is_attr == 1">
										</el-input>
									</el-form-item>
									<el-form-item label="库存预警">
										<el-input type="number" v-model="form.warning_stock">
										</el-input>
									</el-form-item>
									<el-form-item label="销量">
										<el-input type="number" v-model="form.sales" step="1" :disabled="true">
										</el-input>
									</el-form-item>
									<el-form-item label="虚拟销量">
										<el-input type="number" v-model="form.virtual_sales" step="1"></el-input>
									</el-form-item>
									<el-form-item label="上架">
										<el-switch v-model="form.status" :active-value="1" :inactive-value="0">
										</el-switch>
									</el-form-item>
									<el-form-item label="设为推荐">
										<el-switch v-model="form.is_recommend" :active-value="1" :inactive-value="0">
										</el-switch>
									</el-form-item>
								</el-col>
							</el-row>
						</el-tab-pane>
						<el-tab-pane label="商品详情" name="detail">
							<scroll-bar>
								<el-form-item label="商品详情">
									<rich-text @contentChange='detailChange' :content="form.detail"></rich-text>
								</el-form-item>
							</scroll-bar>
						</el-tab-pane>
						<el-tab-pane label="使用多规格" name="attr">
							<scroll-bar :height="700">
								<el-form-item label="使用多规格">
									<el-switch v-model="form.is_attr" :active-value="1" :inactive-value="0">
									</el-switch>
								</el-form-item>
								<el-form-item label="商品规格" v-if="form.is_attr == 1">
									<attr-group @select="makeAttrGroup" v-model="attrGroups"></attr-group>
									<attr v-model="form.attr_list" :attr-groups="attrGroups"
										v-if="form.attr_list.length"></attr>
								</el-form-item>
							</scroll-bar>
						</el-tab-pane>
						<el-tab-pane label="积分营销" name="integral">
							<scroll-bar :height="700">
								<el-row>
									<el-col :span="12">
										<el-form-item label="开启积分抵扣">
											<el-radio-group v-model="form.is_integral">
												<el-radio :label="0">不开启</el-radio>
												<el-radio :label="1">开启</el-radio>
											</el-radio-group>
										</el-form-item>
										<div v-if="form.is_integral == 1">
											<div>
												<el-form-item label="使用积分">
													<el-input v-model="form.use_integral">
														<template slot="append">积分</template>
													</el-input>
												</el-form-item>
												<el-form-item label="抵扣金额">
													<el-input v-model="form.integral_price">
														<template slot="append">元</template>
													</el-input>
												</el-form-item>
											</div>
										</div>
									</el-col>
								</el-row>
							</scroll-bar>
						</el-tab-pane>
						<el-tab-pane label="会员价" name="member_price">
							<scroll-bar :height="700">
								<el-row>
									<el-col :span="12">
										<el-form-item label="开启会员价">
											<el-radio-group v-model="form.is_member_price" @change="memberPriceChange">
												<el-radio :label="0">不开启</el-radio>
												<el-radio :label="1">开启</el-radio>
											</el-radio-group>
										</el-form-item>

										<el-form-item label="会员价设置" v-if="form.is_member_price == 1">
											<div class="flex-row" style="font-weight: bold;">
												<div style="width: 200px;">等级名称</div>
												<div style="width: 200px;">价格</div>
											</div>
											<div class="flex-row" style="margin-bottom: 10px;"
												v-if="form.level_price.length > 0"
												v-for="(item, i) in form.level_price">
												<div style="width: 200px;">{{ item.level_name }}</div>
												<div style="width: 200px;">
													<el-input v-model="item.price" type="number" min="0" step="0.01"
														placeholder="请输入会员价" @input="priceInput">
														<template slot="append">元</template>
													</el-input>
												</div>
											</div>
											<el-button type="danger" size="mini" @click="freshMemberPrice">重置会员价
											</el-button>
										</el-form-item>
									</el-col>
								</el-row>
							</scroll-bar>
						</el-tab-pane>

						<el-tab-pane label="权限设置" name="permission">


							<div
								style="width: 90%;height: 40px;background-color:#F4F6F8 ;color: #000000;font-weight: bold;line-height: 40px;padding: 0 20px;">
								商品浏览权限
							</div>

							<el-form-item label="会员等级">
								<el-radio-group v-model="form.permission.explode_is_level">
									<el-radio :label="0">不限制会员等级</el-radio>
									<el-radio :label="1">指定会员可见</el-radio>
								</el-radio-group>
							</el-form-item>
							<el-form-item v-if="form.permission.explode_is_level == 1">
								<el-checkbox-group v-model="form.permission.explode_level_ids"
									@change="levelCheckboxChange" v-if="level_list.length > 0">
									<el-checkbox :label="level.id" v-for="(level, index) in level_list">{{ level.name }}
									</el-checkbox>
								</el-checkbox-group>
							</el-form-item>

							<div
								style="width: 90%;height: 40px;background-color:#F4F6F8 ;color: #000000;font-weight: bold;line-height: 40px;padding: 0 20px;">
								商品购买权限
							</div>

							<el-form-item label="会员等级">
								<el-radio-group v-model="form.permission.buy_is_level">
									<el-radio :label="0">不限制会员等级</el-radio>
									<el-radio :label="1">指定会员可购买</el-radio>
								</el-radio-group>
							</el-form-item>
							<el-form-item v-if="form.permission.buy_is_level == 1">
								<el-checkbox-group v-model="form.permission.buy_level_ids" @change="levelCheckboxChange"
									v-if="level_list.length > 0">
									<el-checkbox :label="level.id" v-for="(level, index) in level_list">{{ level.name }}
									</el-checkbox>
								</el-checkbox-group>
							</el-form-item>


						</el-tab-pane>

						<el-tab-pane label="运费设置" name="express">
							<scroll-bar :height="700">
								<el-form-item label="运费设置">
									<el-radio-group v-model="form.express_type">
										<el-radio :label="0">包邮</el-radio>
										<el-radio :label="1">统一设置</el-radio>
										<el-radio :label="2">运费模板</el-radio>
									</el-radio-group>
									<el-row>
										<el-col :span="7">
											<el-input v-if="form.express_type == 1" placeholder="设置统一运费"
												v-model="form.freight_price"></el-input>
											<el-select v-model="freight.name" @change="freightChange" placeholder="请选择"
												v-if="form.express_type == 2">
												<el-option v-for="item in freight_list" :key="item.name"
													:label="item.name" :value="item">
												</el-option>
											</el-select>
										</el-col>
									</el-row>
								</el-form-item>
							</scroll-bar>
						</el-tab-pane>
					</el-tabs>


				</el-form>
			</div>
		</el-card>
		<cat-picker :showCatPicker='showCatPicker' @selected='selectCat' @closed='closeCat'></cat-picker>
	</div>
</template>

<script>
import CatPicker from '@/components/mall/goods/CatPicker'
import FilePicker from '@/components/common/FilePicker'
import AttrGroup from '@/components/mall/goods/AttrGroup'
import Attr from '@/components/mall/goods/Attr'
import RichText from '@/components/common/RichText'

export default {
	name: 'goods-edit',
	components: {
		FilePicker,
		CatPicker,
		AttrGroup,
		Attr,
		RichText
	},
	data() {
		return {
			showCatPicker: false,
			is_loading: false,
			is_submiting: false,
			attrGroups: [], //规格组
			activeName: 'basic',
			freight_name: '',
			freight: {
				id: 0,
				name: ''
			},
			value: '',
			checkList: [],
			freight_list: [],
			cat_level_list: [],
			level_list: [],
			form: {
				warning_stock: 0,
				total_stock: 0,
				is_negotiable: 0,
				subtitle: '',
				is_integral: 0,
				integral_price: 0,
				use_integral: '',
				use_integral_type: 0,
				freight_id: '0',
				freight_price: '0.00',
				express_type: 0,
				id: '',
				name: '',
				no: '',
				cover_pic: '',
				pic_list: [],
				is_recommend: 0,
				status: 0,
				weight: '',
				is_attr: 0,
				is_limit: 0,
				is_member_price: 0,
				limit_num: 0,
				attr_list: [],
				attr_group_list: [],
				origin_price: '',
				price: '',
				cost_price: '',
				unit: '件',
				virtual_sales: 0,
				sales: 0,
				share_title: '',
				sort: 0,
				cat_list: [],
				level_price: [],
				detail: '',
				cat_ids: [],
				permission: {
					explode_is_level: 0,
					explode_level_ids: [],
					buy_is_level: 0,
					buy_level_ids: []
				}

			}
		}
	},
	created() {
		this.getFreight()
		this.getCatLevel()
		this.getLevelList()
	},
	mounted() {
		this.form.id = this.$route.query.id
		if (this.form.id) {
			this.getGoods()
		}
	},
	methods: {
		freshMemberPrice() {
			let level_price = []
			if (this.level_list.length == 0) {
				this.$message.error('请先添加会员等级')
				this.form.is_member_price = 0
				return
			}

			if (level_price.length == 0) {
				this.level_list.forEach((item) => {
					let newItem = {
						level_id: item.id,
						level_name: item.name,
						level: item.level,
						price: ''
					}
					level_price.push(newItem)
				})
			}

			this.form.level_price = level_price

			this.$forceUpdate()

		},

		memberPriceChange(e) {

			if (e == 1) {
				if (this.level_list.length == 0) {
					this.$message.error('请先添加会员等级')
					this.form.is_member_price = 0
					return
				}
				this.form.level_price = []
				if (this.form.level_price.length == 0) {
					this.level_list.forEach((item) => {
						let newItem = {
							level_id: item.id,
							level_name: item.name,
							level: item.level,
							price: ''
						}
						this.form.level_price.push(newItem)
					})
				}
				this.$forceUpdate()

			}
		},
		priceInput(e) {
			this.$forceUpdate()
		},
		levelCheckboxChange(e) {

		},
		getLevelList() {
			this.$request({
				url: '/mall/user/level'
			}).then(res => {
				if (res.code == 0) {
					let level_list = [{
						id: '0',
						level: -1,
						name: '普通用户'
					}]
					this.level_list = level_list.concat(res.data.list)
				}
			})

		},

		change(e) {
			this.form.cat_ids = []
			if (e.length) {
				e.forEach((item) => {
					this.form.cat_ids = this.form.cat_ids.concat(item)
				})
			} else {
				this.form.cat_ids = []
			}

			this.form.cat_ids = Array.from(new Set(this.form.cat_ids))
		},
		getCatLevel() {
			this.$request({
				url: '/mall/goods/all-cat-level'
			}).then(res => {

				if (res.code == 0) {
					this.cat_level_list = res.data.list
				}
			})
		},
		freightChange(e) {
			this.freight = e
		},
		getFreight() {
			this.$request({
				url: '/mall/mall/freight-list',
				data: {
					enabled: 1
				}
			}).then(res => {
				if (res.code == 0) {
					this.freight_list = res.data.list
				}
			})
		},
		detailChange(e) {
			this.form.detail = e.content
		},
		deleteCat(index) {
			this.form.cat_ids.splice(index, 1)
			this.form.cat_list.splice(index, 1)
		},
		closeCat() {
			this.showCatPicker = false
		},
		selectCat(e) {

			this.form.cat_list = []
			for (var i = 0; i < e.length; i++) {
				this.form.cat_list.push({
					id: e[i].id,
					name: e[i].name
				})
			}
		},
		openCatPicker() {
			this.showCatPicker = true
		},
		getGoods() {
			this.is_loading = true
			this.$request({
				url: '/mall/goods/edit',
				data: {
					id: this.form.id
				}
			}).then(res => {
				this.is_loading = false
				if (res.code == 0) {
					this.form = res.data.info
					if (res.data.info.is_attr == 1) {
						if (res.data.info.attr_group_list) {
							this.attrGroups = res.data.info.attr_group_list
						}
					}
					if (res.data.info.express_type == 2) {
						if (res.data.info.freight) {
							this.freight = res.data.info.freight
						}
					}

				}
			})
		},
		// 规格组合
		makeAttrGroup(e) {
			let self = this
			let array = []
			self.attrGroups.forEach(function (attrGroupItem, attrGroupIndex) {
				attrGroupItem.attr_list.forEach(function (attrListItem, attrListIndex) {
					let object = {
						attr_group_id: attrGroupItem.attr_group_id,
						attr_group_name: attrGroupItem.attr_group_name,
						attr_id: attrListItem.attr_id,
						attr_name: attrListItem.attr_name
					}

					if (!array[attrGroupIndex]) {
						array[attrGroupIndex] = []
					}
					array[attrGroupIndex].push(object)
				})
			})

			// 2.属性排列组合
			const res = array.reduce((osResult, options) => {
				return options.reduce((oResult, option) => {
					if (!osResult.length) {
						return oResult.concat(option)
					} else {
						return oResult.concat(osResult.map(o => [].concat(o, option)))
					}
				}, [])
			}, [])

			// 3.组合结果赋值
			for (let i in res) {
				const options = Array.isArray(res[i]) ? res[i] : [res[i]]
				const row = {
					attr_list: options,
					stock: 0,
					price: 0,
					no: '',
					weight: 0,
					pic_url: ''
				}
				let extra = {}
				if (this.form && this.form.extra) {
					extra = JSON.parse(JSON.stringify(this.form.extra))
					for (let i in extra) {
						row[i] = 0
					}
				}
				// 动态绑定多规格会员价

				// 3-1.已设置数据的优先使用原数据
				if (self.form.attr_list.length) {
					for (let j in self.form.attr_list) {
						const oldOptions = []
						for (let k in self.form.attr_list[j].attr_list) {
							oldOptions.push(self.form.attr_list[j].attr_list[k].attr_name)
						}
						const newOptions = []
						for (let k in options) {
							newOptions.push(options[k].attr_name)
						}
						if (oldOptions.toString() === newOptions.toString()) {
							row['price'] = self.form.attr_list[j].price
							row['stock'] = self.form.attr_list[j].stock
							row['no'] = self.form.attr_list[j].no
							row['weight'] = self.form.attr_list[j].weight
							row['pic_url'] = self.form.attr_list[j].pic_url
							break
						}
					}
				}
				res[i] = row
			}

			self.form.attr_list = res
		},
		submit() {
			if (this.form.cat_ids.length == 0) {
				this.$message.warning('请选择分类')
				return
			}
			if (this.form.express_type == 2) {
				if (this.freight.id == 0) {
					this.$message.error('请选择运费模板')
					return
				}
				this.form.freight_id = this.freight.id
			}
			this.is_submiting = true
			this.form.attr_group_list = this.attrGroups
			this.$request({
				url: '/mall/goods/edit',
				data: this.form,
				method: 'post'
			}).then(res => {
				this.is_submiting = false

				if (res.code == 0) {
					this.$message.success(res.msg)
					this.$go.back()
				} else {
					this.$message.error(res.msg)
				}
			}).catch(e => {
				this.is_submiting = false

			})

		},
		deleteCoverPic() {

			this.form.cover_pic = ''
		},
		chooseCoverPic(e) {

			if (e.length > 0) {
				this.form.cover_pic = e[0].url
			}
		},
		choosePicList(e) {
			if (e.length > 0) {
				for (let i in e) {
					if (this.form.pic_list.length < 6) {
						this.form.pic_list.push(e[i].url)
					} else {

						this.$message.warning('最多添加6张图片')
						break
					}

				}
			}
		},
		cancel() {
			this.$go.back()
		},
		deletePic(index) {
			if (this.form.pic_list && this.form.pic_list.length > 0) {
				this.form.pic_list.splice(index, 1)
			} else {
				this.form.pic_list = []
			}

		}
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
