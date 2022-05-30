<template>
	<div class="app-container">
    <el-card class="box-card" v-loading='is_loading'>
      <div slot="header" class="clearfix">
        <span>参数设置</span>

        <el-button style="float: right; padding: 3px 20px;" type="text" @click="submit">保存</el-button>
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

            </el-form>
          </el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>

<script>
	export default {
    name:'platform-wxapp',
		data() {
			return {
				is_loading: false,
				form: {
					name: '',
					appid: '',
					secret: '',

				}
			}
		},
		created: function() {

			this.getSetting();

		},
		methods: {


			menu() {
				this.$router.push({
					path: "/wechat/menu"
				})

			},
			getSetting() {
				this.is_loading = true;
				this.$request({
					url: '/mall/platform/wxapp'
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.form = res.data.wxap;
					}
				}).catch(e => {
					this.is_loading = false;
				})
			},
			submit() {
				this.$request({
					url: '/mall/platform/wxapp',
					data: this.form,
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						this.$message.success(res.msg);
					}
				})
			},
		}
	}
</script>

<style>
</style>
