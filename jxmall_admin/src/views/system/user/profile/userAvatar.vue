<template>
  <div>
    <div class="flex-y-center flex-x-center">
      <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center" v-if="options.img">
        <img :src="options.img" alt="" style="width: 100%;height: 100%;">
        <span class="flex-x-center flex-y-center pic-del" @click="options.img=''">
          <i class="el-icon-close"></i></span>
      </div>
      <file-picker v-if="!options.img" style="margin-right: 5px;" v-model="options.img" width="88" height="88">
        <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
          <i class="el-icon-upload"></i>
        </div>
      </file-picker>
    </div>
   <div class="flex-y-center flex-x-center">
		  <el-button type='text' @click="save">保存</el-button>
	 </div>


  </div>
</template>

<script>
  import store from "@/store";
  import {
    VueCropper
  } from "vue-cropper";
  import {
    uploadAvatar
  } from "@/api/system/user";

  export default {
    components: {
      VueCropper
    },
    props: {
      user: {
        type: Object
      }
    },
    data() {
      return {
        // 是否显示弹出层
        open: false,
        // 是否显示cropper
        visible: false,
        // 弹出层标题
        title: "修改头像",
        options: {
          img: store.getters.avatar, //裁剪图片的地址
          autoCrop: true, // 是否默认生成截图框
          autoCropWidth: 200, // 默认生成截图框宽度
          autoCropHeight: 200, // 默认生成截图框高度
          fixedBox: true // 固定截图框大小 不允许改变
        },
        previews: {}
      };
    },
    methods: {
      // 编辑头像
      editCropper() {
        this.open = true;
      },
      // 打开弹出层结束时的回调
      modalOpened() {
        this.visible = true;
      },
      // 覆盖默认的上传行为
      requestUpload() {},
      // 向左旋转
      rotateLeft() {
        this.$refs.cropper.rotateLeft();
      },
      // 向右旋转
      rotateRight() {
        this.$refs.cropper.rotateRight();
      },
      // 图片缩放
      changeScale(num) {
        num = num || 1;
        this.$refs.cropper.changeScale(num);
      },
      // 上传预处理
      beforeUpload(file) {
        if (file.type.indexOf("image/") == -1) {
          this.$modal.msgError("文件格式错误，请上传图片类型,如：JPG，PNG后缀的文件。");
        } else {
          const reader = new FileReader();
          reader.readAsDataURL(file);
          reader.onload = () => {
            this.options.img = reader.result;
          };
        }
      },
      // 上传图片
      save() {
        uploadAvatar({
          avatar: this.options.img
        }).then(response => {
          store.commit('SET_AVATAR', this.options.img);
          this.$modal.msgSuccess("修改成功");
          this.visible = false;
        });
      },
      // 实时预览
      realTime(data) {
        this.previews = data;
      },
      // 关闭窗口
      closeDialog() {
        this.options.img = store.getters.avatar
        this.visible = false;
      }
    }
  };
</script>
<style scoped lang="scss">
  .user-info-head {
    position: relative;
    display: inline-block;
    height: 120px;
  }

  .user-info-head:hover:after {
    content: '+';
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    color: #eee;
    background: rgba(0, 0, 0, 0.5);
    font-size: 24px;
    font-style: normal;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    cursor: pointer;
    line-height: 110px;
    border-radius: 50%;
  }
</style>
