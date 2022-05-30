<template id="test">

	<div class="container">
		<div class="diy-component-preview">
			<div class="content" style="position: relative;">
				<el-image style="width: 98%;margin: 0 auto;border-radius: 10px;" :src="data.pic_url" v-if="data.pic_url!=''"></el-image>
				<div style="height: 100px;width: 100%;text-align: center" class="flex-y-center flex-x-center"
					v-if="data.pic_url==''">
					<i class="el-icon-edit"></i>
				</div>
				
				<div v-if="value.list.length" v-for="item in value.list" 
						:style="{left:item.starX+'px',top:item.starY+'px',width:item.areaWidth+'px',height:item.areaHeight+'px'}"
						style="position: absolute;border:dashed #C6E0FA 1px;color: #FFFFFF;" class="flex-y-center flex-x-center">						 
					  {{item.name}}
				</div>
				
			</div>
		</div>
		<div class="diy-component-edit">
			<el-form label-width="100px">
				<el-form-item label="选择热图">
					<el-input v-model="data.name" placeholder="选择热图">
						<template slot="append">
							<span @click="showHotPicker">选择</span>
						</template>
					</el-input>
				</el-form-item>
				<el-form-item v-if="data.list.length" v-for="(item,index) in data.list" :label="item.name">
					<el-input v-model="item.url" placeholder="点击选择链接" readonly size="small">
						<link-picker v-model="item.url" slot="append">
							<el-button size="small">选择链接</el-button>
						</link-picker>
					</el-input>
				</el-form-item>
			</el-form>
		</div>
		<el-dialog title="热图列表" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
			<div>
				<el-table :data="list" border style="width: 100%">
					<el-table-column prop="id" label="ID">
					</el-table-column>
					<el-table-column prop="name" label="名称">
					</el-table-column>
					<el-table-column label="操作">
						<template slot-scope="scope">
							<el-button type="text" @click="confirm(scope.row)">选择</el-button>
						</template>
					</el-table-column>
				</el-table>
				<div style="text-align: right;margin: 20px 0;">
					<pagination :pagination="pagination" @pageChage="getList">
					</pagination>
				</div>
			</div>
			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogVisible = false">取 消</el-button>
			</span>
		</el-dialog>

	</div>

</template>

<script>
	import LinkPicker from '@/components/mall/LinkPicker'
	export default {
		components: {
			LinkPicker
		},
		name: 'HotImage',
		props: {
			value: {
				type: Object,
				default: () => {
					return null;
				}
			},
			active: {
				type: Boolean,
				default: false
			}
		},
		data() {
			return {
				pagination: null,
				list: [],
				dialogVisible: false,

				data: {
					hot_image_id: '',
					list: [],
					name: '',
					pic_url: '',
				},

				page: 1,
			}
		},
		computed: {
			cContainerStyle() {
				return `background:${this.data.background};`;
			},
			cNavStyle() {
				return `width:${100 / this.data.columns}%;`;
			},
		},
		created() {
			if (!this.value) {
				this.$emit('input', this.data)
			} else {
				this.data = this.value;
			}
		 
		},

		computed: {
			cContainerStyle() {
				return `background:${this.data.background};`;
			},
			cNavStyle() {
				return `width:${100 / this.data.columns}%;`;
			},
		},
		watch: {
			data: {
				deep: true,
				handler(newVal, oldVal) {
					this.$emit('input', newVal, oldVal);
				},
			}
		},
		methods: {
			getList() {
				this.is_loading = true;
				this.$request({
					url: '/mall/mall/hot-image-list',
					data: {
						page: this.page,
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.list = res.data.list
						this.pagination = res.data.pagination
					}
				})
			},
			handleClose(e) {
				this.dialogVisible = false;
			},
			showHotPicker(e) {
				this.dialogVisible = true;
				this.getList();
			},
			confirm(e) {

				this.data.hot_image_id = e.id;
				this.data.name = e.name;
				this.data.list = JSON.parse(e.data);
				this.data.pic_url = e.pic_url;
				this.handleClose();
			}
		}
	}
</script>

<style lang="scss">
	.container {
		.edit-item {
			border: 1px solid #e2e2e2;
			line-height: normal;
			padding: 5px;
			margin-bottom: 5px;
			max-width: 450px;
		}
	}
</style>
