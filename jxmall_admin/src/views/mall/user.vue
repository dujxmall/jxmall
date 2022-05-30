<template>
  <div class="app-container">
    <el-card class="box-card" v-loading="is_loading">
      <div slot="header" class="clearfix">
        <span>用户中心</span>
        <div style="float: right;">
          <el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
        </div>
      </div>
      <div style="padding-left:50px;">
        <div class="flex-row">
          <div class="mobile-preview" style="position: relative;">
            <div style="height: 100%;width: 100%;">
              <el-image :src="form.header.user_bg"></el-image>
              <div
                style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;overflow-y: scroll;padding-bottom: 150px;">
                <div style="position: relative;width: 100%;height: 100%;">
                  <div style="width: 100%;margin-top: 120px;padding: 0 30px;padding-bottom: 150px;">
                    <div class="flex-row flex-y-center" style="margin-top: 5px;"
                         @click="chooseBlock('bg')">
                      <el-avatar icon="el-icon-user-solid" :size="100"></el-avatar>
                      <div style="padding: 0 20px;">
                        <div style="font-weight: 500;color: #FFFFFF;">用户昵称</div>
                        <div style="color: #FACDC8;padding: 10px 0;">
                          个性签名
                        </div>
                      </div>
                    </div>
                    <div class="flex-x-between"
                         style="padding: 0 50px; text-align: center;color: #FFFFFF;padding-top: 50px;">
                      <div v-if="form.header.goods.is_show==1">
                        <div style="font-weight: bold;">1</div>
                        <div>{{form.header.goods.name}}</div>
                      </div>
                      <div v-if="form.header.order.is_show==1">
                        <div style="font-weight: bold;">1</div>
                        <div>{{form.header.order.name}}</div>
                      </div>
                      <div v-if="form.header.footmark.is_show==1">
                        <div style="font-weight: bold;">1</div>
                        <div>{{form.header.footmark.name}}</div>
                      </div>
                    </div>
                    <div style="width: 100%;margin-top: 30px;">
                      <div style="width: 98%;margin: 0 auto;">
                        <div
                          style="background-color: #FFFFFF;padding:30px;width: 100%;height:200px;border-radius: 10px;">
                          <div class="flex-x-between">
                            <div style="font-weight: bold;">我的订单</div>
                            <div>查看全部订单 <i class="el-icon-arrow-right"></i></div>
                          </div>
                          <div class="flex-x-between"
                               style="text-align: center;border-top: solid #f0f0f0 1px;margin-top: 20px;padding-top: 10px;"
                               @click="chooseBlock('order')">
                            <div v-if="form.order.status_0.is_show==1">
                              <div>
                                <el-image :src="form.order.status_0.icon"
                                          style="width: 64px;height: 60px;"></el-image>
                              </div>
                              <div>{{form.order.status_0.name}}</div>
                            </div>
                            <div v-if="form.order.status_1.is_show==1">
                              <div>
                                <el-image :src="form.order.status_1.icon"
                                          style="width: 64px;height: 60px;"></el-image>
                              </div>
                              <div>{{form.order.status_1.name}}</div>
                            </div>
                            <div v-if="form.order.status_3.is_show==1">
                              <div>
                                <el-image :src="form.order.status_2.icon"
                                          style="width: 64px;height: 60px;"></el-image>
                              </div>
                              <div>{{form.order.status_2.name}}</div>
                            </div>
                            <div v-if="form.order.status_3.is_show==1">
                              <div>
                                <el-image :src="form.order.status_3.icon"
                                          style="width: 64px;height: 60px;"></el-image>
                              </div>
                              <div>{{form.order.status_3.name}}</div>
                            </div>
                            <div v-if="form.order.status_4.is_show==1">
                              <div>
                                <el-image :src="form.order.status_4.icon"
                                          style="width: 64px;height: 60px;"></el-image>
                              </div>
                              <div>{{form.order.status_4.name}}</div>
                            </div>
                          </div>
                        </div>
                        <div
                          style="background-color: #FFFFFF;padding:30px;width: 100%;height:200px;border-radius: 10px;margin-top: 20px;"
                          @click="chooseBlock('finance')">
                          <div class="flex-x-between">
                            <div style="font-weight: bold;">{{form.finance.name}}</div>
                          </div>
                          <div class="flex-x-between"
                               style="text-align: center;border-top: solid #f0f0f0 1px;margin-top: 20px;padding-top: 10px;">
                            <div v-if="form.finance.integral.is_show==1">
                              <div style="height: 64px;width: 64px;"
                                   class="flex-x-center flex-y-center">
                                0
                              </div>
                              <div>{{form.finance.integral.name}}</div>
                            </div>
                            <div v-if="form.finance.balance.is_show==1">
                              <div style="height: 64px;width: 64px;"
                                   class="flex-x-center flex-y-center">
                                0
                              </div>
                              <div>{{form.finance.balance.name}}</div>
                            </div>
                            <div v-if="form.finance.price.is_show==1">
                              <div style="height: 64px;width: 64px;"
                                   class="flex-x-center flex-y-center">
                                0
                              </div>
                              <div>{{form.finance.price.name}}</div>
                            </div>
                            <div v-if="form.finance.coupon.is_show==1">
                              <div style="height: 64px;width: 64px;"
                                   class="flex-x-center flex-y-center">
                                0
                              </div>
                              <div>{{form.finance.coupon.name}}</div>
                            </div>
                          </div>
                        </div>

                        <div
                          style="background-color: #FFFFFF;padding:30px;width: 100%;min-height:200px;border-radius: 10px;margin-top: 20px;"
                          @click="chooseBlock('menus')" v-if="form.menus.type==0">
                          <div class="flex-x-between">
                            <div style="font-weight: bold;">{{form.menus.name}}</div>
                            <div><i class="el-icon-arrow-right"></i></div>
                          </div>
                          <div
                            style="text-align: center;border-top: solid #f0f0f0 1px;margin-top: 20px;padding-top: 20px;display: flex;flex-wrap: nowrap;flex-flow: row wrap;justify-content: start;align-items: center; ">
                            <div style="width: 20%;text-align: center;"
                                 v-if="form.menus.list.length"
                                 v-for="(item,i) in form.menus.list">
                              <div style="height: 64px;width: 100%;text-align: center;"
                                   class="flex-x-center flex-y-center">
                                <el-image :src="item.icon"
                                          style="width: 64px;height: 64px;"></el-image>
                              </div>
                              <div style="padding: 8px 0;">{{item.name}}</div>
                            </div>
                          </div>
                        </div>

                        <div
                          style="background-color: #FFFFFF;padding:30px;width: 100%;min-height:200px;border-radius: 10px;margin-top: 20px;"
                          @click="chooseBlock('menus2')" v-if="form.menus2.type==0">
                          <div class="flex-x-between">
                            <div style="font-weight: bold;">{{form.menus2.name}}</div>
                            <div><i class="el-icon-arrow-right"></i></div>
                          </div>
                          <div
                            style="text-align: center;border-top: solid #f0f0f0 1px;margin-top: 20px;padding-top: 20px;display: flex;flex-wrap: nowrap;flex-flow: row wrap;justify-content: start;align-items: center; ">
                            <div style="width: 20%;text-align: center;"
                                 v-if="form.menus2.list.length"
                                 v-for="(item,i) in form.menus2.list">
                              <div style="height: 64px;width: 100%;text-align: center;"
                                   class="flex-x-center flex-y-center">
                                <el-image :src="item.icon"
                                          style="width: 64px;height: 64px;"></el-image>
                              </div>
                              <div style="padding: 8px 0;">{{item.name}}</div>
                            </div>
                          </div>
                        </div>


                        <div
                          style="background-color: #FFFFFF;padding:30px;width: 100%;min-height:200px;border-radius: 10px;margin-top: 20px;"
                          @click="chooseBlock('menus')" v-if="form.menus.type==1">
                          <div class="flex-x-between">
                            <div style="font-weight: bold;">{{form.menus.name}}</div>
                            <div><i class="el-icon-arrow-right"></i></div>

                          </div>
                          <div
                            style="text-align: center;border-top: solid #f0f0f0 1px;margin-top: 20px;padding-top: 20px;justify-content: start;align-items: center; ">
                            <div style="text-align: center;width: 100%;border-bottom: solid #f0f0f0 1px;"
                                 class="flex-x-between" v-if="form.menus.list.length"
                                 v-for="(item,i) in form.menus.list">
                              <div class="flex-y-center">
                                <div style="height: 64px;text-align: center;"
                                     class="flex-x-center flex-y-center">
                                  <el-image :src="item.icon"
                                            style="width: 40px;height: 40px;"></el-image>
                                </div>
                                <div style="padding: 8px 2px;width: 100%;">{{item.name}}
                                </div>
                              </div>
                              <div>
                                <div><i class="el-icon-arrow-right"></i></div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div
                          style="background-color: #FFFFFF;padding:30px;width: 100%;min-height:200px;border-radius: 10px;margin-top: 20px;"
                          @click="chooseBlock('menus2')" v-if="form.menus2.type==1">
                          <div class="flex-x-between">
                            <div style="font-weight: bold;">{{form.menus2.name}}</div>
                            <div><i class="el-icon-arrow-right"></i></div>

                          </div>
                          <div
                            style="text-align: center;border-top: solid #f0f0f0 1px;margin-top: 20px;padding-top: 20px;justify-content: start;align-items: center; ">
                            <div style="text-align: center;width: 100%;border-bottom: solid #f0f0f0 1px;"
                                 class="flex-x-between" v-if="form.menus2.list.length"
                                 v-for="(item,i) in form.menus2.list">
                              <div class="flex-y-center">
                                <div style="height: 64px;text-align: center;"
                                     class="flex-x-center flex-y-center">
                                  <el-image :src="item.icon"
                                            style="width: 40px;height: 40px;"></el-image>
                                </div>
                                <div style="padding: 8px 2px;width: 100%;">{{item.name}}
                                </div>
                              </div>
                              <div>
                                <div><i class="el-icon-arrow-right"></i></div>
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

            <div
              style="position: absolute;bottom: 0;height:120px;width: 100%;background-color: #FFFFFF;border-top: solid #f1f1f1 1px;">
              <div style="width: 100%;padding:10px 20px;">
                <div style="width: 100%;" class="flex-x-between">
                  <div class="tab-item" v-if="form.tabbar.list.length"
                       v-for="(item,i) in form.tabbar.list" @click="selectNav(i)">
                    <el-image class="icon" :src="item.active_icon" v-if="select_index===i">
                    </el-image>
                    <el-image class="icon" :src="item.icon" v-if="select_index!=i"></el-image>
                    <div class="text" :style="{color:form.tabbar.color}" v-if="select_index===i">
                      {{item.name}}
                    </div>
                    <div class="text" style="color: #666666;" v-if="select_index!=i">
                      {{item.name}}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div style="padding: 0 20px;">
            <el-form label-width="100px" style="width:800px" v-if="currentSelectBlock=='bg'">
              <el-form-item label="头部">
                <div class="edit-nav-item">
                  <div flex="dir:left box:first cross:center">
                    <div class="flex-row flex-y-center">
                      <div>
                        <div flex="main:center cross:center"
                             class="add-image-btn flex-x-center flex-y-center"
                             v-if="form.header.user_bg">
                          <img :src="form.header.user_bg" alt=""
                               style="width: 100%;height: 100%;">
                          <span class="flex-x-center flex-y-center pic-del"> <i
                            class="el-icon-close"
                            @click="deleteImg('header_bg')"></i></span>
                        </div>
                        <file-picker v-if="!form.header.user_bg" style="margin-right: 5px;"
                                     v-model="form.header.user_bg" width="88" height="88">
                          <div flex="main:center cross:center"
                               class="add-image-btn flex-x-center flex-y-center">
                            <i class="el-icon-upload"></i>
                          </div>
                        </file-picker>
                      </div>

                    </div>
                  </div>
                </div>

              </el-form-item>
              <el-form-item label="商品收藏">
                <div class="edit-nav-item">
                  <div flex="dir:left box:first cross:center">
                    <div class="flex-row flex-y-center">
                      <el-form-item label="是否显示">
                        <el-radio-group v-model="form.header.goods.is_show">
                          <el-radio :label="0">隐藏</el-radio>
                          <el-radio :label="1">显示</el-radio>
                        </el-radio-group>
                      </el-form-item>
                    </div>
                    <div class="flex-row flex-y-center">
                      <el-form-item label="名称">
                        <el-input v-model="form.header.goods.name" placeholder="名称"
                                  size="small" style="margin-bottom: 5px"></el-input>
                      </el-form-item>
                    </div>
                  </div>
                </div>
              </el-form-item>
              <el-form-item label="成交订单" v-if="form.header.order">
                <div class="edit-nav-item">
                  <div flex="dir:left box:first cross:center">
                    <div class="flex-row flex-y-center">
                      <el-form-item label="是否显示">
                        <el-radio-group v-model="form.header.order.is_show">
                          <el-radio :label="0">隐藏</el-radio>
                          <el-radio :label="1">显示</el-radio>
                        </el-radio-group>
                      </el-form-item>
                    </div>
                    <div class="flex-row flex-y-center">
                      <el-form-item label="名称">
                        <el-input v-model="form.header.order.name" placeholder="名称"
                                  size="small" style="margin-bottom: 5px"></el-input>
                      </el-form-item>
                    </div>
                  </div>
                </div>
              </el-form-item>

              <el-form-item label="足迹">
                <div class="edit-nav-item">
                  <div flex="dir:left box:first cross:center">
                    <div class="flex-row flex-y-center">
                      <el-form-item label="是否显示">
                        <el-radio-group v-model="form.header.footmark.is_show">
                          <el-radio :label="0">隐藏</el-radio>
                          <el-radio :label="1">显示</el-radio>
                        </el-radio-group>
                      </el-form-item>
                    </div>
                    <div class="flex-row flex-y-center">
                      <el-form-item label="名称">
                        <el-input v-model="form.header.footmark.name" placeholder="名称"
                                  size="small" style="margin-bottom: 5px"></el-input>
                      </el-form-item>
                    </div>
                  </div>
                </div>
              </el-form-item>
            </el-form>
            <el-form label-width="100px" style="width:800px" v-if="currentSelectBlock=='order'">
              <el-form-item label="菜单">
                <div class="edit-nav-item">
                  <div flex="dir:left box:first cross:center">
                    <div class="flex-row flex-y-center">
                      <div>
                        <div flex="main:center cross:center"
                             class="add-image-btn flex-x-center flex-y-center"
                             v-if="form.order.status_0.icon">
                          <img :src="form.order.status_0.icon" alt=""
                               style="width: 100%;height: 100%;">
                          <span class="flex-x-center flex-y-center pic-del"
                                @click="form.order.status_0.icon=''"> <i
                            class="el-icon-close"></i></span>
                        </div>
                        <file-picker v-if="!form.order.status_0.icon"
                                     style="margin-right: 5px;" v-model="form.order.status_0.icon"
                                     width="88" height="88">
                          <div flex="main:center cross:center"
                               class="add-image-btn flex-x-center flex-y-center">
                            <i class="el-icon-upload"></i>
                          </div>
                        </file-picker>
                      </div>
                      <div style="margin-top: 10px;">
                        <el-form-item label="是否显示">
                          <el-radio-group v-model="form.order.status_0.is_show">
                            <el-radio :label="0">隐藏</el-radio>
                            <el-radio :label="1">显示</el-radio>
                          </el-radio-group>
                        </el-form-item>
                        <el-input v-model="form.order.status_0.name" placeholder="名称"
                                  size="small" style="margin-bottom: 5px"></el-input>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="edit-nav-item">
                  <div flex="dir:left box:first cross:center">
                    <div class="flex-row flex-y-center">
                      <div>
                        <div flex="main:center cross:center"
                             class="add-image-btn flex-x-center flex-y-center"
                             v-if="form.order.status_1.icon">
                          <img :src="form.order.status_1.icon" alt=""
                               style="width: 100%;height: 100%;">
                          <span class="flex-x-center flex-y-center pic-del"
                                @click="form.order.status_1.icon=''"> <i
                            class="el-icon-close"></i></span>
                        </div>
                        <file-picker v-if="!form.order.status_1.icon"
                                     style="margin-right: 5px;" v-model="form.order.status_1.icon"
                                     width="88" height="88">
                          <div flex="main:center cross:center"
                               class="add-image-btn flex-x-center flex-y-center">
                            <i class="el-icon-upload"></i>
                          </div>
                        </file-picker>
                      </div>
                      <div style="margin-top: 10px;">
                        <el-form-item label="是否显示">
                          <el-radio-group v-model="form.order.status_1.is_show">
                            <el-radio :label="0">隐藏</el-radio>
                            <el-radio :label="1">显示</el-radio>
                          </el-radio-group>
                        </el-form-item>
                        <el-input v-model="form.order.status_1.name" placeholder="名称"
                                  size="small" style="margin-bottom: 5px"></el-input>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="edit-nav-item">

                  <div flex="dir:left box:first cross:center">
                    <div class="flex-row flex-y-center">
                      <div>
                        <div flex="main:center cross:center"
                             class="add-image-btn flex-x-center flex-y-center"
                             v-if="form.order.status_2.icon">
                          <img :src="form.order.status_2.icon" alt=""
                               style="width: 100%;height: 100%;">
                          <span class="flex-x-center flex-y-center pic-del"
                                @click="form.order.status_2.icon=''"> <i
                            class="el-icon-close"></i></span>
                        </div>
                        <file-picker v-if="!form.order.status_2.icon"
                                     style="margin-right: 5px;" v-model="form.order.status_2.icon"
                                     width="88" height="88">
                          <div flex="main:center cross:center"
                               class="add-image-btn flex-x-center flex-y-center">
                            <i class="el-icon-upload"></i>
                          </div>
                        </file-picker>
                      </div>
                      <div style="margin-top: 10px;">
                        <el-form-item label="是否显示">
                          <el-radio-group v-model="form.order.status_2.is_show">
                            <el-radio :label="0">隐藏</el-radio>
                            <el-radio :label="1">显示</el-radio>
                          </el-radio-group>
                        </el-form-item>
                        <el-input v-model="form.order.status_2.name" placeholder="名称"
                                  size="small" style="margin-bottom: 5px"></el-input>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="edit-nav-item">

                  <div flex="dir:left box:first cross:center">
                    <div class="flex-row flex-y-center">
                      <div>
                        <div flex="main:center cross:center"
                             class="add-image-btn flex-x-center flex-y-center"
                             v-if="form.order.status_3.icon">
                          <img :src="form.order.status_3.icon" alt=""
                               style="width: 100%;height: 100%;">
                          <span class="flex-x-center flex-y-center pic-del"
                                @click="form.order.status_3.icon=''"> <i
                            class="el-icon-close"></i></span>
                        </div>
                        <file-picker v-if="!form.order.status_3.icon"
                                     style="margin-right: 5px;" v-model="form.order.status_3.icon"
                                     width="88" height="88">
                          <div flex="main:center cross:center"
                               class="add-image-btn flex-x-center flex-y-center">
                            <i class="el-icon-upload"></i>
                          </div>
                        </file-picker>
                      </div>
                      <div style="margin-top: 10px;">
                        <el-form-item label="是否显示">
                          <el-radio-group v-model="form.order.status_3.is_show">
                            <el-radio :label="0">隐藏</el-radio>
                            <el-radio :label="1">显示</el-radio>
                          </el-radio-group>
                        </el-form-item>
                        <el-input v-model="form.order.status_3.name" placeholder="名称"
                                  size="small" style="margin-bottom: 5px"></el-input>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="edit-nav-item">

                  <div flex="dir:left box:first cross:center">
                    <div class="flex-row flex-y-center">
                      <div>
                        <div flex="main:center cross:center"
                             class="add-image-btn flex-x-center flex-y-center"
                             v-if="form.order.status_4.icon">
                          <img :src="form.order.status_4.icon" alt=""
                               style="width: 100%;height: 100%;">
                          <span class="flex-x-center flex-y-center pic-del"
                                @click="form.order.status_4.icon=''"> <i
                            class="el-icon-close"></i></span>
                        </div>
                        <file-picker v-if="!form.order.status_4.icon"
                                     style="margin-right: 5px;" v-model="form.order.status_4.icon"
                                     width="88" height="88">
                          <div flex="main:center cross:center"
                               class="add-image-btn flex-x-center flex-y-center">
                            <i class="el-icon-upload"></i>
                          </div>
                        </file-picker>
                      </div>
                      <div style="margin-top: 10px;">
                        <el-form-item label="是否显示">
                          <el-radio-group v-model="form.order.status_4.is_show">
                            <el-radio :label="0">隐藏</el-radio>
                            <el-radio :label="1">显示</el-radio>
                          </el-radio-group>
                        </el-form-item>
                        <el-input v-model="form.order.status_4.name" placeholder="名称"
                                  size="small" style="margin-bottom: 5px"></el-input>
                      </div>
                    </div>
                  </div>
                </div>
              </el-form-item>
            </el-form>


            <el-form label-width="100px" style="width:800px" v-if="currentSelectBlock=='finance'">
              <el-row>
                <el-col :span="16">
                  <el-form-item label="标题" label-width="100px">
                    <el-input v-model="form.finance.name" placeholder="名称" size="small"
                              style="margin-bottom: 5px">
                    </el-input>
                  </el-form-item>
                </el-col>
              </el-row>

              <el-form-item label="积分">
                <div class="edit-nav-item">
                  <div flex="dir:left box:first cross:center">
                    <div class="flex-row flex-y-center">
                      <el-form-item label="是否显示">
                        <el-radio-group v-model="form.finance.integral.is_show">
                          <el-radio :label="0">隐藏</el-radio>
                          <el-radio :label="1">显示</el-radio>
                        </el-radio-group>
                      </el-form-item>
                    </div>
                    <div class="flex-row flex-y-center">
                      <el-form-item label="名称">
                        <el-input v-model="form.finance.integral.name" placeholder="名称"
                                  size="small" style="margin-bottom: 5px"></el-input>
                      </el-form-item>
                    </div>
                  </div>
                </div>
              </el-form-item>
              <el-form-item label="余额">
                <div class="edit-nav-item">
                  <div flex="dir:left box:first cross:center">
                    <div class="flex-row flex-y-center">
                      <el-form-item label="是否显示">
                        <el-radio-group v-model="form.finance.balance.is_show">
                          <el-radio :label="0">隐藏</el-radio>
                          <el-radio :label="1">显示</el-radio>
                        </el-radio-group>
                      </el-form-item>
                    </div>
                    <div class="flex-row flex-y-center">
                      <el-form-item label="名称">
                        <el-input v-model="form.finance.balance.name" placeholder="名称"
                                  size="small" style="margin-bottom: 5px"></el-input>
                      </el-form-item>
                    </div>
                  </div>
                </div>
              </el-form-item>
              <el-form-item label="佣金">
                <div class="edit-nav-item">
                  <div flex="dir:left box:first cross:center">
                    <div class="flex-row flex-y-center">
                      <el-form-item label="是否显示">
                        <el-radio-group v-model="form.finance.price.is_show">
                          <el-radio :label="0">隐藏</el-radio>
                          <el-radio :label="1">显示</el-radio>
                        </el-radio-group>
                      </el-form-item>
                    </div>
                    <div class="flex-row flex-y-center">
                      <el-form-item label="名称">
                        <el-input v-model="form.finance.price.name" placeholder="名称"
                                  size="small" style="margin-bottom: 5px"></el-input>
                      </el-form-item>
                    </div>
                  </div>
                </div>
              </el-form-item>
              <el-form-item label="优惠券">
                <div class="edit-nav-item">
                  <div flex="dir:left box:first cross:center">
                    <div class="flex-row flex-y-center">
                      <el-form-item label="是否显示">
                        <el-radio-group v-model="form.finance.coupon.is_show">
                          <el-radio :label="0">隐藏</el-radio>
                          <el-radio :label="1">显示</el-radio>
                        </el-radio-group>
                      </el-form-item>
                    </div>
                    <div class="flex-row flex-y-center">
                      <el-form-item label="名称">
                        <el-input v-model="form.finance.coupon.name" placeholder="名称"
                                  size="small" style="margin-bottom: 5px"></el-input>
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
                    <el-input v-model="form.menus.name" placeholder="名称" size="small"
                              style="margin-bottom: 5px">
                    </el-input>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row>
                <el-col :span="16">
                  <el-form-item label="样式" label-width="100px">
                    <el-radio-group @change="menuTypeChange" v-model="form.menus.type">
                      <el-radio :label="0">格式布局</el-radio>
                      <el-radio :label="1">列表</el-radio>
                    </el-radio-group>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-form-item label="菜单">
                <div v-for="(item,index) in form.menus.list" class="edit-nav-item">
                  <div class="nav-edit-options">
                    <el-button @click="menuItemDelete(index)" type="primary"
                               icon="el-icon-delete" style="top: -6px;right: -31px;"></el-button>
                  </div>
                  <div flex="dir:left box:first cross:center">
                    <div>
                      <div flex="main:center cross:center"
                           class="add-image-btn flex-x-center flex-y-center" v-if="item.icon">
                        <img :src="item.icon" alt="" style="width: 100%;height: 100%;">
                        <span class="flex-x-center flex-y-center pic-del"
                              @click="deleteIcon(item)"> <i class="el-icon-close"></i></span>
                      </div>
                      <file-picker v-if="!item.icon" style="margin-right: 5px;"
                                   v-model="item.icon" width="88" height="88">
                        <div flex="main:center cross:center"
                             class="add-image-btn flex-x-center flex-y-center">
                          <i class="el-icon-upload"></i>
                        </div>
                      </file-picker>
                    </div>
                    <div style="margin-top: 10px;">
                      <el-input v-model="item.name" placeholder="名称" size="small"
                                style="margin-bottom: 5px">
                      </el-input>
                      <div>
                        <el-input v-model="item.url" placeholder="点击选择链接" readonly
                                  size="small">
                          <el-button size="small" slot="append"
                                     @click="selectMenuLinkClick(index)">选择链接
                          </el-button>
                        </el-input>
                      </div>
                    </div>
                  </div>
                </div>
                <el-button size="small" @click="addMenuItem">添加图标</el-button>
              </el-form-item>
            </el-form>
            <el-form label-width="100px" style="width:800px" v-if="currentSelectBlock=='menus2'">
              <el-row>
                <el-col :span="16">
                  <el-form-item label="标题" label-width="100px">
                    <el-input v-model="form.menus2.name" placeholder="名称" size="small"
                              style="margin-bottom: 5px">
                    </el-input>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row>
                <el-col :span="16">
                  <el-form-item label="是否显示" label-width="100px">
                    <el-radio-group v-model="form.menus2.is_show">
                      <el-radio :label="0">否</el-radio>
                      <el-radio :label="1">是</el-radio>
                    </el-radio-group>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-row>
                <el-col :span="16">
                  <el-form-item label="样式" label-width="100px">
                    <el-radio-group v-model="form.menus2.type">
                      <el-radio :label="0">格式布局</el-radio>
                      <el-radio :label="1">列表</el-radio>
                    </el-radio-group>
                  </el-form-item>
                </el-col>
              </el-row>
              <el-form-item label="菜单">
                <div v-for="(item,index) in form.menus2.list" class="edit-nav-item">
                  <div class="nav-edit-options">
                    <el-button @click="menuItemDelete2(index)" type="primary"
                               icon="el-icon-delete" style="top: -6px;right: -31px;"></el-button>
                  </div>
                  <div flex="dir:left box:first cross:center">
                    <div>
                      <div flex="main:center cross:center"
                           class="add-image-btn flex-x-center flex-y-center" v-if="item.icon">
                        <img :src="item.icon" alt="" style="width: 100%;height: 100%;">
                        <span class="flex-x-center flex-y-center pic-del"
                              @click="deleteIcon(item)"> <i class="el-icon-close"></i></span>
                      </div>
                      <file-picker v-if="!item.icon" style="margin-right: 5px;"
                                   v-model="item.icon" width="88" height="88">
                        <div flex="main:center cross:center"
                             class="add-image-btn flex-x-center flex-y-center">
                          <i class="el-icon-upload"></i>
                        </div>
                      </file-picker>
                    </div>
                    <div style="margin-top: 10px;">
                      <el-input v-model="item.name" placeholder="名称" size="small"
                                style="margin-bottom: 5px">
                      </el-input>
                      <div>
                        <el-input v-model="item.url" placeholder="点击选择链接" readonly
                                  size="small">
                          <el-button size="small" slot="append"
                                     @click="selectMenuLinkClick2(index)">选择链接
                          </el-button>
                        </el-input>
                      </div>
                    </div>
                  </div>
                </div>
                <el-button size="small" @click="addMenuItem2">添加图标</el-button>
              </el-form-item>
            </el-form>

            <el-form label-width="100px" style="width:800px" v-if="currentSelectBlock=='tabbar'">
              <el-form-item label="文字颜色">
                <el-color-picker v-model="form.tabbar.color"></el-color-picker>
              </el-form-item>
              <el-form-item label="菜单">
                <div v-for="(item,index) in form.tabbar.list" class="edit-nav-item">
                  <div class="nav-edit-options">
                    <el-button @click="itemDelete(index)" type="primary" icon="el-icon-delete"
                               style="top: -6px;right: -31px;"></el-button>
                    <el-button @click="up(index)" type="primary" icon="el-icon-arrow-up"
                               style="top: 25px;right: -31px;"></el-button>

                    <el-button @click="down(index)" type="primary" icon="el-icon-arrow-down"
                               style="top: 55px;right: -31px;"></el-button>
                  </div>
                  <div flex="dir:left box:first cross:center">
                    <div class="flex-row">
                      <el-form-item label="选中图标: "
                                    style="padding-left: 10px;margin-bottom: 10px;">
                        <div flex="main:center cross:center"
                             class="add-image-btn flex-x-center flex-y-center"
                             v-if="item.active_icon">
                          <img :src="item.active_icon" alt=""
                               style="width: 100%;height: 100%;">
                          <span class="flex-x-center flex-y-center pic-del"
                                @click="deleteActiveIcon(item)"> <i
                            class="el-icon-close"></i></span>
                        </div>
                        <file-picker v-if="!item.active_icon" style="margin-right: 5px;"
                                     v-model="item.active_icon" width="88" height="88">
                          <div flex="main:center cross:center"
                               class="add-image-btn flex-x-center flex-y-center">
                            <i class="el-icon-upload"></i>
                          </div>
                        </file-picker>
                      </el-form-item>
                      <el-form-item label="未选中图标: ">
                        <div flex="main:center cross:center"
                             class="add-image-btn flex-x-center flex-y-center"
                             v-if="item.icon">
                          <img :src="item.icon" alt="" style="width: 100%;height: 100%;">
                          <span class="flex-x-center flex-y-center pic-del"
                                @click="deleteIcon(item)"> <i
                            class="el-icon-close"></i></span>
                        </div>
                        <file-picker v-if="!item.icon" style="margin-right: 5px;"
                                     v-model="item.icon" width="88" height="88">
                          <div flex="main:center cross:center"
                               class="add-image-btn flex-x-center flex-y-center">
                            <i class="el-icon-upload"></i>
                          </div>
                        </file-picker>
                      </el-form-item>
                    </div>
                    <div style="margin-top: 10px;">
                      <el-input v-model="item.name" placeholder="名称" size="small"
                                style="margin-bottom: 5px">
                      </el-input>
                      <div>
                        <el-input v-model="item.url" placeholder="点击选择链接" readonly
                                  size="small">
                          <el-button size="small" @click="selectLinkClick(index)"
                                     slot="append"> 选择链接
                          </el-button>
                        </el-input>
                      </div>
                    </div>
                  </div>
                </div>
                <el-button size="small" @click="addItem">添加菜单</el-button>
              </el-form-item>
            </el-form>

          </div>
        </div>
      </div>
      <link-picker @selected="linkSelected" @cancel="cancelLink" :showLink="showLink">
      </link-picker>
      <link-picker @selected="menuLinkSelected" @cancel="cancelLink" :showLink="showMenuLink">
      </link-picker>
      <link-picker @selected="menuLinkSelected2" @cancel="cancelLink" :showLink="showMenuLink2">
      </link-picker>
    </el-card>

  </div>
