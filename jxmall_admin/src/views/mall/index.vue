<template>

	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>商城设置</span>
				<el-button style="float: right; padding: 3px 0" type="text" @click='save'>保存</el-button>
			</div>

			<el-row>
				<el-col>
					<el-form ref="form" :model="form" label-width="160px">
						<el-tabs v-model="activeName" @tab-click="tabClick">
							<el-tab-pane label="商城设置" name="mall">
								<el-form-item label="商城名称">
									<el-input v-model="form.name"></el-input>
								</el-form-item>
								<el-form-item label="地址">
									<el-input v-model="form.address"></el-input>
								</el-form-item>

								<el-form-item label="经度">
									<el-input v-model="form.lon"></el-input>
								</el-form-item>
								<el-form-item label="纬度">
									<el-input v-model="form.lat"></el-input>
								</el-form-item>
								<el-form-item label="商城logo">
									<single-image-selector :url="form.logo" v-model="form.logo"></single-image-selector>
								</el-form-item>
								<el-form-item label="联系电话">
									<el-input v-model="form.tel">
									</el-input>
								</el-form-item>

								<el-form-item label="商城介绍">
									<el-input type="textarea" v-model="form.detail"></el-input>
								</el-form-item>
								<el-form-item label="关于我们">
									<rich-text @contentChange='detailChange' :content="form.about"></rich-text>
								</el-form-item>

							</el-tab-pane>
							<el-tab-pane label="显示设置" name="show">
								<el-form-item label="在线客服">
									<el-radio-group v-model="form.show_contact">
										<el-radio label="0">隐藏</el-radio>
										<el-radio label="1">显示</el-radio>
									</el-radio-group>
								</el-form-item>
								<el-form-item label="客服图标" v-if="form.show_contact == 1">
									<single-image-selector :url="form.contact_icon" v-model="form.contact_icon">
									</single-image-selector>
								</el-form-item>
								<el-form-item label="电话客服">
									<el-radio-group v-model="form.show_phone">
										<el-radio label="0">隐藏</el-radio>
										<el-radio label="1">显示</el-radio>
									</el-radio-group>
								</el-form-item>
								<el-form-item label="电话图标" v-if="form.show_phone == 1">
									<div flex="main:center cross:center"
										class="add-image-btn flex-x-center flex-y-center" v-if="form.phone_icon">
										<img :src="form.phone_icon" alt="" style="width: 100%;height: 100%;">
										<span class="flex-x-center flex-y-center pic-del" @click="form.phone_icon = ''">
											<i class="el-icon-close"></i></span>
									</div>
									<file-picker v-if="!form.phone_icon" style="margin-right: 5px;"
										v-model="form.phone_icon" width="88" height="88">
										<div flex="main:center cross:center"
											class="add-image-btn flex-x-center flex-y-center">
											<i class="el-icon-upload"></i>
										</div>
									</file-picker>
								</el-form-item>
								<el-form-item label="一键导航">
									<el-radio-group v-model="form.show_nav">
										<el-radio label="0">隐藏</el-radio>
										<el-radio label="1">显示</el-radio>
									</el-radio-group>
								</el-form-item>
								<el-form-item label="导航图标" v-if="form.show_nav == 1">
									<div flex="main:center cross:center"
										class="add-image-btn flex-x-center flex-y-center" v-if="form.nav_icon">
										<img :src="form.nav_icon" alt="" style="width: 100%;height: 100%;">
										<span class="flex-x-center flex-y-center pic-del" @click="form.nav_icon = ''">
											<i class="el-icon-close"></i></span>
									</div>
									<file-picker v-if="!form.nav_icon" style="margin-right: 5px;"
										v-model="form.nav_icon" width="88" height="88">
										<div flex="main:center cross:center"
											class="add-image-btn flex-x-center flex-y-center">
											<i class="el-icon-upload"></i>
										</div>
									</file-picker>
								</el-form-item>

							</el-tab-pane>

							<el-tab-pane label="登录设置" name="login">
								<el-form-item label="是否启用手机号登录">
									<el-radio-group v-model="form.is_login_mobile">
										<el-radio label="0">否</el-radio>
										<el-radio label="1">是</el-radio>
									</el-radio-group>
									<div style="color: #FF0000;">提示：启用手机号登录，请先配置好商城短信设置</div>
								</el-form-item>
								<el-form-item label="需要绑定手机号">
									<el-radio-group v-model="form.is_binding">
										<el-radio label="0">否</el-radio>
										<el-radio label="1">是</el-radio>
									</el-radio-group>
									<div style="color: #FF0000;">提示：如果不启用手机号登录，开启了此步骤，用户在授权登录后回提示用户绑定手机号</div>
								</el-form-item>
								<el-form-item label="开启首页登录">
									<el-radio-group v-model="form.is_home_login">
										<el-radio label="0">否</el-radio>
										<el-radio label="1">是</el-radio>
									</el-radio-group>
									<div style="color: #FF0000;">提示：审核期间，一定不要开启首页登录</div>
								</el-form-item>
								<el-form-item label="移动端登录页图标">
									<div flex="main:center cross:center"
										class="add-image-btn flex-x-center flex-y-center" v-if="form.login_pic">
										<img :src="form.login_pic" alt="" style="width: 100%;height: 100%;">
										<span class="flex-x-center flex-y-center pic-del" @click="form.login_pic = ''">
											<i class="el-icon-close"></i></span>
									</div>
									<file-picker v-if="!form.login_pic" style="margin-right: 5px;"
										v-model="form.login_pic" width="88" height="88">
										<div flex="main:center cross:center"
											class="add-image-btn flex-x-center flex-y-center">
											<i class="el-icon-upload"></i>
										</div>
									</file-picker>
								</el-form-item>
							</el-tab-pane>

							<el-tab-pane label="交易设置" name="order">
								<el-form-item label="订单超时时长">
									<el-input v-model="form.over_time">
										<template slot="append">
											分钟
										</template>
									</el-input>
								</el-form-item>
								<el-form-item label="订单自动收货时长">
									<el-input v-model="form.confirm_time">
										<template slot="append">
											天
										</template>
									</el-input>
								</el-form-item>
								<el-form-item label="订单自动完成时长">
									<el-input v-model="form.complete_time">
										<template slot="append">
											天
										</template>
									</el-input>
								</el-form-item>

								<el-form-item label="不允许退款或取消订单" size="normal">
									<el-radio v-model="form.is_ban_cancel" label="0">否</el-radio>
									<el-radio v-model="form.is_ban_cancel" label="1">是</el-radio>
								</el-form-item>
							</el-tab-pane>

							<el-tab-pane label="退换货设置" name="refund">
								<el-form-item label="收货人">
									<el-input v-model="form.receive_name">

									</el-input>
								</el-form-item>
								<el-form-item label="收货地址">
									<el-input v-model="form.receive_address">

									</el-input>
								</el-form-item>
								<el-form-item label="收货电话">
									<el-input v-model="form.receive_mobile">

									</el-input>
								</el-form-item>
							</el-tab-pane>
							<el-tab-pane label="快递鸟设置" name="express_bird">
								<el-form-item label="用户ID">
									<el-input v-model="form.EBusinessID">

									</el-input>
								</el-form-item>
								<el-form-item label="APP Key">
									<el-input v-model="form.AppKey">
									</el-input>
								</el-form-item>
							</el-tab-pane>
							<el-tab-pane label="商城短信配置" name="sms_setting">
								<el-form-item label="阿里云access_key_id">
									<el-input v-model="form.access_key_id"></el-input>
								</el-form-item>
								<el-form-item label="阿里云access_key_secret">
									<el-input v-model="form.access_key_secret"></el-input>
								</el-form-item>
								<el-form-item label="模板签名">
									<el-input v-model="form.sign_name"></el-input>
								</el-form-item>
								<el-form-item label="验证码模板code">
									<el-input v-model="form.sms_tpl_code"></el-input>
								</el-form-item>
								<el-form-item label="验证码模板变量">
									<el-input v-model="form.sms_tpl"></el-input>
								</el-form-item>
							</el-tab-pane>

							<el-tab-pane label="存储位置" name="file_location">
								<el-form-item label="选择位置">
									<el-radio-group v-model="form.file_location">
										<el-radio :label="'local'">本地</el-radio>
										<el-radio :label="'oss'">阿里云</el-radio>
										<el-radio :label="'cos'">腾讯云</el-radio>
										<el-radio :label="'qiniu'">七牛云</el-radio>
									</el-radio-group>
								</el-form-item>
							</el-tab-pane>
						</el-tabs>

					</el-form>

				</el-col>

			</el-row>
		</el-card>
	</div>
