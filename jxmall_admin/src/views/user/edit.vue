<template>
  <div class="app-container">

    <el-card class="box-card" v-loading="is_loading">
      <div slot="header" class="clearfix">
        <span>用户编辑</span>
      </div>
      <div style="padding: 0 20px;" v-if="user">
        <div class="title-bar flex-y-center"><span>基本信息</span></div>
        <div style="padding: 30px;" class="flex-x-between">
          <div class="flex-row">
            <div>
              <el-avatar :src="user.avatar_url" size="large"></el-avatar>
            </div>
            <div style="padding: 0 20px;">
              <div style="color: #000000;font-weight: bold;">{{user.nickname}}</div>
              <div class="flex-row" style="font-size: 8px;padding: 10px 0;">
                <div style="width: 80px;text-align: end;">会员ID：</div>
                <div>{{user.id}}</div>
              </div>
              <div class="flex-row" style="font-size: 10px;padding: 10px 0;">
                <div style="width: 80px;text-align: end;">手机号：</div>
                <div>{{user.mobile}}</div>
              </div>
              <div class="flex-row flex-y-center" style="font-size: 10px;padding: 10px 0;">
                <div style="width: 80px;text-align: end;">会员等级：</div>
                <div>{{user.level_name}}
                  <span style="color:#409EFF;" class="edit" @click="levelChange">修改</span>
                </div>
              </div>
              <div class="flex-row flex-y-center" style="font-size: 10px;padding: 10px 0;">
                <div style="width: 80px;text-align: end;">推荐人：</div>
                <div class="flex-y-center">
                  <el-avatar :src="user.parent_avatar_url"></el-avatar>
                  <div style="padding: 0 10px;">{{user.parent_nickname}}</div>
                  <span style="color:#409EFF;" class="edit" @click="showUserPicker=!showUserPicker">修改</span>
                  <span style="color:#409EFF;margin: 0 20px;" class="edit" @click="bindParent(0)">重置推荐人</span>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div class="flex-row flex-y-center" style="font-size: 8px;padding: 10px 0;">
              <div style="width: 80px;text-align: end;">会员来源：</div>
              <div>
                <el-avatar :src="user.platform_logo" size="small"></el-avatar>

              </div>
            </div>
            <div class="flex-row" style="font-size: 10px;padding: 10px 0;">
              <div style="width: 80px;text-align: end;">注册时间：</div>
              <div>{{user.created_at}}</div>
            </div>
            <div class="flex-row" style="font-size: 10px;padding: 10px 0;">
              <div style="width: 80px;text-align: end;">最近时间：</div>
              <div>{{user.updated_at}}</div>
            </div>
            <div class="flex-row" style="font-size: 10px;padding: 10px 0;">
              <div style="width: 80px;text-align: end;">最近登录IP：</div>
              <div>{{user.login_ip}}</div>
            </div>
          </div>
          <div>
            <div class="flex-row" style="font-size: 10px;padding: 10px 0;">
              <div style="width: 80px;text-align: end;">备注：</div>
              <div>{{user.remarks?user.remarks:'暂无备注信息'}} <span style="color:#409EFF;" class="edit"
                  @click="remarksChange">修改</span></div>
            </div>
          </div>
        </div>

        <div class="title-bar flex-y-center"><span>资产信息</span></div>
        <div style="width: 100%;padding-top: 20px;" class="flex-x-between">
          <div style="width: 33.333%;padding-right: 30px;border: solid #F3F3F3 1px;height: 130px;">
            <div style="width: 100%;text-align: center;height: 100%;" class="flex-y-center flex-x-center">
              <div>
                <div style="font-size: 8px;">积分</div>
                <div style="font-weight: bold;font-size: 20px;padding: 5px 0;">{{user.integral}}</div>
                <div>
                  <el-button type="text" @click="showDialog('integral')">充值</el-button>
                  <el-button type="text" @click="integralLog">积分记录</el-button>
                </div>
              </div>
            </div>
          </div>
          <div style="width: 33.333%;margin:0 30px;border: solid #F3F3F3 1px;height: 130px;">
            <div style="width: 100%;text-align: center;height: 100%;" class="flex-y-center flex-x-center">
              <div>
                <div style="font-size: 8px;">余额</div>
                <div style="font-weight: bold;font-size: 20px;padding: 5px 0;">{{user.money}}</div>
                <div>
                  <el-button type="text" @click="showDialog('balance')">充值</el-button>
                  <el-button type="text" @click="balanceLog">余额记录</el-button>
                </div>
              </div>
            </div>
          </div>
          <div style="width: 33.333%;padding-right: 30px;border: solid #F3F3F3 1px;height: 130px;">
            <div style="width: 100%;text-align: center;height: 100%;" class="flex-y-center flex-x-center">
              <div>
                <div style="font-size: 8px;">佣金</div>
                <div style="font-weight: bold;font-size: 20px;padding: 5px 0;">{{user.price}}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="title-bar flex-y-center" style="margin-top:20px"><span>交易信息</span></div>
        <div style="width: 100%;padding-top: 20px;" class="flex-x-between" v-if="user">
          <div style="width: 50%;border: solid #F3F3F3 1px;height: 130px;">
            <div style="width: 100%;text-align: center;height: 100%;padding: 10px;">
              <div class="flex-row">
                <div style="width: 80px;height:80px;border-radius: 50%;background-color: #F0FAFF;"
                  class="flex-x-center flex-y-center">
                  <img style="width: 50px;height: 50px;" src="@/assets/statics/mall/user/order1.png" />
                </div>
                <div style="padding: 20px;text-align: start;">
                  <div style="font-size: 8px;font-size: 15px;font-weight: bold;">累计成交订单数(笔)</div>
                  <div style="font-weight: bold;font-size: 20px;padding: 5px 0;">
                    {{user.success_order_count}}
                  </div>

                </div>
              </div>
            </div>
          </div>
          <div style="width: 50%;border: solid #F3F3F3 1px;height: 130px;margin-left: 20px;">
            <div style="width: 100%;text-align: center;height: 100%;padding: 10px;">
              <div class="flex-row">
                <div style="width: 80px;height:80px;border-radius: 50%;background-color: #F0FAFF;"
                  class="flex-x-center flex-y-center">
                  <img style="width: 50px;height: 50px;" src="@/assets/statics/mall/user/money1.png" />
                </div>
                <div style="padding: 20px;text-align: start;">
                  <div style="font-size: 8px;font-size: 15px;font-weight: bold;">累计成交金额</div>
                  <div style="font-weight: bold;font-size: 20px;padding: 5px 0;">
                    {{user.success_order_price}}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div style="width: 100%;padding-top: 20px;" class="flex-x-between">
          <div style="width: 50%;border: solid #F3F3F3 1px;height: 130px;">
            <div style="width: 100%;text-align: center;height: 100%;padding: 10px;">
              <div class="flex-row">
                <div style="width: 80px;height:80px;border-radius: 50%;background-color: #FFF1F2;"
                  class="flex-x-center flex-y-center">
                  <img style="width: 50px;height: 50px;" src="@/assets/statics/mall/user/order2.png" />
                </div>
                <div style="padding: 20px;text-align: start;">
                  <div style="font-size: 8px;font-size: 15px;font-weight: bold;">累计退款订单数(笔)</div>
                  <div style="font-weight: bold;font-size: 20px;padding: 5px 0;">
                    {{user.success_refund_count}}
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div style="width: 50%;border: solid #F3F3F3 1px;height: 130px;margin-left: 20px;">
            <div style="width: 100%;text-align: center;height: 100%;padding: 10px;">
              <div class="flex-row">
                <div style="width: 80px;height:80px;border-radius: 50%;background-color: #FFF1F2;"
                  class="flex-x-center flex-y-center">
                  <img style="width: 50px;height: 50px;" src="@/assets/statics/mall/user/money2.png" />
                </div>
                <div style="padding: 20px;text-align: start;">
                  <div style="font-size: 8px;font-size: 15px;font-weight: bold;">累计退款金额</div>
                  <div style="font-weight: bold;font-size: 20px;padding: 5px 0;">
                    {{user.success_refund_price}}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <el-dialog title="充值" :visible.sync="dialogVisible" width="30%">
        <div v-if="type=='balance'">
          <el-row>
            <el-col :span="18">
              <el-form ref="form" :model="form" label-width="80px">
                <el-form-item label="当前余额">
                  <span style="font-size: 20px;font-weight: bold;color: #FF9900;">{{user.money}}</span>
                </el-form-item>
                <el-form-item label="数目">
                  <el-input v-model="form.num"></el-input>
                </el-form-item>
                <el-form-item label="变化">
                  <el-radio-group v-model="form.way">
                    <el-radio :label="0">增加</el-radio>
                    <el-radio :label="1">减少</el-radio>

                  </el-radio-group>
                </el-form-item>
                <el-form-item label="变化">
                  <el-input type="textarea" v-model="form.remarks"></el-input>
                </el-form-item>

              </el-form>
            </el-col>
          </el-row>
        </div>
        <div v-if="type=='integral'">
          <el-row>
            <el-col :span="18">
              <el-form ref="form" :model="form" label-width="80px">
                <el-form-item label="当前积分">
                  <span style="font-size: 20px;font-weight: bold;color: #FF9900;">{{user.integral}}</span>
                </el-form-item>
                <el-form-item label="数目">
                  <el-input v-model="form.num"></el-input>
                </el-form-item>
                <el-form-item label="变化">
                  <el-radio-group v-model="form.way">
                    <el-radio :label="0">增加</el-radio>
                    <el-radio :label="1">减少</el-radio>

                  </el-radio-group>
                </el-form-item>
                <el-form-item label="变化">
                  <el-input type="textarea" v-model="form.remarks"></el-input>
                </el-form-item>
              </el-form>
            </el-col>
          </el-row>
        </div>
        <span slot="footer" class="dialog-footer">
          <el-button @click="dialogVisible = false">取 消</el-button>
          <el-button type="primary" @click="confirm">确 定</el-button>
        </span>
      </el-dialog>
      <el-dialog title="修改等级" :visible.sync="levelVisible" width="30%">

        <el-row>
          <el-col :span="18">
            <el-form ref="form" :model="form" label-width="130px">

              <el-form-item label="选择会员等级">
                <el-select style="width: 100%;" v-model="form.level" placeholder="请选择等级">
                  <el-option v-for="item in list" :key="item.name" :label="item.name" :value="item.level">
                  </el-option>
                </el-select>
              </el-form-item>
            </el-form>
          </el-col>
        </el-row>

        <span slot="footer" class="dialog-footer">
          <el-button @click="levelVisible = false">取 消</el-button>
          <el-button type="primary" @click="levelConfirm">确 定</el-button>
        </span>
      </el-dialog>
      <el-dialog title="修改备注" :visible.sync="remarksVisible" width="30%">
        <el-row>
          <el-col :span="18">
            <el-form ref="form" :model="form" label-width="130px">
              <el-form-item label="输入备注">
                <el-input type="textarea" v-model="user&&user.remarks"></el-input>
              </el-form-item>
            </el-form>
          </el-col>
        </el-row>

        <span slot="footer" class="dialog-footer">
          <el-button @click="remarksVisible = false">取 消</el-button>
          <el-button type="primary" @click="remarksConfirm">确 定</el-button>
        </span>
      </el-dialog>
      <user-picker :show="showUserPicker" @selected="selectUser" @close="closeShowUserPicker"></user-picker>
    </el-card>

  </div>

