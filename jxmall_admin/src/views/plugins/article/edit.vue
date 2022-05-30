<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>文章编辑</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
      </div>
      <el-row>
        <el-col :span="12">
          <el-form ref="form" :model="form" label-width="160px">
            <el-form-item label="文章分类">
              <el-select v-model="form.cat_id" placeholder="请选择">
                <el-option v-for="item in cat_list" :key="item.id" :label="item.name" :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="文章标题">
              <el-input v-model="form.title"></el-input>
            </el-form-item>
            <el-form-item label="作者">
              <el-input v-model="form.created_by"></el-input>
            </el-form-item>
            <el-form-item label="阅读量">
              <el-input v-model="form.views"></el-input>
            </el-form-item>
            <el-form-item label="封面图">
              <single-image-selector :url="form.cover_pic" v-model="form.cover_pic"></single-image-selector>
            </el-form-item>
            <el-form-item label="视频">
              <template v-if="form.video">
                <div class="flex-row">
                  <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
                    <video :src="form.video" alt="" style="width: 100%;height: 100%;" />
                    <div class="flex-x-center flex-y-center pic-del" @click="form.video = ''">
                      <i class="el-icon-close"></i>
                    </div>
                  </div>
                </div>
              </template>
              <file-picker type="video" :max="1" :multiple="false" v-model="form.video"
                v-if="form.video == '' || !form.video">
                <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
                  <i class="el-icon-upload"></i>
                </div>
              </file-picker>
            </el-form-item>
            <el-form-item label="详情">
              <rich-text @contentChange='detailChange' :content="form.detail"></rich-text>
            </el-form-item>
          </el-form>


        </el-col>
      </el-row>

    </el-card>
  </div>
</template>
<script>
import RichText from '@/components/common/RichText'

export default {

  components: {
    RichText,
  },
  data() {
    return {
      cat_list: [],
      form: {
        cat_id: '',
        id: '',
        video: '',
        title: '',
        cover_pic: '',
        detail: '',
        created_by: '',
        views: '',

      },
    }
  },
  created() {

    if (this.$route.query.id) {
      this.form.id = this.$route.query.id
      this.getArticle()
    }
    this.getCatList()
  },
  methods: {
    detailChange(e) {
      this.form.detail = e.content
    },
    getCatList() {
      this.$request({
        url: '/plugin/article/mall/article/all-cat',
      }).then(res => {
        if (res.code == 0) {
          this.cat_list = res.data.list
        }
      })
    },
    getArticle() {
      this.$request({
        url: '/plugin/article/mall/article/article',
        data: this.form,

      }).then(res => {
        if (res.code == 0) {
          if (res.data.article) {
            if (res.data.article.cat_id) {
              this.form = res.data.article
              this.form.cat_id = res.data.article.cat_id.toString()
            }
          }
        }
      })
    },
    save() {
      this.$request({
        url: '/plugin/article/mall/article/article',
        data: this.form,
        method: 'post',
      }).then(res => {
        if (res.code == 0) {
          this.$message.success(res.msg)
        }
      })

    },
  },
}
</script>

<style>
</style>
