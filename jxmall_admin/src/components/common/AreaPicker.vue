<template>
	<el-dialog title="提示" :visible.sync="dialogVisible" width="60%">

		<div>
			<el-row>
				<el-col :span="11">
					<div style="border: solid #f3f3f3 1px;" class="list">
						<el-scrollbar wrap-class="list" wrap-style="color: red; height:100%;" view-style="font-weight: bold;height:100%;">
							<el-tree show-checkbox highlight-current ref="tree" node-key="code" :data="area_list" :props="defaultProps"
							 @check-change="checkedChange">
								<span class="custom-tree-node" slot-scope="{ node, data }">
									<span>{{ node.label }}</span>
								</span>
							</el-tree>
						</el-scrollbar>
					</div>
				</el-col>
				<el-col :span="2">
					<div style="height: 450px;font-size: 25px;font-weight: bold;" class="flex-y-center flex-x-center"> <i class="el-icon-arrow-right"></i>
					</div>
				</el-col>

				<el-col :span="11">
					<div style="border: solid #f3f3f3 1px;" class="list">
						<el-scrollbar wrap-class="list" wrap-style=" height:100%;" view-style="font-weight: bold;height:100%;">
							<span v-if="select_list.length" v-for="item in select_list">
								{{item.name}},
							</span>
						</el-scrollbar>
					</div>
				</el-col>
			</el-row>
		</div>
		<span slot="footer" class="dialog-footer">
			<el-button @click="dialogVisible = false">取 消</el-button>
			<el-button type="primary" @click="confirm">确 定</el-button>
		</span>
	</el-dialog>
</template>

<script>
	export default {
		props: {
			openDialog: {
				type: Boolean,
				default: false
			},
		},
		data() {
			return {
				dialogVisible: false,
				area_list: [],
				defaultProps: {
					children: 'list',
					label: 'name'
				},
				defaultProps1: {
					children: 'list',
					label: 'name'
				},
				select_list: []
			}
		},
		created() {
			let area_list = localStorage.getItem('AREA_DATA');
			if (area_list) {
				this.area_list = JSON.parse(area_list);
				 
				return;
			}
			this.$request({
				url: '/common/address/area-data'
			}).then(res => {
				if (res.code == 0) {
					this.area_list = res.data.list
					localStorage.setItem('AREA_DATA', JSON.stringify(res.data.list))
				}
			})
		},
		watch: {
			openDialog(newVal, oldVal) {
				this.dialogVisible = newVal
			},
			dialogVisible(newVal, oldVal) {
				if (!newVal) {
					this.$emit('closed')
				}
			}
		},
		methods: {
			checkedChange() {

				this.select_list = (this.getCheckedNodes());

			},
			getCheckedNodes() {
				return this.$refs.tree.getCheckedNodes();
			},
			getCheckedKeys() {
				return (this.$refs.tree.getCheckedKeys());
			},

			handleNodeClick(data) {
			 
			},
			confirm() {
				let allNodes = this.getCheckedNodes();
				let allKeys = this.getCheckedKeys();

				this.dialogVisible = false;
				this.$emit('success', {
					checked_keys: allKeys,
					checked_nodes: allNodes
				});
			},
		}
	}
</script>

<style scoped>
	.list {
		height: 450px;
		max-height: 450px;
	}

	.custom-tree-node {
		flex: 1;
		display: flex;
		align-items: center;
		justify-content: space-between;
		font-size: 14px;
		padding-right: 8px;
	}

	::-webkit-scrollbar {
		display: none;
		/*隐藏滚动条*/
	}

	.add-area {
		width: 100%;
		text-align: center;
		color: #409EFF;

	}

	.add-area:hover {
		cursor: pointer;
	}

	.page-component__scroll {
		height: 100%;
	}
</style>