</template>

<script>
	import RichText from '@/components/common/RichText'

	export default {
		name: 'mall-index',
		components: {
			RichText
		},
		data() {
			return {
				activeName: 'mall',
				form: {
					is_ban_cancel:'0',
					address: '',
					lat: '',
					lon: '',
					is_login_mobile: '0',
					access_key_id: '',
					access_key_secret: '',
					sign_name: '',
					about: '',
					nav_icon: '',
					show_nav: 0,
					logo: '',
					detail: '',
					name: '',
					tel: '',
					is_binding: 0,
					is_home_login: 0,
					login_pic: '',
					confirm_time: '',
					over_time: 3,
					complete_time: 0,
					receive_mobile: '',
					receive_address: '',
					receive_name: '',
					EBusinessID: '',
					AppKey: '',
					file_location: '',
					contact_icon: '',
					phone_icon: '',
					show_contact: 0,
					show_phone: 0,
					sms_tpl_code: '',
					sms_tpl: ''
				}
			}
		},
		created() {
			this.getSetting();
		},
		methods: {
			detailChange(e) {
				this.form.about = e.content
			},
			save() {
				this.$request({
					url: '/mall/mall/setting',
					data: this.form,
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						this.$message.success(res.msg)
					}
				})
			},
			getSetting() {
				this.$request({
					url: "/mall/mall/setting",
				}).then(res => {
					if (res.code == 0) {
						this.form = res.data.setting
					}
				})
			},
			tabClick(e) {


			},
			deleteLogo() {
				this.form.logo = "";
			},
			back(row) {
				this.$go.back()
			},
	
	}
}
</script>


<style scoped>
	.el-input {
		width: 750px;
	}

	.el-textarea {
		width: 750px;
	}
</style>
