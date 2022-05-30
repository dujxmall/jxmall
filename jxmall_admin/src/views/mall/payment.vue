<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>支付设置</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
      </div>
      <div v-loading="is_loading">
        <el-form ref="form" :model="form" label-width="160px">
          <el-tabs v-model="activeName">
            <el-tab-pane label="微信支付" name="wechat">
              <el-row>
                <el-col :span="12">
                  <div class="form-body">
                    <el-form-item label="启用微信支付" prop="wechat_status">
                      <el-switch v-model="form.wechat_status" active-value="1" inactive-value="0">
                      </el-switch>
                    </el-form-item>
                    <el-form-item label="支付图标" prop="wechat_icon">
                      <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
                        v-if="form.wechat_icon">
                        <img :src="form.wechat_icon" alt="" style="width: 100%;height: 100%;">
                        <span class="flex-x-center flex-y-center pic-del" @click="deleteWechatIcon">
                          <i class="el-icon-close"></i></span>
                      </div>
                      <file-picker v-if="!form.wechat_icon" style="margin-right: 5px;" v-model="form.wechat_icon"
                        width="88" height="88">
                        <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
                          <i class="el-icon-upload"></i>
                        </div>
                      </file-picker>
                    </el-form-item>
                    <el-form-item label="微信公众号APPID" prop="wechat_app_id">
                      <el-input v-model="form.wechat_app_id"></el-input>
                      <br>
                      <div class="info-color">
                        小程序端支付会自动将该APPID 切换为小程序的APPID
                      </div>
                    </el-form-item>
                    <el-form-item label="微信支付商户号" prop="wechat_mch_id">
                      <el-input v-model.trim="form.wechat_mch_id"></el-input>
                    </el-form-item>
                    <el-form-item label="微信支付Api密钥(V2密钥)" prop="wechat_pay_secret">
                      <el-input @focus="hidden.wechat_pay_secret = false" v-if="hidden.wechat_pay_secret" readonly
                        placeholder="已隐藏内容，点击查看或编辑">
                      </el-input>
                      <el-input v-else v-model.trim="form.wechat_pay_secret"></el-input>
                    </el-form-item>
                    <el-form-item label="微信支付V3密钥" prop="wechat_v3_key">
                      <el-input @focus="hidden.wechat_v3_key = false" v-if="hidden.wechat_v3_key" readonly
                        placeholder="已隐藏内容，点击查看或编辑">
                      </el-input>
                      <el-input v-else v-model.trim="form.wechat_v3_key"></el-input>
                    </el-form-item>
                    <el-form-item label="微信支付证书序列号" prop="wechat_serial_no">
                      <el-input v-model.trim="form.wechat_serial_no"></el-input>
                    </el-form-item>
                    <el-form-item label="微信支付apiclient_cert.pem" prop="wechat_cert_pem">
                      <el-input @focus="hidden.wechat_cert_pem = false" v-if="hidden.wechat_cert_pem" readonly
                        type="textarea" :rows="5" placeholder="已隐藏内容，点击查看或编辑">
                      </el-input>
                      <el-input v-else type="textarea" :rows="5" v-model="form.wechat_cert_pem">
                      </el-input>
                    </el-form-item>
                    <el-form-item label="微信支付apiclient_key.pem" prop="wechat_key_pem">
                      <el-input @focus="hidden.wechat_key_pem = false" v-if="hidden.wechat_key_pem" readonly
                        type="textarea" :rows="5" placeholder="已隐藏内容，点击查看或编辑">
                      </el-input>
                      <el-input v-else type="textarea" :rows="5" v-model="form.wechat_key_pem">
                      </el-input>
                    </el-form-item>
                  </div>
                </el-col>
              </el-row>
            </el-tab-pane>
            <el-tab-pane label="余额支付" name="balance">
              <el-row>
                <el-col :span="12">
                  <el-form-item label="启用余额支付" prop="balance_status">
                    <el-switch v-model="form.balance_status" active-value="1" inactive-value="0">
                    </el-switch>
                  </el-form-item>
                  <el-form-item label="支付图标" prop="balance_icon">
                    <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
                      v-if="form.balance_icon">
                      <img :src="form.balance_icon" alt="" style="width: 100%;height: 100%;">
                      <span class="flex-x-center flex-y-center pic-del" @click="deleteBalanceIcon"> <i
                          class="el-icon-close"></i></span>
                    </div>
                    <file-picker v-if="!form.balance_icon" style="margin-right: 5px;" v-model="form.balance_icon"
                      width="88" height="88">
                      <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
                        <i class="el-icon-upload"></i>
                      </div>
                    </file-picker>
                  </el-form-item>
                </el-col>
              </el-row>
            </el-tab-pane>
            <el-tab-pane label="汇聚支付" name="joinpay">
              <el-row>
                <el-col :span="12">
                  <div class="form-body">
                    <el-form-item label="微信支付方式" prop="wechat_pay_type">
                      <el-radio v-model="form.wechat_pay_type" label="original">原生支付</el-radio>
                      <el-radio v-model="form.wechat_pay_type" label="joinpay">汇聚支付</el-radio>
                    </el-form-item>
                    <el-form-item label="报备商户号" prop="join_trade_merchant_no">
                      <el-input v-model.trim="form.join_trade_merchant_no"></el-input>
                    </el-form-item>
                    <el-form-item label="聚合商户编号" prop="join_merchant_no">
                      <el-input v-model.trim="form.join_merchant_no"></el-input>
                    </el-form-item>
                    <el-form-item label="商户加密密钥" prop="join_app_secret">
                      <el-input v-model.trim="form.join_app_secret"></el-input>
                    </el-form-item>
                    <el-form-item label="商户私钥" prop="join_private_key">
                      <el-input type="textarea" :rows="15" v-model.trim="form.join_private_key"></el-input>
                    </el-form-item>
                    <el-form-item label="注意事项">
                      <div style="color:#ff4455">
                        使用汇聚支付，仅支持支付，打款到个人银行卡，不支持打款到用户零钱，如果需要打款到用户零钱，则需要配置好微信原生支付相关参数
                      </div>
                    </el-form-item>
                  </div>

                </el-col>
              </el-row>
            </el-tab-pane>
          </el-tabs>
        </el-form>
      </div>
    </el-card>

  </div>
