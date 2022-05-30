<template>
	<div class="app-container">
		<el-card class="box-card" v-loading="is_loading">
			<div class="el-card__header">
				<slot name="header">站点设置</slot>
				<el-button style="float: right; padding: 3px 0" type="text" @click="submit">保存</el-button>
			</div>
			<div style="padding-top: 20px;">
				<el-row>
					<el-col :span="12">
						<el-form ref="form" :model="form" label-width="120px">
							<el-form-item label="当前站点域名">
								<el-input v-model="form.domain" placeholder="当前站点的域名影响退款回调通知,例如:https://www.xxx.com"></el-input>
								<div style="color: #ff4455;">提示：填写规范如果开启https，则为https://www.xxx.com,否则为http://www.xxx.com</div>
							</el-form-item>
							<el-form-item label="网站名称">
								<el-input v-model="form.name"></el-input>
							</el-form-item>
							<el-form-item label="站点标题">
								<el-input v-model="form.title"></el-input>
							</el-form-item>
							<el-form-item label="网站简称">
								<el-input v-model="form.sub_name"></el-input>
							</el-form-item>
							<el-form-item label="关键词">
								<el-input v-model="form.keywords"></el-input>
							</el-form-item>
							<el-form-item label="版权文字">
								<el-input v-model="form.copyright"></el-input>
							</el-form-item>
							<el-form-item label="备案号">
								<el-input v-model="form.record"></el-input>
							</el-form-item>
							<el-form-item label="版权链接">
								<el-input v-model="form.copyright_url"></el-input>
							</el-form-item>
							<!-- 	<el-form-item label="注册设置">
                                <el-radio-group v-model="form.is_register">
                                    <el-radio :label="'0'">禁用注册</el-radio>
                                    <el-radio :label="'1'">开启注册</el-radio>
                                </el-radio-group>
                            </el-form-item>
                            <el-form-item label="注册用户试用天数">
                                <el-input type="number" v-model="form.try_day" placeholder="0为不限制">
                                    <template slot="append">天</template>
                                </el-input>
                            </el-form-item> -->

							<el-form-item label="登录页logo">
								<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
									v-if="form.login_logo">
									<img :src="form.login_logo" alt="" style="width: 100%;height: 100%;">
									<span class="flex-x-center flex-y-center pic-del" @click="form.login_logo=''"> <i
											class="el-icon-close"></i></span>
								</div>
								<file-picker :multiple='false' v-model="form.login_logo"
									v-if="!form.login_logo||form.login_logo==''">
									<div flex="main:center cross:center"
										class="add-image-btn flex-x-center flex-y-center">
										<i class="el-icon-upload"></i>
									</div>
								</file-picker>
							</el-form-item>

							<el-form-item label="登录页背景">
								<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
									v-if="form.login_pic">
									<img :src="form.login_pic" alt="" style="width: 100%;height: 100%;">
									<span class="flex-x-center flex-y-center pic-del" @click="deleteLoginPic"> <i
											class="el-icon-close"></i></span>
								</div>
								<file-picker :multiple='false' @selected="loginPic"
									v-if="!form.login_pic||form.login_pic==''">
									<div flex="main:center cross:center"
										class="add-image-btn flex-x-center flex-y-center">
										<i class="el-icon-upload"></i>
									</div>
								</file-picker>
							</el-form-item>

							<el-form-item label="管理页头部">
								<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
									v-if="form.header_pic">
									<img :src="form.header_pic" alt="" style="width: 100%;height: 100%;">
									<span class="flex-x-center flex-y-center pic-del" @click="deleteHeaderPic"> <i
											class="el-icon-close"></i></span>
								</div>
								<file-picker :multiple='false' @selected="headerPic"
									v-if="!form.header_pic||form.header_pic==''">
									<div flex="main:center cross:center"
										class="add-image-btn flex-x-center flex-y-center">
										<i class="el-icon-upload"></i>
									</div>
								</file-picker>
							</el-form-item>

						</el-form>
					</el-col>
				</el-row>

			</div>

		</el-card>

	</div>

</template>
<script>
	import store from "@/store";

	export default {
		name: 'system-setting-site',
		data() {
			return {
				is_loading: false,
				form: {
					domain:'',
					title:'',
					auth_key: '',
					is_register: 0,
					name: '',
					record: '',
					sub_name: '',
					keywords: '',
					logo: '',
					login_logo: '',
					copyright: '',
					copyright_url: '',
					header_pic: '', //管理页头部图片
					login_pic: '', //登录页背景图
				}
			}
		},
		created() {
			this.getSetting();
		},

		methods: {
			getSetting() {
				this.$request({
					url: '/admin/site/setting',

				}).then(res => {
					this.form = Object.assign(this.form, res.data.setting)

				})
			},
			logoPic(e) {
				if (e.length > 0) {
					this.form.logo = e[0].url;
				}
			},
			loginPic(e) {
				if (e.length > 0) {
					this.form.login_pic = e[0].url;
				}
			},
			deleteLogoPic() {
				this.form.logo = '';
			},
			headerPic(e) {
				if (e.length > 0) {
					this.form.header_pic = e[0].url;
				}
			},
			deleteHeaderPic() {
				this.form.header_pic = '';
			},
			deleteLoginPic() {
				this.form.login_pic = '';
			},
			submit() {
				this.loading = true;

				this.$request({
					url: '/admin/site/setting',
					data: this.form,
					method: 'post'
				}).then(res => {
					this.loading = false
					if (res.code == 0) {
						store.commit('SET_SETTING', {
							copyright: this.form.copyright,
							copyright_url: this.form.copyright_url,
							header_pic: this.form.header_pic,
							is_register: this.form.is_register,
							login_pic: this.form.login_pic,
							logo: this.form.logo,
							name: this.form.name,
							record: this.form.record,
							sub_name: this.form.sub_name,
							try_day: this.form.try_day,
							title:this.form.title
						});
						this.$cache.local.set(
							"site-setting", JSON.stringify({
								copyright: this.form.copyright,
								copyright_url: this.form.copyright_url,
								header_pic: this.form.header_pic,
								is_register: this.form.is_register,
								login_pic: this.form.login_pic,
								logo: this.form.logo,
								name: this.form.name,
								record: this.form.record,
								sub_name: this.form.sub_name,
								try_day: this.form.try_day,
								title:this.form.title
							})
						);

						this.$message.success('保存成功');
					}
				})


			},
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

	.user-info {
		height: 210px;
		padding: 40px;
		position: relative;

	}

	.info-bg {
		position: absolute;
		right: 40px;
		top: 40px;
		height: 170px;
		width: 170px;
	}

	.user-text {
		padding: 40px 20px 20px;
		font-size: 24px;
	}

	.user-label {
		font-size: 16px;
		color: #999999;
		margin-bottom: 5px;
	}
</style>
