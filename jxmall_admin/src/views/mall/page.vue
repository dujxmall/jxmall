<template>
	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>页面路径</span>
			</div>

			<div style="display: flex;flex-wrap: wrap;white-space: nowrap;justify-content: start;">
				<div style="width: 239px;background: #F3F3F3;border-radius: 5px;padding: 10px;margin-right:10px;margin-bottom: 10px;"
					v-if="list.length" v-for="(item,index) in list">
					<div>
						<div style="font-weight: bold;font-size: 18px;" class="flex-x-between">
							<div>{{item.name}}</div>
							<div>
								<el-button type="text" @click="save(item)" v-if="item.is_show_save">保存</el-button>
							</div>
						</div>
						<div style="color: #22A1FF;padding: 5px 0;">{{item.page}}</div>
						<el-button type="text" style="color: #22A1FF;padding: 5px 0;" @click="showH5(item.page)">h5链接
						</el-button>
					</div>
					<div>
						<el-input @input="showSaveBtn(item)" placeholder="输入页面标题" v-model="item.name"></el-input>
					</div>
				</div>
			</div>

			<el-dialog title="二维码链接" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
				<div v-loading="loading" style="text-align: center;">
					<vue-qr :text="link" :size="200"></vue-qr>
					<div>{{link}}</div>
				</div>

				<span slot="footer" class="dialog-footer">
					<el-button @click="dialogVisible = false">取 消</el-button>
					<el-button type="primary" @click="dialogVisible = false">确 定</el-button>
				</span>
			</el-dialog>

		</el-card>

	</div>
</template>

<script>
	import vueQr from 'vue-qr'
	import QRCode from 'qrcodejs2'
	export default {
	  name:'mall-page',
		components: {
			vueQr
		},
		data() {
			return {
				loading: false,
				list: [],
				h5Root: '',
				mall_id: '',
				dialogVisible: false,
				link: '',

			}
		},
		created() {
			this.getPage();
		},
		methods: {
			handleClose() {
				this.dialogVisible = false;
			},
			showSaveBtn(row) {

				 
				row.is_show_save = true;
			},
			showH5(page) {
				this.dialogVisible = true;
				let link = this.h5Root + '/#' + page + "?mall_id=" + this.mall_id;
				this.link = link;
			},
			save(row) {
			 
				if (!row.name || row.name == '') {
					this.$message.error('页面标题不能为空');
					return;
				}
				this.$request({
					url: '/mall/mall/page-edit',
					data: row,
					method: 'post'
				}).then(res => {

					if (res.code == 0) {
						this.getPage();
					}

				})

			},
			getPage() {
				this.$request({
					url: '/mall/mall/page'
				}).then(res => {
					if (res.code == 0) {
						this.list = res.data.list;
						this.mall_id = res.data.mall_id;
						this.h5Root = res.data.h5Root;
					}
				})
			}
		}
	}
</script>

<style lang="scss">
	.qrcode {
		display: inline-block;

		img {
			width: 132px;
			height: 132px;
			background-color: #fff; //设置白色背景色
			padding: 6px; // 利用padding的特性，挤出白边
			box-sizing: border-box;
		}
	}
</style>