</template>

<script>
export default {

  name: 'mall-payment',
  data() {

    return {
      is_loading: false,
      activeName: 'wechat',
      form: {
        wechat_icon: '',
        balance_icon: '',
        balance_status: 0,
        wechat_key_pem: '',
        wechat_app_id: '',
        wechat_cert_pem: '',
        wechat_status: 0,
        wechat_pay_secret: '',
        wechat_mch_id: '',
        wechat_serial_no: '',
        wechat_v3_key: '',
        wechat_pay_type: 'original',
        join_trade_merchant_no: '',
        join_private_key: '',
        join_app_secret: '',
        join_merchant_no: '',
      },
      hidden: {
        wechat_app_id: true,
        wechat_mch_id: false,
        wechat_pay_secret: true,
        wechat_cert_pem: true,
        wechat_key_pem: true,
        wechat_v3_key: true,
      },
    }
  },
  created() {

    this.getSetting();
  },
  methods: {

    deleteWechatIcon() {
      this.form.wechat_icon = "";
    },

    deleteBalanceIcon() {
      this.form.balance_icon = "";
    },
    save() {

      this.is_loading = true;
      this.$request({
        url: '/mall/mall/payment',
        data: this.form,
        method: 'post'
      }).then(res => {
        this.is_loading = false;
        if (res.code == 0) {
          this.$message.success(res.msg)
        }

      }).catch(e => {
        this.is_loading = false;
      })
    },
    getSetting() {

      this.is_loading = true;
      this.$request({
        url: '/mall/mall/payment',

      }).then(res => {
        this.is_loading = false;
        if (res.code == 0) {
          this.form = res.data.setting;

        }
        if (res.code == 1) {
          this.form.wechat_icon = res.data.wechat_icon;
          this.form.balance_icon = res.data.balance_icon;
        }

      }).catch(res => {
        this.is_loading = false;

      })
    },

  }

}
</script>

<style>
</style>
