<template id="test">

  <div class="container">
    <div class="diy-component-preview" style="position: relative;width: 100%">
      <div style="position: absolute;" :style="cContainerStyle">
        <div style="position: relative;margin-bottom: 20px" v-if="data.list.length>0" v-for="item in data.list">
          <div :style="{width:item.imgWidth+'px',height:item.imgHeight+'px'}" style="position: relative">
            <el-image style="width: 100%;height: 100%;overflow: hidden" :style="{borderRadius:data.radius}"
                      :src="item.imgUrl"
                      fit="fit">
            </el-image>
            <div v-if="item.text"
                 style="position: absolute;width: 100%;height: 100%;top: 0;left: -100px;text-align: right"
                 class="flex-y-center flex-x-center"
                 :style="{fontSize: item.fontSize+'px',color: item.color}">
              <div style="text-align: right">
                {{item.text}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="diy-component-edit">
      <el-form label-width="100px">
        <el-form-item label="按钮组类型">
          <el-radio-group v-model="data.type">
            <el-radio :label="0">展开</el-radio>
            <el-radio :label="1">收缩</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="按钮圆角">
          <el-radio-group v-model="data.radius">
            <el-radio label="0">否</el-radio>
            <el-radio label="50%">是</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="收缩按钮背景颜色" v-if="data.type==1">
          <el-color-picker v-model="data.bgColor"></el-color-picker>
        </el-form-item>
        <el-form-item label="右边距">
          <el-slider show-input :max="750" v-model="data.right"></el-slider>
        </el-form-item>
        <el-form-item label="底边距">
          <el-slider show-input :max="2048" v-model="data.bottom"></el-slider>
        </el-form-item>
        <el-form-item label="默认按钮高度">
          <el-slider show-input :max="120" v-model="data.height"></el-slider>
        </el-form-item>
        <el-form-item label="默认按钮宽度">
          <el-slider show-input :max="120" v-model="data.width"></el-slider>
        </el-form-item>
        <el-form-item label="按钮组">
          <el-empty size="mini" description='暂无按钮' v-if="data.list.length==0">
          </el-empty>
          <template v-for="(item,index) in data.list" v-if="data.list.length>0">
            <div style="border: dashed 1px #1E90FA;position:relative;padding: 3px;margin-bottom: 10px">
              <div style="width: 95%;position: relative">
                <el-button type="primary" size="mini" class="flex-x-center flex-y-center pic-del"
                           style="position: absolute;top: 0;right: -30px;width: 20px"
                           @click="data.list.splice(index,1)">
                  <i class="el-icon-close"></i>

                </el-button>
                <el-form-item label="按钮类型">
                  <el-radio-group v-model="item.openType">
                    <el-radio label="nav">跳转链接</el-radio>
                    <el-radio label="contact">客服按钮（小程序按钮）</el-radio>
                    <el-radio label="call">拨打电话</el-radio>
                    <el-radio label="location">导航</el-radio>
                  </el-radio-group>
                </el-form-item>
                <el-form-item label="跳转链接" v-if="item.openType=='nav'">
                  <el-input v-model="item.value" placeholder="点击选择链接" size="small">
                    <link-picker slot="append" v-model="item.value">
                      <el-button size="small">选择链接</el-button>
                    </link-picker>
                  </el-input>
                </el-form-item>
                <el-form-item label="电话" v-if="item.openType=='call'">
                  <el-input v-model="item.value" placeholder="电话" size="small">
                  </el-input>
                </el-form-item>
                <el-form-item label="地址" v-if="item.openType=='location'">
                  <el-input v-model="item.value" placeholder="地址" size="small">
                  </el-input>
                </el-form-item>
                <el-form-item label="经度" v-if="item.openType=='location'">
                  <el-input v-model="item.lon" placeholder="经度" size="small">
                  </el-input>
                </el-form-item>

                <el-form-item label="纬度" v-if="item.openType=='location'">
                  <el-input v-model="item.lat" placeholder="纬度" size="small">
                  </el-input>
                </el-form-item>


                <el-form-item label="图标">
                  <div>
                    <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
                         v-if="item.imgUrl">
                      <img :src="item.imgUrl" alt="" style="width: 100%;height: 100%;">
                      <span class="flex-x-center flex-y-center pic-del" @click="item.imgUrl=''">
										<i class="el-icon-close"></i></span>
                    </div>
                    <file-picker v-if="!item.imgUrl" style="margin-right: 5px;"
                                 v-model="item.imgUrl" width="88" height="88">
                      <div flex="main:center cross:center"
                           class="add-image-btn flex-x-center flex-y-center">
                        <i class="el-icon-upload"></i>
                      </div>
                    </file-picker>
                  </div>
                </el-form-item>

                <el-form-item label="图片高度">
                  <el-slider show-input :max="120" v-model="item.imgHeight"></el-slider>
                </el-form-item>
                <el-form-item label="图片宽度">
                  <el-slider show-input :max="120" v-model="item.imgWidth"></el-slider>
                </el-form-item>
                <el-form-item label="按钮名称" style="margin-top: 20px">
                  <el-input v-model="item.text" placeholder="按钮名称，可不填"></el-input>
                </el-form-item>
                <el-form-item label="字号大小">
                  <el-slider show-input :max="30" v-model="item.fontSize"></el-slider>
                </el-form-item>
                <el-form-item label="文字颜色">
                  <el-color-picker v-model="item.color"></el-color-picker>
                </el-form-item>
                <el-form-item label="按钮背景颜色">
                  <el-color-picker v-model="item.bgColor"></el-color-picker>
                </el-form-item>
              </div>
            </div>

          </template>

          <div class="flex-y-center flex-x-center">
            <el-button type="text" @click="addButton">新增</el-button>
          </div>
        </el-form-item>


      </el-form>
    </div>

  </div>

</template>

<script>
  import LinkPicker from '@/components/mall/LinkPicker'

  export default {
    components: {
      LinkPicker
    },
    name: 'FloatButton',
    props: {
      value: {
        type: Object,
        default: () => {
          return null;
        }
      },
      active: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        dialogVisible: false,
        currentEditNavIndex: null,
        item: {
          type: 0,
          value: '',
          bgColor: "#16C2C2",
          //图标/图片地址
          imgUrl: "",
          //图片高度 rpx
          imgHeight: 64,
          //图片宽度 rpx
          imgWidth: 64,
          //名称
          text: "名称",
          //字体大小
          fontSize: 34,
          //字体颜色
          color: "#fff",
          lat: '',
          lon: '',
        },
        data: {
          radius: '50%',
          width: 108,
          height: 108,
          bottom: 100,
          right: 20,
          list: [],
          openType: 'nav',//0展开类型  1 收缩类型
          bgColor: '#E22729',
        },
        dialogTableVisible: false,
        page: 1,

      }
    },
    computed: {
      cContainerStyle() {
        return `bottom:${this.data.bottom}px;right:${this.data.right}px`;
      },
      cNavStyle() {
        return `width:${100 / this.data.columns}%;`;
      },
    },
    created() {
      if (!this.value) {
        this.$emit('input', this.data)
      } else {
        this.data = this.value;
      }
     
    },


    watch: {
      data: {
        deep: true,
        handler(newVal, oldVal) {
          this.$emit('input', newVal, oldVal);
        },
      }
    },
    methods: {
      addButton() {
        this.data.list.push(this.item);
      },
      handleClose(e) {
        this.dialogVisible = false;
      },
      showHotPicker() {
        this.dialogVisible = true;
      },
      linkSelected(e) {
        Object.assign(this.data, e)
        this.$forceUpdate();
        console.log(this.data);
      },
    }
  }
</script>

<style lang="scss">
  .container {
    .edit-item {
      border: 1px solid #e2e2e2;
      line-height: normal;
      padding: 5px;
      margin-bottom: 5px;
      max-width: 450px;
    }
  }
</style>
