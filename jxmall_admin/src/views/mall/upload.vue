<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>参数配置</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
      </div>
      <el-tabs v-model="activeName" @tab-click="handleClick">

        <el-tab-pane label="阿里云" name="oss">
          <el-row>
            <el-col :span="12">

              <el-form ref="form" label-width="160px">
                <el-form-item label="access_key">
                  <el-input v-model="oss.access_key"></el-input>
                </el-form-item>
                <el-form-item label="access_secret">
                  <el-input v-model="oss.access_secret"></el-input>
                </el-form-item>
                <el-form-item label="bucket">
                  <el-input v-model="oss.bucket"></el-input>
                </el-form-item>
                <el-form-item label="节点(end_point)">
                  <el-input v-model="oss.end_point"></el-input>
                </el-form-item>

                <el-form-item label="启用">
                  <el-radio-group v-model="oss.enabled">
                    <el-radio :label="0">否</el-radio>
                    <el-radio :label="1">是</el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-form>
            </el-col>

          </el-row>

        </el-tab-pane>
        <el-tab-pane label="腾讯云" name="cos">
          <el-row>
            <el-col :span="12">
              <el-form ref="form" label-width="160px">
                <el-form-item label="secret_id">
                  <el-input v-model="cos.secret_id"></el-input>
                </el-form-item>
                <el-form-item label="secret_key">
                  <el-input v-model="cos.secret_key"></el-input>
                </el-form-item>
                <el-form-item label="bucket">
                  <el-input v-model="cos.bucket"></el-input>
                </el-form-item>
                <el-form-item label="区域(region)">
                  <el-input v-model="cos.region"></el-input>
                </el-form-item>
                <el-form-item label="启用">
                  <el-radio-group v-model="cos.enabled">
                    <el-radio :label="0">否</el-radio>
                    <el-radio :label="1">是</el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-form>
            </el-col>

          </el-row>

        </el-tab-pane>
        <el-tab-pane label="七牛云" name="qiniu">

          <el-row>
            <el-col :span="12">

              <el-form ref="form" label-width="160px">
                <el-form-item label="access_key">
                  <el-input v-model="qiniu.access_key"></el-input>
                </el-form-item>
                <el-form-item label="access_secret">
                  <el-input v-model="qiniu.access_secret"></el-input>
                </el-form-item>
                <el-form-item label="domain">
                  <el-input v-model="qiniu.domain"></el-input>
                </el-form-item>
                <el-form-item label="bucket">
                  <el-input v-model="qiniu.bucket"></el-input>
                </el-form-item>
                <el-form-item label="启用">
                  <el-radio-group v-model="qiniu.enabled">
                    <el-radio :label="0">否</el-radio>
                    <el-radio :label="1">是</el-radio>
                  </el-radio-group>
                </el-form-item>
              </el-form>
            </el-col>
          </el-row>
        </el-tab-pane>
      </el-tabs>
    </el-card>

  </div>
</template>

<script>
  export default {
    name:'mall-upload',
    data() {

      return {
        oss: {
          access_key: '',
          access_secret: '',
          bucket: '',
          end_point: '',
          enabled: 0
        },
        cos: {
          secret_id: '',
          secret_key: '',
          bucket: '',
          region: '',
          enabled: 0
        },
        qiniu: {
          access_key: '',
          access_secret: '',
          bucket: '',
          domain: '',
          enabled: 0
        },
        activeName: 'oss'
      }
    },
    created() {
      this.getSetting();
    },
    methods: {
      handleClick(e) {

      },
      save() {
        this.$request({
          url: '/mall/mall/upload',
          data: {
            qiniu: JSON.stringify(this.qiniu),
            cos: JSON.stringify(this.cos),
            oss: JSON.stringify(this.oss)
          },
          method: 'post'
        }).then(res => {
          if (res.code == 0) {
            this.$message.success(res.msg)
          }
        })


      },
      getSetting() {
        this.$request({
          url: '/mall/mall/upload',
        }).then(res => {
          if (res.code == 0) {
            if (res.data.cos) {
              this.cos = res.data.cos;
            }
            if (res.data.oss) {
              this.oss = res.data.oss;
            }
            if (res.data.qiniu) {
              this.qiniu = res.data.qiniu;
            }
          }
        })


      },
    }
  }
</script>

<style>
</style>