</template>

<script>
  import draggable from 'vuedraggable'
  import LinkPicker from '@/components/mall/LinkPicker'

  export default {
    name: 'mall-user',
    components: {
      LinkPicker,
      draggable
    },

    data() {
      return {
        showMenuLink: false,
        showMenuLink2: false,
        showLink: false,
        currentSelectBlock: '',
        currentMenuEditNavIndex: 0,
        currentMenuEditNavIndex2: 0,
        currentEditNavIndex: 0,
        index: 0,
        form: {
          header: {
            user_bg: '',
            avatar: '',
            goods: {
              is_show: 1,
              name: '商品收藏'
            },
            order: {
              is_show: 1,
              name: '成交订单'
            },
            footmark: {
              is_show: 1,
              name: '足迹'
            },
          },
          menus: {
            type: 0,
            name: '常用工具',
            list: [{
              url: '',
              icon: '',
              name: '示例'
            },],
          },
          menus2: {
            type: 0,
            is_show: 0,
            name: '常用工具',
            list: [{
              url: '',
              icon: '',
              name: '示例'
            },],
          },
          finance: {
            name: '我的资产',
            integral: {
              name: '积分',
              is_show: 1,
            },
            balance: {
              name: '余额',
              is_show: 1,
            },
            price: {
              name: '佣金',
              is_show: 1,
            },
            coupon: {
              name: '优惠券',
              is_show: 1,
            },
          },
          order: {
            status_0: {
              name: '待付款',
              icon: '',
              is_show: 1,
            },
            status_1: {
              name: '待发货',
              icon: '',
              is_show: 1,
            },
            status_2: {
              name: '待收货',
              icon: '',
              is_show: 1,
            },
            status_3: {
              name: '待评价',
              is_show: 1,
              icon: ''
            },
            status_4: {
              name: '退款/售后',
              is_show: 1,
              icon: ''
            },
          },
          tabbar: {
            color: '#E41F19',
            list: [],
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
      cancelLink() {
        this.showLink = false;
        this.showMenuLink = false;
      },
      chooseBlock(block) {
        this.currentSelectBlock = block;
      },
      menuTypeChange(e) {
        
      },
      up(index) {

        if (index > 0) {
          let oldItem = this.form.tabbar.list[index];
          let item = this.form.tabbar.list[index - 1];
          this.form.tabbar.list[index - 1] = oldItem;
          this.form.tabbar.list[index] = item;

          this.$forceUpdate();
        }

      },
      down(index) {

        if (index < this.form.tabbar.list.length - 1) {
          let oldItem = this.form.tabbar.list[index];
          let item = this.form.tabbar.list[index + 1];
          this.form.tabbar.list[index + 1] = oldItem;
          this.form.tabbar.list[index] = item;

          this.$forceUpdate();
        }

      },
      dragEnd(e) {
        
      },
      getSetting() {
        this.is_loading = true;
        this.$request({
          url: '/mall/mall/user-center'
        }).then(res => {
          this.is_loading = false;
          if (res.code == 0) {
            Object.assign(this.form, res.data.page_data);
            this.form.tabbar = res.data.page_data.tabbar
          }
        })
      },
      selectNav(index) {
        this.currentSelectBlock = 'tabbar';

        this.select_index = index
      },
      linkSelected(e) {
        if (!this.form.tabbar.list[this.currentEditNavIndex].name || this.form.tabbar.list[this
            .currentEditNavIndex].name ==
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
      menuLinkSelected2(e) {
        if (!this.form.menus2.list[this.currentMenuEditNavIndex2].name || this.form.menus2.list[this
            .currentMenuEditNavIndex2].name ==
          '') {
          this.form.menus2.list[this.currentMenuEditNavIndex2].name = e.name
        }

        this.showLink = false;
        this.showMenuLink2 = false;
        this.form.menus2.list[this.currentMenuEditNavIndex2].url = e.url
        this.$forceUpdate();
      },
      addItem() {
        if (this.form.tabbar.list.length >= 5) {
          this.$message.warning('做多添加5个菜单')
          return;
        }
        this.form.tabbar.list.push({
          icon: '',
          active_icon: '',
          name: '',
          url: '',
        });
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
      menuItemDelete2(index) {
        this.form.menus2.list.splice(index, 1);
      },
      addMenuItem() {
        this.form.menus.list.push({
          pic_url: '',
          name: '',
          url: '',
          open_type: '',
        });
      },
      addMenuItem2() {
        this.form.menus2.list.push({
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
      selectMenuLinkClick2(index) {
        this.showMenuLink2 = true;
        this.currentMenuEditNavIndex2 = index;
      },
      save() {
        this.is_loading = true;
        this.$request({
          url: '/mall/mall/user-center',
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
      },
      updateNav() {
        let self = this;
        self.multipleSelection.forEach(function (item, index) {
          self.form.tabbar.list.push(item)
        });
        self.dialogTableVisible = false;
      }
    }
  }
</script>

<style lang="scss">
  .mobile-preview {
    width: 850px;
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

  .nav-item > div {
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
