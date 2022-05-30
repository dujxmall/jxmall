<template>
	<div>
		<el-dialog title="物流信息" :visible.sync="dialogVisible" width="30%" :before-close="handleClose" >

			<div v-loading='is_loading'>
				<div v-if="status==1">
				
					<div class="flex-row" style="padding-bottom: 20px;">
						<span>{{company}}</span>
						<span style="padding: 0 10px;">{{express_no}}</span>
						<span>{{display_status}}</span>
				
					</div>
				
					<div>
						<el-scrollbar style="height:500px;">
							<el-timeline>
								<el-timeline-item :timestamp="item.AcceptTime" placement="top" v-if="list.length>0" v-for="(item,index) in list">
									<el-card>
										<h4>{{item.AcceptStation}}</h4>
										<p>更新于 {{item.AcceptTime}}</p>
									</el-card>
								</el-timeline-item>
				
							</el-timeline>
				
						</el-scrollbar>
				
					</div>
				</div>
				<div v-if="status==0">
					{{msg}}
				</div>
			</div>

			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogVisible = false">取 消</el-button>
				<el-button type="primary" @click="dialogVisible = false">确 定</el-button>
			</span>
		</el-dialog>

	</div>

</template>

<script>
	export default {
		name: 'Express',
		props: {
			express_code: {
				type: String,
				default: ''
			},
			express_no: {
				type: String,
				default: ''
			},
			express_name: {
				type: String,
				default: ''
			},
			showExpress: {
				type: Boolean,
				default: false
			}
		},
		watch: {
			showExpress(newVal, oldVal) {
				if (newVal) {
					this.dialogVisible = true;
					this.getExpressDetail();
				}
			},
			dialogVisible(newVal, oldVal) {
				if (!this.newVal) {
					this.$emit('expressClose');
				}
			}
		},
		data() {
			return {
				is_loading: false,
				dialogVisible: false,
				abstract_status: null,

				msg: '正在查询...',
				status: 0,
				company: "",

				display_status: "",

				list: []
			}
		},
		created: function() {



		},
		methods: {
			getExpressDetail() {

				this.is_loading = true;

				this.$request({
					url: '/common/express/query',
					data: {
						express_code: this.express_code,
						express_name: this.express_name,
						express_no: this.express_no
					}

				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.display_status = res.data.display_status;
						this.list = res.data.list;
						this.company = res.data.company
						this.abstract_status = res.data.abstract_status
						this.status = 1;
					} else {
						this.msg = res.msg
					}
				})


			},

			handleClose(e) {
				
				this.dialogVisible = false;
			}
		}

	}
</script>

<style>
	.el-scrollbar__wrap {
	    overflow-x: hidden;
	}
</style>
