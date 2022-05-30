<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>提现设置</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
      </div>
      <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="佣金提现" name="price" v-loading="is_loading">
          <el-row>
            <el-col :span="12">
              <el-form ref="form" :model="form" label-width="150px">
                <el-form-item label="开启佣金提现">
                  <el-radio-group v-model="form.price.is_cash">
                    <el-radio :label="'0'">关闭</el-radio>
                    <el-radio :label="'1'">开启</el-radio>
                  </el-radio-group>
                </el-form-item>
                <el-form-item label="单笔最低金额">
                  <el-input v-model="form.price.min_price"></el-input>
                </el-form-item>
                <el-form-item label="单日最多金额">
                  <el-input v-model="form.price.max_price"></el-input>
                </el-form-item>

                <el-form-item label="手续费">
                  <el-input v-model="form.price.service_price">
                    <template slot="append">%</template>
                  </el-input>
                </el-form-item>
                <el-form-item label="提现到余额开启审核">
                  <el-radio-group v-model="form.price.is_check_balance">
                    <el-radio :label="'0'">否</el-radio>
                    <el-radio :label="'1'">是</el-radio>
                  </el-radio-group>
                </el-form-item>
                <el-form-item label="提现方式">
                  <el-checkbox-group v-model="form.price.cash_type">
                    <el-checkbox label="wechat">微信零钱</el-checkbox>
                    <el-checkbox label="alipay">支付宝</el-checkbox>
                    <el-checkbox label="bank">银行卡</el-checkbox>
                    <el-checkbox label="balance">提现到余额</el-checkbox>
                  </el-checkbox-group>
                </el-form-item>

              </el-form>
            </el-col>
          </el-row>
        </el-tab-pane>
        <el-tab-pane label="余额提现" name="balance" v-loading="is_loading">
          <el-row>
            <el-col :span="12">
              <el-form ref="form" :model="form" label-width="150px">
                <el-form-item label="开启余额提现">
                  <el-radio-group v-model="form.balance.is_cash">
                    <el-radio :label="'0'">关闭</el-radio>
                    <el-radio :label="'1'">开启</el-radio>
                  </el-radio-group>
                </el-form-item>


                <el-form-item label="单笔最低金额">
                  <el-input v-model="form.balance.min_price"></el-input>
                </el-form-item>
                <el-form-item label="单日最多金额">
                  <el-input v-model="form.balance.max_price"></el-input>
                </el-form-item>

                <el-form-item label="手续费">
                  <el-input v-model="form.balance.service_price">
                    <template slot="append">%</template>
                  </el-input>
                </el-form-item>

                <el-form-item label="提现方式">
                  <el-checkbox-group v-model="form.balance.cash_type">
                    <el-checkbox label="wechat">微信零钱</el-checkbox>
                    <el-checkbox label="alipay">支付宝</el-checkbox>
                    <el-checkbox label="bank">银行卡</el-checkbox>
                  </el-checkbox-group>
                </el-form-item>
              </el-form>
            </el-col>
          </el-row>
        </el-tab-pane>
        <el-tab-pane label="其他设置" name="other" v-loading="is_loading">
          <el-row>
            <el-col :span="12">
              <el-form ref="form" :model="form" label-width="150px">
                <el-form-item label="打款到银行卡方式">
                  <el-radio-group v-model="form.other.bank_type">
                    <el-radio label="manual">手动打款</el-radio>
                    <el-radio label="auto">自动打款</el-radio>
                  </el-radio-group>
                  <div>自动打款注意事项，若未配置汇聚支付使用微信商户打款到银行卡，需要连续开发人员生成打款证书，汇聚支付请检查相关配置</div>
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

  data() {
    return {
      activeName: 'price',
      is_loading: false,
      form: {
        price: {
          is_cash: '0',
          cash_type: [],
          min_price: '1',
          max_price: '',
          service_price: '',
          is_check_balance: 0,
        },
        balance: {
          is_cash: '0',
          cash_type: [],
          min_price: '1',
          max_price: '',
          service_price: ''

        },
        other: {
          bank_type: 'manual'
        }
      }
    }
  },
  created() {
    this.getSetting();
  },

  methods: {
    handleClick(e) { },
    save() {
      this.is_loading = true;
      this.$request({
        url: "/mall/finance/cash-setting",
        data: this.form,
        method: 'post'
      }).then(res => {
        this.is_loading = false;
        if (res.code == 0) {
          this.$message.success(res.msg)
        }
      })

    },
    getSetting() {
      this.is_loading = true;
      this.$request({
        url: "/mall/finance/cash-setting",
      }).then(res => {
        this.is_loading = false;
        if (res.code == 0) {
          this.form = res.data.setting
          //this.$message.success(res.msg)
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
