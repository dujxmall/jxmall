<template>
  <div class="app-container">
    <el-card class="box-card" v-loading="is_loading">
      <div slot="header" class="clearfix">
        <span>编辑电子面单</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
      </div>
      <div>
        <el-form ref="form" :model="form" label-width="160px">
          <el-row>
            <el-col :span="12">
              <el-form-item label="电子面单模板名称">
                <el-input v-model="form.name"></el-input>
              </el-form-item>
              <el-form-item label="快递公司：">
                <el-select style="width: 100%;" v-model="express.name" @change="expressChange" placeholder="请选择">
                  <el-option v-for="(item,index) in list" :key="item.key" :label="item.name" :value="index">
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="发件人姓名：">
                <el-input v-model="form.sender_name"></el-input>
              </el-form-item>
              <el-form-item label="发件手机号：">
                <el-input v-model="form.sender_mobile"></el-input>
              </el-form-item>
              <el-form-item label="发件省份：">
                <el-input v-model="form.sender_province"></el-input>
              </el-form-item>
              <el-form-item label="发件城市：">
                <el-input v-model="form.sender_city"></el-input>
              </el-form-item>
              <el-form-item label="发件区域：">
                <el-input v-model="form.sender_county"></el-input>
              </el-form-item>
              <el-form-item label="发件地址：">
                <el-input v-model="form.sender_address"></el-input>
              </el-form-item>
              <el-form-item label="电子面单密码：">
                <el-input v-model="form.password"></el-input>
              </el-form-item>
              <el-form-item label="月结编码：">
                <el-input v-model="form.month_code"></el-input>
              </el-form-item>
              <el-form-item label="网点名称：">
                <el-input v-model="form.shop_name"></el-input>
              </el-form-item>
              <el-form-item label="网点编码：">
                <el-input v-model="form.shop_code"></el-input>
              </el-form-item>
              <el-form-item label="模板样式：">
                <el-select style="width: 100%;" v-model="style.label" @change="styleChange" placeholder="请选择">
                  <el-option v-for="(item,index) in express_style_list" :key="index" :label="item.label" :value="index">
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="邮费支付方式：">
                <el-radio-group v-model="form.pay_type">
                  <el-radio :label="0">现付</el-radio>
                  <el-radio :label="1">到付</el-radio>
                  <el-radio :label="2">月结</el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="快递员上门揽件：">
                <el-radio-group v-model="form.to_home">
                  <el-radio :label="1">是</el-radio>
                  <el-radio :label="0">否</el-radio>
                </el-radio-group>
              </el-form-item>

              <el-form-item label="设为默认模板：">
                <el-radio-group v-model="form.is_default">
                  <el-radio :label="1">是</el-radio>
                  <el-radio :label="0">否</el-radio>
                </el-radio-group>
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
      </div>
    </el-card>

  </div>
</template>

<script>
  export default {

    data() {
      return {
        is_loading: false,
        express: null,
        list: [],
        style: {
          label: ''
        },
        style_list: [],
        express_style_list: [],
        express: {
          name: ''
        },
        form: {
          id: '',
          name: '',
          express_key: '',
          account: '',
          password: '',
          month_code: '',
          shop_name: '',
          tpl_style: '',
          pay_type: 0,
          to_home: 1,
          is_default: 0,
          express: null,
          sender_name: '',
          sender_mobile: '',
          sender_province: '',
          sender_city: '',
          sender_county: '',
          sender_address: '',

        }
      }
    },
    created() {
      this.getExpress();
      this.getStyle();
      if (this.$route.query.id) {
        this.form.id = this.$route.query.id;
        this.getEorder();
      }

    },
    methods: {
      styleChange(e) {
        this.style = this.express_style_list[e]
      },
      expressChange(e) {
        this.style = {
          label: ''
        };
        this.express = this.list[e]
        this.express_style_list = [];
        for (let key in this.style_list) {
          if (key == this.express.key) {
            this.express_style_list = this.style_list[key];
            break;
          }
        }

      },
      getExpress() {
        this.$request({
          url: "/common/express/express"
        }).then(res => {

          if (res.code == 0) {
            this.list = res.data.list
          }
        })
      },
      getStyle() {
        this.$request({
          url: "/common/express/eorder-style"
        }).then(res => {

          if (res.code == 0) {
            this.style_list = res.data.list
          }
        })
      },
      getEorder() {
        this.is_loading = true;
        this.$request({
          url: "/mall/mall/eorder",
          data: this.form,
        }).then(res => {
          this.is_loading = false;
          if (res.code == 0) {
            this.form = res.data.eorder;
            this.express = this.form.express;
            this.style.label = this.form.tpl_style;
          }
        })

      },

      save() {
        this.is_loading = true;
        if (this.style && this.style.label) {
          this.form.tpl_style = this.style.label;
        }
        if (this.express && this.express.key) {
          this.form.express_key = this.express.key;
          this.form.express = this.express;
        }
        this.$request({
          url: "/mall/mall/eorder",
          data: this.form,
          method: 'post'
        }).then(res => {
          this.is_loading = false;
          if (res.code == 0) {
            this.$message.success(res.msg)
          }
        })
      },

    }
  }
</script>

<style scoped="scoped">
  .list {
    height: 450px;
    max-height: 450px;
  }

  .custom-tree-node {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 14px;
    padding-right: 8px;
  }

  ::-webkit-scrollbar {
    display: none;
    /*隐藏滚动条*/
  }

  .add-area {
    width: 100%;
    text-align: center;
    color: #409EFF;

  }

  .add-area:hover {
    cursor: pointer;
  }

  .page-component__scroll {
    height: 100%;
  }

  .box {
    height: 900px;
    background-color: #000000;
    width: 200px;
    color: #fff;
  }
</style>
