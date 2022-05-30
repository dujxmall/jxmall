<template>
<div class="app-container">
  <el-card class="box-card">
    <div slot="header" class="clearfix">
      <span>分类编辑</span>
      <el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
    </div>
    <el-row>
      <el-col :span="12">
        <el-form ref="form" :model="form" label-width="160px">

          <el-form-item label="分类名称">
            <el-input v-model="form.name"></el-input>
          </el-form-item>
          <el-form-item label="封面图">
            <template v-if="form.cover_pic">
              <div class="flex-row">
                <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
                  <img :src="form.cover_pic" alt="" style="width: 100%;height: 100%;">
                  <div class="flex-x-center flex-y-center pic-del" @click="form.cover_pic=''">
                    <i class="el-icon-close"></i>
                  </div>
                </div>
              </div>
            </template>
            <file-picker :max="1" :multiple="false" v-model="form.cover_pic" v-if="form.cover_pic==''||!form.cover_pic">
              <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
                <i class="el-icon-upload"></i>
              </div>
            </file-picker>
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
			RichText
		},
		data() {
			return {
				form: {
					id: '',
					name: '',
					cover_pic: '',

				}
			}
		},
		created() {

			if (this.$route.query.id) {
				this.form.id = this.$route.query.id;
				this.getCat();
			}
		},
		methods: {
			detailChange(e) {
				this.form.detail = e.content
			},
			getCat() {
				this.$request({
					url: "/plugin/article/mall/article/cat",
					data: this.form,

				}).then(res => {
					if (res.code == 0) {

						if (res.data.cat) {
							this.form = res.data.cat;
						}

					}
				})
			},
			save() {
				this.$request({
					url: "/plugin/article/mall/article/cat",
					data: this.form,
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						this.$message.success(res.msg)
					}
				})

			}
		}
	}
</script>

<style>
</style>