</template>

<script>
  import UserPicker from "@/components/mall/UserPicker.vue"
  export default {
    components: {
      UserPicker
    },
    data() {
      return {
        showUserPicker: false,
        dialogVisible: false,
        type: '',
        id: '',
        user: null,
        is_loading: false,
        levelVisible: false,
        remarksVisible: false,
        list: [],
        form: {
          way: 0,
          num: '',
          type: 0,
          remarks: '',
          user_id: '',
          level: '',
        }
      }
    },
    created() {
      this.id = this.$route.query.id;
      this.form.user_id = this.id;
      this.getUser();
    },
    methods: {
      integralLog() {
        this.$router.push({
          path: '/finance/integral-log',
          query: {
            user_id: this.id
          }
        })
      },
      balanceLog() {
        this.$router.push({
          path: '/finance/balance-log',
          query: {
            user_id: this.id
          }
        })
      },
      closeShowUserPicker() {

        this.showUserPicker = false;

      },
      selectUser(e) {

        this.closeShowUserPicker();
        if (e.user_id) {
          if (this.id == e.user_id) {
            this.$message.warning('推荐人不能选择自己！');
            return;
          }
          this.bindParent(e.user_id);
        }
      },
      bindParent(parent_id) {

        this.$request({
          url: "/mall/user/parent-change",
          data: {
            parent_id: parent_id,
            user_id: this.id
          },
          method: 'post'
        }).then(res => {
          if (res.code == 0) {
            this.$message.success(res.msg);
            this.getUser();
          }
        })
      },

      remarksConfirm() {
        this.$request({
          url: '/mall/user/remarks-change',
          data: {
            user_id: this.id,
            remarks: this.user.remarks
          },
          method: 'post'
        }).then(res => {
          if (res.code == 0) {
            this.remarksVisible = false;
            this.$message.success(res.msg)
            this.getUser()
          }
        })
      },
      remarksChange() {
        this.remarksVisible = true;
      },
      levelChange() {
        this.levelVisible = true;
      },
      levelConfirm() {
        this.$request({
          url: '/mall/user/level-change',
          data: {
            user_id: this.id,
            level: this.form.level
          },
          method: 'post'
        }).then(res => {
          if (res.code == 0) {
            this.levelVisible = false;
            this.$message.success(res.msg)
            this.getUser()
          }
        })
      },
      showDialog(type) {
        this.type = type;
        this.dialogVisible = true;
        if (this.type == 'balance') {
          this.form.type = 0;
        }
        if (this.type == 'integral') {
          this.form.type = 1;
        }
      },
      getUser() {
        this.is_loading = true;
        this.$request({
          url: '/mall/user/info',
          data: {
            user_id: this.id,
          }
        }).then(res => {
          this.is_loading = false;
          if (res.code == 0) {
            this.user = res.data.user
            this.list = res.data.level_list
          }
          //
        })
      },
      confirm() {
        if (!this.form.num) {
          this.$message.warning('请输入金额');
          return;
        }
        this.$request({
          url: '/mall/user/charge',
          data: this.form,
          method: 'post'
        }).then(res => {
          this.dialogVisible = false;
          if (res.code == 0) {
            this.$message.success(res.msg)
            this.getUser();
          }
        })
      },
    }

  }
</script>

<style>
  .title-bar {
    width: 100%;
    height: 40px;
    background-color: #F4F6F8;
    padding: 0 20px;
    font-weight: bold;
    margin-bottom: 30px;
  }

  .edit:hover {
    cursor: pointer;
  }
</style>
