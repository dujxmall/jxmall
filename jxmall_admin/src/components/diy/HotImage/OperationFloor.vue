<template>
  <div class="operationFloor">
    <div class="imgBox" @mouseup.left.stop="changeStop()">
      <div class="container" style="width: 750px">
        <img style="width: 100%;" ref="backgroundImg" :src="data.pic_url" ondragstart="return false;"
             oncontextmenu="return false;" onselect="document.selection.empty();" alt=""
             @mousedown.left.stop="mouseDown($event)">
        <!--绘制的热区-->
        <div v-show="caseShow" class="area"
             :style="{width: areaWidth+'px',height: areaHeight+'px',left: starX+'px',top: starY+'px'}"/>
        <!--已有的热区-->
        <AreaBox v-for="(item, index) in data.data" :id="index" :key="index" :area-init.sync="item"
                 @DelAreaBox="DelAreaBox"/>
      </div>
    </div>
  </div>
</template>

<script>
  import AreaBox from './AreaBox'

  export default {
    name: 'OperationFloor',
    components: {
      AreaBox
    },
    props: {
      value: {
        type: Object,
        default: () => {
          return null
        }
      },
      active: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        data: {
          pic_url: '',
          data: []
        },
        starX: 0,
        starY: 0,
        areaWidth: 0,
        areaHeight: 0,
        caseShow: false,
        nowImgWidth: null
      }
    },
    created() {

      this.data = this.value
      if (!this.data) {
        this.data = {
          pic_url: '',
          data: []
        }
      }
    },

    methods: {
      // 绘画热区开始
      mouseDown(e) {
        this.caseShow = true
        // 记录滑动的初始值
        this.starX = e.layerX
        this.starY = e.layerY
        // 鼠标滑动的过程
        if (!document.onmousemove) {
          document.onmousemove = (ev) => {
            this.areaWidth = ev.layerX - this.starX
            this.areaHeight = ev.layerY - this.starY
          }
        }
      },
      // 绘画热区结束
      changeStop() {
        document.onmousemove = null
        if (this.caseShow && this.areaWidth > 10 && this.areaHeight > 10) {
          const data = {
            starX: this.starX,
            starY: this.starY,
            areaWidth: this.areaWidth,
            areaHeight: this.areaHeight,
            nowImgWidth: this.$refs.backgroundImg.width,
            url: null,
            name: '新模块'
          }


          if (!this.data.data) {
            this.data.data = []
          }
          this.data.data.push(data)
        }
        // 初始化绘图
        this.caseShow = false
        this.starX = 0
        this.starY = 0
        this.areaWidth = 0
        this.areaHeight = 0
      },
      // 删除指定热区
      DelAreaBox(id) {
        this.data.data.splice(id, 1)
      }
    }
  }
</script>

<style scoped lang="scss" ref="stylesheet/scss">
  .operationFloor {
    position: relative;

    .header {
      .titleBox {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 100px;

        .name {
          font-size: 13px;
          font-weight: bold;
        }
      }

      .textBox {
        font-size: 12px;
        color: #777;
        margin-bottom: 10px;
      }
    }

    .imgBox {
      display: flex;
      justify-content: center;

      .container {
        position: relative;
        overflow: hidden;
      }

      img {
        cursor: crosshair;
      }

      .area {
        position: absolute;
        width: 200px;
        height: 200px;
        left: 200px;
        top: 300px;
        background: rgba(#2980b9, 0.3);
        border: 1px dashed #34495e;
      }
    }
  }
</style>
