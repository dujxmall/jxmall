<template>
  <div class="app-container">
    <el-card class="box-card" v-loading="is_loading">
      <div slot="header" class="clearfix">
        <span>推广中心</span>
        <div style="float: right;">
          <el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
        </div>
      </div>
      <div style="padding-left:50px;">
        <div class="flex-row">
          <div class="mobile-preview" style="position: relative;">
            <div style="height: 100%;width: 100%;">
              <el-image :src="form.header.user_bg"> </el-image>
              <div style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;">
                <div style="position: relative;width: 100%;height: 100%;">
                  <div style="width: 100%;margin-top: 120px;padding: 0 30px;">
                    <div class="flex-x-between " style="margin-top: 5px;" @click="chooseBlock('bg')">
                      <div class="flex-y-center">
                        <el-avatar :src="form.header.avatar" icon="el-icon-user-solid" :size="100"></el-avatar>
                        <div style="padding: 0 20px;">
                          <div style="font-weight: 500;color: #FFFFFF;">用户昵称</div>
                          <div style="color: #FACDC8;padding: 10px 0;">
                            {{form.header.parent_label}}: 张三
                          </div>
                        </div>
                      </div>
                      <div>
                        <el-image :src="form.header.qrcode" style="width: 60px;height: 60px;"></el-image>
                      </div>

                    </div>
                    <div class="flex-x-between"
                      style="padding: 0 50px; text-align: center;color: #FFFFFF;padding-top: 50px;">
                      <div>
                        <div style="font-weight: bold;">1</div>
                        <div>{{form.header.balance.name}}</div>
                      </div>
                      <div>
                        <div style="font-weight: bold;">1</div>
                        <div>{{form.header.price.name}}</div>
                      </div>
                      <div>
                        <div style="font-weight: bold;">1</div>
                        <div>{{form.header.integral.name}}</div>
                      </div>
                    </div>
                    <div style="width: 100%;margin-top: 30px;">
                      <div style="width: 98%;margin: 0 auto;">
                        <div
                          style="background-color: #FFFFFF;padding:30px;width: 100%;height:200px;border-radius: 10px;">
                          <div class="flex-x-between">
                            <div style="font-weight: bold;">我的资产</div>
                          </div>
                          <div class="flex-x-between"
                            style="text-align: center;border-top: solid #f0f0f0 1px;margin-top: 20px;padding-top: 10px;"
                            @click="chooseBlock('finance')">
                            <div style="text-align: center;">
                              <div style="height: 64px;width: 64px;text-align: center;"
                                class="flex-x-center flex-y-center">
                                0
                              </div>
                              <div>{{form.finance.total.name}}</div>
                            </div>
                            <div>
                              <div style="height: 64px;width: 64px;" class="flex-x-center flex-y-center">
                                0
                              </div>
                              <div>{{form.finance.price.name}}</div>
                            </div>
                            <div>
                              <div style="height: 64px;width: 64px;" class="flex-x-center flex-y-center">
                                0
                              </div>
                              <div>{{form.finance.cashing.name}}</div>
                            </div>
                            <div>
                              <div style="height: 64px;width: 64px;" class="flex-x-center flex-y-center">
                                0
                              </div>
                              <div>{{form.finance.cash.name}}</div>
                            </div>
                          </div>
                        </div>
                        <div
                          style="background-color: #FFFFFF;padding:30px;width: 100%;min-height:200px;border-radius: 10px;margin-top: 20px;"
                          @click="chooseBlock('menus')">
                          <div class="flex-x-between">
                            <div style="font-weight: bold;">{{form.menus.name}}</div>
                            <div> <i class="el-icon-arrow-right"></i> </div>
                          </div>
                          <div
                            style="text-align: center;border-top: solid #f0f0f0 1px;margin-top: 20px;padding-top: 20px;display: flex;flex-wrap: nowrap;flex-flow: row wrap;justify-content: start;align-items: center; ">
                            <div style="width: 25%;text-align: center;" v-if="form.menus.list.length"
                              v-for="(item,i) in form.menus.list">
                              <div style="height: 64px;width: 100%;text-align: center;"
                                class="flex-x-center flex-y-center">
                                <el-image :src="item.icon" style="width: 64px;height: 64px;"></el-image>
                              </div>
                              <div style="padding: 8px 0;">{{item.name}}</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div style="padding: 0 20px; width: 800px;">


              <el-form label-width="100px" style="width:800px" v-if="currentSelectBlock=='bg'">
                <el-form-item label="头部">
                  <div class="edit-nav-item">
                    <div flex="dir:left box:first cross:center">
                      <div class="flex-row flex-y-center">
                        <div>
                          <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
                            v-if="form.header.user_bg">
                            <img :src="form.header.user_bg" alt="" style="width: 100%;height: 100%;">
                            <span class="flex-x-center flex-y-center pic-del"> <i class="el-icon-close"
                                @click="deleteImg('header_bg')"></i></span>
                          </div>
                          <file-picker v-if="!form.header.user_bg" style="margin-right: 5px;"
                            v-model="form.header.user_bg" width="88" height="88">
                            <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
                              <i class="el-icon-upload"></i>
                            </div>
                          </file-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                </el-form-item>
                <el-form-item label="推广二维码">
                  <div class="edit-nav-item">
                    <div flex="dir:left box:first cross:center">
                      <div class="flex-row flex-y-center">
                        <div>
                          <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
                            v-if="form.header.qrcode">
                            <img :src="form.header.qrcode" alt="" style="width: 100%;height: 100%;">
                            <span class="flex-x-center flex-y-center pic-del"> <i class="el-icon-close"
                                @click="deleteImg('qrcode')"></i></span>
                          </div>
                          <file-picker v-if="!form.header.qrcode" style="margin-right: 5px;"
                            v-model="form.header.qrcode" width="88" height="88">
                            <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
                              <i class="el-icon-upload"></i>
                            </div>
                          </file-picker>
                        </div>
                      </div>
                    </div>
                  </div>
                </el-form-item>
                <el-form-item label="推荐人">
                  <div class="edit-nav-item">
                    <div flex="dir:left box:first cross:center">
                      <div class="flex-row flex-y-center">
                        <el-form-item label="名称">
                          <el-input v-model="form.header.parent_label" placeholder="推荐人" size="small"
                            style="margin-bottom: 5px"></el-input>
                        </el-form-item>
                      </div>
                    </div>
                  </div>
                </el-form-item>
                <el-form-item label="余额">
                  <div class="edit-nav-item">
                    <div flex="dir:left box:first cross:center">
                      <div class="flex-row flex-y-center">
                        <el-form-item label="名称">
                          <el-input v-model="form.header.balance.name" placeholder="名称" size="small"
                            style="margin-bottom: 5px"></el-input>
                        </el-form-item>
                      </div>
                    </div>
                  </div>
                </el-form-item>
                <el-form-item label="佣金">
                  <div class="edit-nav-item">
                    <div flex="dir:left box:first cross:center">
                      <div class="flex-row flex-y-center">
                        <el-form-item label="名称">
                          <el-input v-model="form.header.price.name" placeholder="名称" size="small"
                            style="margin-bottom: 5px"></el-input>
                        </el-form-item>
                      </div>
                    </div>
                  </div>
                </el-form-item>
                <el-form-item label="积分">
                  <div class="edit-nav-item">
                    <div flex="dir:left box:first cross:center">

                      <div class="flex-row flex-y-center">
                        <el-form-item label="名称">
                          <el-input v-model="form.header.integral.name" placeholder="名称" size="small"
                            style="margin-bottom: 5px"></el-input>
                        </el-form-item>
                      </div>
                    </div>
                  </div>
                </el-form-item>
              </el-form>
              <el-form label-width="100px" style="width:800px" v-if="currentSelectBlock=='finance'">
                <el-row>
                  <el-col :span="16">
                    <el-form-item label="标题" label-width="100px">
                      <el-input v-model="form.finance.total.name" placeholder="名称" size="small"
                        style="margin-bottom: 5px"></el-input>
                    </el-form-item>
                  </el-col>
                </el-row>

                <el-form-item label="积分">
                  <div class="edit-nav-item">
                    <div flex="dir:left box:first cross:center">
                      <div class="flex-row flex-y-center">
                        <el-form-item label="名称">
                          <el-input v-model="form.finance.price.name" placeholder="名称" size="small"
                            style="margin-bottom: 5px"></el-input>
                        </el-form-item>
                      </div>
                    </div>
                  </div>
                </el-form-item>
                <el-form-item label="余额">
                  <div class="edit-nav-item">
                    <div flex="dir:left box:first cross:center">
                      <div class="flex-row flex-y-center">
                        <el-form-item label="名称">
                          <el-input v-model="form.finance.cashing.name" placeholder="名称" size="small"
                            style="margin-bottom: 5px"></el-input>
                        </el-form-item>
                      </div>
                    </div>
                  </div>
                </el-form-item>
                <el-form-item label="提现中">
                  <div class="edit-nav-item">
                    <div flex="dir:left box:first cross:center">

                      <div class="flex-row flex-y-center">
                        <el-form-item label="名称">
                          <el-input v-model="form.finance.cashing.name" placeholder="名称" size="small"
                            style="margin-bottom: 5px"></el-input>
                        </el-form-item>
                      </div>
                    </div>
                  </div>
                </el-form-item>
                <el-form-item label="已提现">
                  <div class="edit-nav-item">
                    <div flex="dir:left box:first cross:center">

                      <div class="flex-row flex-y-center">
                        <el-form-item label="名称">
                          <el-input v-model="form.finance.cash.name" placeholder="名称" size="small"
                            style="margin-bottom: 5px"></el-input>
                        </el-form-item>
                      </div>
                    </div>
                  </div>
                </el-form-item>




              </el-form>

              <el-form label-width="100px" style="width:800px" v-if="currentSelectBlock=='menus'">
                <el-row>
                  <el-col :span="16">
                    <el-form-item label="标题" label-width="100px">
                      <el-input v-model="form.menus.name" placeholder="名称" size="small" style="margin-bottom: 5px">
                      </el-input>
                    </el-form-item>
                  </el-col>
                </el-row>
                <el-form-item label="菜单">
                  <div v-for="(item,index) in form.menus.list" class="edit-nav-item">
                    <div class="nav-edit-options">
                      <el-button @click="menuItemDelete(index)" type="primary" icon="el-icon-delete"
                        style="top: -6px;right: -31px;"></el-button>
                    </div>
                    <div flex="dir:left box:first cross:center">
                      <div>
                        <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
                          v-if="item.icon">
                          <img :src="item.icon" alt="" style="width: 100%;height: 100%;">
                          <span class="flex-x-center flex-y-center pic-del" @click="deleteIcon(item)"> <i
                              class="el-icon-close"></i></span>
                        </div>
                        <file-picker v-if="!item.icon" style="margin-right: 5px;" v-model="item.icon" width="88"
                          height="88">
                          <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
                            <i class="el-icon-upload"></i>
                          </div>
                        </file-picker>
                      </div>
                      <div style="margin-top: 10px;">
                        <el-input v-model="item.name" placeholder="名称" size="small" style="margin-bottom: 5px">
                        </el-input>
                        <div>
                          <el-input v-model="item.url" placeholder="点击选择链接" readonly size="small">
                            <el-button size="small" slot="append" @click="selectMenuLinkClick(index)">选择链接</el-button>
                          </el-input>
                        </div>
                      </div>
                    </div>
                  </div>
                  <el-button size="small" @click="addMenuItem">添加图标</el-button>
                </el-form-item>
              </el-form>

          </div>
        </div>
      </div>
      <link-picker @selected="linkSelected" :showLink="showLink">
      </link-picker>
      <link-picker @selected="menuLinkSelected" :showLink="showMenuLink">
      </link-picker>
    </el-card>

  </div>
