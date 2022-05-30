<template>
	<div class="app-container">
		<el-card class="box-card" v-loading='is_loading'>
			<div slot="header" class="clearfix">
				<span>参数设置</span>
				<el-button style="float: right; padding: 3px 20px;" type="text" @click="submit">保存</el-button>
				<el-button style="float: right; padding: 3px 20px;" type="text" @click="dialogVisible=true">上传代码
				</el-button>
			</div>
			<div>
				<el-row>
					<el-col :span="12">
						<el-form ref="form" :model="form" label-width="120px">
							<el-form-item label="小程序名称">
								<el-input v-model="form.name"></el-input>
							</el-form-item>
							<el-form-item label="APPID">
								<el-input v-model="form.appid"></el-input>
							</el-form-item>
							<el-form-item label="密钥">
								<el-input v-model="form.secret"></el-input>
							</el-form-item>
							<el-form-item label="上传密钥">
								<el-input type="textarea" v-model="form.upload_key" :autosize="{ minRows: 2, maxRows: 20 }"></el-input>
								<div>注：自动上传代码请关闭白名单</div>
							</el-form-item>
						</el-form>
					</el-col>
				</el-row>
			</div>
		</el-card>
		<el-dialog title="提示" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
			<el-form ref="form" :model="form" label-width="120px">
				<el-form-item label="版本号">
					<el-input v-model="form.version"></el-input>
				</el-form-item>
				<el-form-item label="描述信息">
					<el-input v-model="form.desc"></el-input>
				</el-form-item>
			</el-form>
			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogVisible = false">取 消</el-button>
				<el-button type="primary" @click="upload" v-loading="is_loading">确 定</el-button>
			</span>
		</el-dialog>
	</div>
</template>

<script>
	export default {
		name: 'platform-mpwx',
		data() {
			return {
				dialogVisible: false,
				is_loading: false,
				is_can_upload: false,
				form: {
					name: '',
					appid: '',
					secret: '',
					upload_key: '',
					version: '',
					desc: '首次提交',
				}
			}
		},
		created: function() {

			this.getSetting()

		},
		methods: {
			handleClose(e) {
				this.dialogVisible = false;
			},
			getSetting() {
				this.is_loading = true
				this.$request({
					url: '/mall/platform/mpwx'
				}).then(res => {
					this.is_loading = false
					if (res.code == 0) {
						this.is_can_upload = true;
						this.form = res.data.mpwx
					}
				}).catch(e => {
					this.is_loading = false
				})
			},
			upload() {
				if (this.is_loading) {
					this.$message.warning('请勿频繁点击')
					return;
				}
				this.is_loading = true;
				this.$request({
					url: '/mall/platform/mpwx-upload',
					data: this.form,
					method: 'post'
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.dialogVisible=false;
						this.$message.success(res.msg)
					}
				}).catch(e => {
					this.is_loading = false;
					 
				});
			},
			submit() {
				this.$request({
					url: '/mall/platform/mpwx',
					data: this.form,
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						this.$message.success(res.msg)
					}
				})
			}
		}
	}
</script>

<style>
</style>
