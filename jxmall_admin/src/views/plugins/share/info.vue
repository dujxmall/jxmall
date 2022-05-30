<template>
	<div class="app-container">
    <el-card class="box-card" v-loading="is_loading">
      <div slot="header" class="clearfix">
        <span>分销信息</span>
      </div>
      <div style="padding: 0 20px;" v-if="user">
        <div class="title-bar flex-y-center"><span>基本信息</span></div>
        <div style="padding: 20px;padding-top: 0px;" class="flex-x-between">
          <div>
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
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div>

            <div class="flex-row flex-y-center" style="font-size: 8px;padding: 10px 0;">
              <div style="width: 80px;text-align: end;">推荐人：</div>
              <div class="flex-x-center flex-y-center">
                <el-avatar :src="user.parent_avatar" size="small"></el-avatar>
                <div style="padding: 10px;">{{user.parent_name}}</div>
              </div>
            </div>
            <div class="flex-row flex-y-center" style="font-size: 10px;padding: 10px 0;">
              <div style="width: 80px;text-align: end;">分销等级：</div>
              <div>{{user.share_level_name}}
                <span style="color:#409EFF;" class="edit" @click="levelChange">修改</span>
              </div>
            </div>
            <div class="flex-row" style="font-size: 10px;padding: 10px 0;">
              <div style="width: 80px;text-align: end;">成为时间：</div>
              <div>{{user.share_at}}</div>
            </div>
            <div class="flex-row" style="font-size: 10px;padding: 10px 0;">
              <div style="width: 80px;text-align: end;">升级时间：</div>
              <div>{{user.updated_at}}</div>
            </div>
            <div class="flex-row" style="font-size: 10px;padding: 10px 0;">
              <div style="width: 80px;text-align: end;" class="flex-y-center">自动升级
                <el-tooltip class="item" effect="dark" content="如果强制不自动升级，满足任何条件，此分销商的级别也不会改变" placement="left-start">
                  <i class="el-icon-warning"></i>
                </el-tooltip>：
              </div>
              <div>
                <el-radio-group v-model="user.is_auto" @change="autoUpgrade">
                  <el-radio :label="0">否</el-radio>
                  <el-radio :label="1">是</el-radio>
                </el-radio-group>
              </div>
            </div>
          </div>
          <div>
            <div class="flex-row" style="font-size: 10px;padding: 10px 0;">
              <div style="width: 80px;text-align: end;">全部下线：</div>
              <div> <span style="color:#22A1FF ;">{{user.child_all}} </span> 人（分销商：<span style="color:#22A1FF ;">{{user.child_share_all}}</span>人）</div>
            </div>
            <div class="flex-row" style="font-size: 10px;padding: 10px 0;">
              <div style="width: 80px;text-align: end;">一级：</div>
              <div> <span style="color:#22A1FF ;">{{user.child_first}} </span>人（分销商：<span style="color:#22A1FF ;">{{user.child_share_first}}</span>人）</div>
            </div>
            <div class="flex-row" style="font-size: 10px;padding: 10px 0;">
              <div style="width: 80px;text-align: end;">二级：</div>
              <div> <span style="color:#22A1FF ;">{{user.child_second}} </span>人（分销商：<span style="color:#22A1FF ;">{{user.child_share_second}}</span>人）</div>
            </div>
            <div class="flex-row" style="font-size: 10px;padding: 10px 0;">
              <div style="width: 80px;text-align: end;">三级：</div>
              <div><span style="color:#22A1FF ;">{{user.child_third}} </span>人（分销商：<span style="color:#22A1FF ;">{{user.child_share_third}}</span>
                人）</div>
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
                  <el-button type="text">积分记录</el-button>
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
                  <el-button type="text">余额记录</el-button>
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
        <div class="title-bar flex-y-center"><span>交易信息</span></div>
        <div style="width: 100%;padding-top: 20px;" class="flex-x-between" v-if="user">
          <div style="width: 50%;border: solid #F3F3F3 1px;height: 130px;">
            <div style="width: 100%;text-align: center;height: 100%;padding: 10px;">
              <div class="flex-row">
                <div style="width: 80px;height:80px;border-radius: 50%;background-color: #F0FAFF;" class="flex-x-center flex-y-center">
                  <img style="width: 50px;height: 50px;" src="@/assets/statics/mall/user/order1.png" />
                </div>
                <div style="padding: 20px;text-align: start;">
                  <div style="font-size: 8px;font-size: 15px;font-weight: bold;">分销订单(笔)</div>
                  <div style="font-weight: bold;font-size: 20px;padding: 5px 0;">{{user.share_order}}</div>

                </div>
              </div>
            </div>
          </div>
          <div style="width: 50%;border: solid #F3F3F3 1px;height: 130px;margin-left: 20px;">
            <div style="width: 100%;text-align: center;height: 100%;padding: 10px;">
              <div class="flex-row">
                <div style="width: 80px;height:80px;border-radius: 50%;background-color: #F0FAFF;" class="flex-x-center flex-y-center">
                  <img style="width: 50px;height: 50px;" src="@/assets/statics/mall/user/money1.png" /></div>
                <div style="padding: 20px;text-align: start;">
                  <div style="font-size: 8px;font-size: 15px;font-weight: bold;">累计佣金</div>
                  <div style="font-weight: bold;font-size: 20px;padding: 5px 0;">{{user.total_share_price}}</div>
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
                    <el-radio :label="2">最终金额</el-radio>
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
                    <el-radio :label="2">最终金额</el-radio>
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
    </el-card>

  </div>

</template>

<script>
	export default {
    name: 'plugins-share-info',
		data() {
			return {
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
			autoUpgrade(e) {
				this.$request({
					url: "/plugin/share/mall/share/auto-upgrade",
					data: {
						user_id: this.user.id,
						is_auto: e
					},
					method: 'post'
				}).then(res => {
					
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
			levelChange() {
				this.levelVisible = true;
			},
			levelConfirm() {
				this.$request({
					url: '/plugin/share/mall/share/level-change',
					data: {
						user_id: this.user.id,
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
					url: "/plugin/share/mall/share/info",
					data: {
						user_id: this.id,
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.user = res.data.user
						this.list = res.data.level_list
					}

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
		margin-top: 30px;
	}

	.edit:hover {
		cursor: pointer;
	}
</style>