</template>

<script>
  import draggable from 'vuedraggable'
  import LinkPicker from '@/components/mall/LinkPicker'
  export default {
    name:'mall-manage',
    components: {
      LinkPicker,
      draggable
    },

    data() {
      return {
        showMenuLink: false,
        showLink: false,
        currentSelectBlock: '',
        currentMenuEditNavIndex: 0,
        currentEditNavIndex: 0,
        form: {
          header: {
            parent_label: '',
            user_bg: '',
            avatar: '',
            qrcode: '',
            balance: {
              name: '余额'
            },
            price: {
              name: '佣金'
            },
            integral: {
              name: '积分'
            },
          },
          menus: {
            type: 0,
            name: '常用工具',
            list: [{
              url: '',
              icon: '',
              name: '示例'
            }, ],
          },
          finance: {
            name: '我的资产',
            total: {
              name: '积分',
            },
            price: {
              name: '佣金',

            },
            cashing: {
              name: '佣金',
            },
            cash: {
              name: '成功提现',

            },
          },


        },
        select_index: 0,
        is_loading: false,
      }
    },
    created() {
      this.getSetting();
    },
    methods: {
      chooseBlock(block) {
        this.currentSelectBlock = block;
      },
      getSetting() {
        this.is_loading = true;
        this.$request({
          url: '/mall/mall/user-manage'
        }).then(res => {
          this.is_loading = false;
          if (res.code == 0) {
            Object.assign(this.form, res.data.page_data);
          }
        })
      },
      selectNav(index) {
        this.currentSelectBlock = 'tabbar';

        this.select_index = index
      },
      linkSelected(e) {
        if (!this.form.tabbar.list[this.currentEditNavIndex].name || this.form.tabbar.list[this.currentEditNavIndex]
          .name ==
          '') {
          this.form.tabbar.list[this.currentEditNavIndex].name = e.name
        }
        this.form.tabbar.list[this.currentEditNavIndex].url = e.url
        this.showLink = false;
        this.showMenuLink = false;
        this.$forceUpdate();
      },
      menuLinkSelected(e) {
        if (!this.form.menus.list[this.currentMenuEditNavIndex].name || this.form.menus.list[this
            .currentMenuEditNavIndex].name ==
          '') {
          this.form.menus.list[this.currentMenuEditNavIndex].name = e.name
        }

        this.showLink = false;
        this.showMenuLink = false;
        this.form.menus.list[this.currentMenuEditNavIndex].url = e.url
        this.$forceUpdate();
      },

      deleteActiveIcon(row) {
        row.active_icon = ''
      },
      deleteIcon(row) {
        row.icon = ''
      },
      itemDelete(index) {
        this.form.tabbar.list.splice(index, 1);
      },
      menuItemDelete(index) {
        this.form.menus.list.splice(index, 1);
      },
      addMenuItem() {
        this.form.menus.list.push({
          pic_url: '',
          name: '',
          url: '',
          open_type: '',
        });
      },
      selectLinkClick(index) {
        this.showLink = true;
        this.currentEditNavIndex = index;

      },

      selectMenuLinkClick(index) {
        this.showMenuLink = true;
        this.currentMenuEditNavIndex = index;
      },
      save() {
        this.is_loading = true;
        this.$request({
          url: '/mall/mall/user-manage',
          method: 'post',
          data: {
            page_data: JSON.stringify(this.form)
          }
        }).then(res => {
          this.is_loading = false;
          if (res.code == 0) {
            this.$message.success('保存成功')
          }
        })
      },
      deleteImg(e) {
        if (e == 'header_bg') {
          this.form.header.user_bg = '';
        }
        if (e == 'qrcode') {
          this.form.header.qrcode = '';
        }
      },
      updateNav() {
        let self = this;
        self.multipleSelection.forEach(function(item, index) {
          self.form.tabbar.list.push(item)
        });
        self.dialogTableVisible = false;
      }
    }
  }
