<template>

	<div class="app-container">
    <el-card class="box-card" v-loading="is_loading">
      <div slot="header" class="clearfix">
        <span>等级设置</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
      </div>


      <el-row>
        <el-col :span="14">
          <el-form ref="form" :model="form" label-width="130px">
            <el-form-item label="等级权重">
              <el-tag closable @close="form.level=''" v-if="form.level!==''&&form.level!=='0'">
                {{form.level}}级
              </el-tag>
              <el-tag @close="form.level=''" v-if="form.level=='0'">
                默认等级
              </el-tag>
              <el-select v-model="form.level" v-if="form.level===''" placeholder="请选择">
                <el-option v-for="item in default_list" v-if="item.is_use==0" :key="item.level" :label="item.name" :value="item.level">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="等级名称">
              <el-input v-model="form.name"></el-input>
            </el-form-item>
            <div v-if="setting">
              <el-form-item label="一级佣金" v-if="setting.level>=1">
                <el-input v-model="form.first_price">
                  <template slot="append">
                    <span v-if="form.price_type==0">元</span>
                    <span v-if="form.price_type==1">%</span>
                  </template>

                </el-input>
              </el-form-item>
              <el-form-item label="二级佣金" v-if="setting.level>1">
                <el-input v-model="form.second_price">
                  <template slot="append">
                    <span v-if="form.price_type==0">元</span>
                    <span v-if="form.price_type==1">%</span>
                  </template>
                </el-input>
              </el-form-item>
              <el-form-item label="三级佣金" v-if="setting.level>2">
                <el-input v-model="form.third_price">
                  <template slot="append">
                    <span v-if="form.price_type==0">元</span>
                    <span v-if="form.price_type==1">%</span>
                  </template>
                </el-input>
              </el-form-item>
            </div>
            <el-form-item label="佣金类型">
              <el-radio-group v-model="form.price_type">
                <el-radio :label="'0'">固定金额</el-radio>
                <el-radio :label="'1'">百分比</el-radio>
              </el-radio-group>
            </el-form-item>
            <div v-if="form.level!='0'">
              <el-form-item label="是否自动升级">
                <el-radio-group v-model="form.upgrade.is_auto">
                  <el-radio :label="'0'">否</el-radio>
                  <el-radio :label="'1'">是</el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="升级方式">
                <el-radio-group v-model="form.upgrade.type">
                  <el-radio :label="'0'">满足任一条件</el-radio>
                  <el-radio :label="'1'">满足所有条件</el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="选择升级条件">
                <div style="display: flex;flex-wrap: wrap;justify-content: start;">
                  <div class="flex-x-center flex-y-center condition-item" :class="item.selected==1?'condition-item-active':''"
                       @click="clickItem(item)" v-if="list.length" v-for="item in list">{{item.name}}</div>
                </div>

                <div style="margin-top: 20px;background-color: #F3F3F3;padding: 20px;">
                  <el-form-item style="margin-bottom: 10px;" :label="item.name" v-if="item.selected==1" v-for="item in list">
                    <el-input v-model="item.value" v-if="item.type!=10">
                      <template slot="append">
                        <span>{{item.unit}}</span>
                      </template>
                    </el-input>
                    <div v-if="item.type==10">
                      <el-table :data="item.value" style="width: 100%">
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
                      <div style="width: 100%;text-align: center;">
                        <goods-picker @selected="selectedGoods" style="margin-right: 5px;" width="88" height="88">
                          <el-button type="text">+添加商品({{item.value.length}}/50)</el-button>
                        </goods-picker>
                      </div>
                    </div>


                  </el-form-item>

                </div>

              </el-form-item>


            </div>
          </el-form>
        </el-col>

      </el-row>

    </el-card>
  </div>

</template>

<script>
	export default {
		data() {
			return {
				form: {
					id: '',
					first_price: "",
					level: '',
					name: "",
					price_type: '0',
					second_price: "",
					third_price: "",
					upgrade: {
						is_auto: '0',
						type: '0',
						list: [],
					},
				},
				list: [{
						name: '分销订单总额',
						type: 0,
						value: '',
						selected: 0,
						unit: '元'
					},
					{
						name: '分销订单总数',
						type: 1,
						value: '',
						selected: 0,
						unit: '个'
					}, {
						name: '一级分销订单总额',
						type: 2,
						value: '',
						selected: 0,
						unit: '元'
					}, {
						name: '一级分销订单总数',
						type: 3,
						value: '',
						selected: 0,
						unit: '个'
					}, {
						name: '自购订单总额',
						type: 4,
						value: '',
						selected: 0,
						unit: '元'
					}, {
						name: '自购订单总数',
						type: 5,
						value: '',
						selected: 0,
						unit: '个'
					}, {
						name: '下级总人数',
						type: 6,
						value: '',
						selected: 0,
						unit: '人'
					},
					{
						name: '下级分销商人数',
						type: 7,
						value: '',
						selected: 0,
						unit: '人'
					}, {
						name: '一级下线人数',
						type: 8,
						value: '',
						selected: 0,
						unit: '人'
					}, {
						name: '一级下线分销商',
						type: 9,
						value: '',
						selected: 0,
						unit: '人'
					}, {
						name: '购买指定商品',
						type: 10,
						value: [],
						selected: 0,
						unit: ''
					},

				],
				setting: null,
				default_list: [],
				is_loading: false,
			}
		},
		created() {
			if (this.$route.query.id) {
				this.form.id = this.$route.query.id;
				this.getLevel();
			}
			this.getLevelDefault();
		},


		methods: {
			goodsDelete(index) {
				this.list[10].value.splice(index, 1);
			},
			selectedGoods(e) {
				if (this.list[10].value.length == 0) {
					this.list[10].value = (e)
				} else {
					for (let i in e) {
						this.list[10].value.push(e[i])
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
			save() {
				this.is_loading = true;
				this.form.upgrade.list = this.list;
				this.$request({
					url: "/plugin/share/mall/share/level",
					data: this.form,
					method: "post"
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.$message.success(res.msg)
						this.$go.back()
					}
				}).catch(e => {
					this.is_loading = false;
				})
			},
			getLevel() {
				this.is_loading = true;
				this.$request({
					url: "/plugin/share/mall/share/level",
					data: {
						id: this.form.id
					},

				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.form = res.data.level
						if (res.data.level.upgrade) {
							this.list = res.data.level.upgrade.list
						} else {
							this.form.upgrade = {
								is_auto: '0',
								type: '0',
								list: [],
							}
						}
					}
				}).catch(e => {
					this.is_loading = false;
				})
			},
			getLevelDefault() {
				this.is_loading = true;
				this.$request({
					url: "/plugin/share/mall/share/share-default",
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.default_list = res.data.list;
						this.setting = res.data.setting;
					}
				}).catch(e => {
					this.is_loading = false;
					this.$router.replace({
						path: '/plugins/share/setting'
					})
				})
			},

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
