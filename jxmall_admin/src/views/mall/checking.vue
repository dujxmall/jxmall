<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>版本审核</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
      </div>

      <el-form ref="form" label-width="160px">
        <el-form-item label="微信小程序">
          <el-input v-model="form.mpwx"></el-input>
        </el-form-item>
        <el-form-item label="ios">
          <el-input v-model="form.ios"></el-input>
        </el-form-item>
        <el-form-item label="android">
          <el-input v-model="form.android"></el-input>
        </el-form-item>
        <el-form-item label="接口根地址">
          <el-input v-model="form.api_url"></el-input>
        </el-form-item>
        <el-form-item label="是否启用">
          <el-radio-group v-model="form.is_enabled">
            <el-radio label="0">否</el-radio>
            <el-radio label="1">是</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
    </el-card>

  </div>

</template>

<script>
  export default {
    name:'mall-checking',
    data() {

      return {
        form: {
          ios: '',
          android: '',
          mpwx: '',
          api_url: '',
          is_enabled: 0
        },
      }
    },
    created() {
      this.getSetting();
    },
    methods: {

      save() {
        this.$request({
          url: '/mall/mall/checking',
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
          url: '/mall/mall/checking',
        }).then(res => {
          if (res.code == 0) {
            this.form = Object.assign(this.form, res.data.versions)
          }
        })
      },
    }
  }
</script>

<style>
</style>