</script>

<style lang="scss">
  .mobile-preview {
    width: 750px;
    background-color: #F3F3F3;
    height: 1650px;
    zoom: 50%;
    border-radius: 10px;
    box-sizing: border-box;
    border: 4px solid #F3F3F3;
    overflow: hidden;
    font-size: 24px;
  }

  .tab-item {
    text-align: center;

    display: block;
    width: 100px;

    .icon {
      width: 60px;
      height: 60px;
    }

    .text {

      padding-top: 5px;
    }

  }

  .nav-item {
    text-align: center;
    font-size: 24px;
    padding: 20px 0;
  }

  .nav-item>div {
    height: 25px;
    line-height: 25px;
  }

  .nav-item img {
    display: block;
    width: 88px;
    height: 88px;
    margin: 0 auto 5px auto;
  }

  .edit-nav-item {
    border: 1px solid #e2e2e2;
    line-height: normal;
    padding: 5px;
    margin-bottom: 5px;
    max-width: 450px;
  }

  .nav-icon-upload {
    display: block;
    width: 65px;
    height: 65px;
    line-height: 65px;
    border: 1px dashed #8bc4ff;
    color: #8bc4ff;
    background: #f9f9f9;
    cursor: pointer;
    background-size: 100% 100%;
    font-size: 28px;
    text-align: center;
    vertical-align: middle;
  }

  .nav-edit-options {
    position: relative;
  }

  .nav-edit-options {
    .el-button {
      height: 25px;
      line-height: 25px;
      width: 25px;
      padding: 0;
      text-align: center;
      border: none;
      border-radius: 0;
      position: absolute;
      margin-left: 0;
    }
  }
</style>
