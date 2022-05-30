<template>
	<div class="app-container">
    <el-card class="box-card" v-loading="is_loading">
      <div slot="header" class="clearfix">
        <span>商品海报</span>
        <div style="float: right;">
          <el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
        </div>
      </div>
      <div style="padding-left: 50px;">
        <div class="flex-row">
          <div class="mobile-preview" style="position: relative;background-size: cover;position: relative;">
            <img :src="form.bg_pic.url" style="height: 100%;width: 100%;position: absolute;top: 0;left: 0;" alt="">
            <img :src="form.pic.url" :style="pic" style="height: 100%;width: 100%;position: absolute;top: 0;left: 0;" alt="">
            <img :src="form.qr_code.url" alt="" style="position: absolute;top: 0;left: 0;" :style="qrcode">
            <img :src="form.head.url" alt="" style="position: absolute;top: 0;left: 0;" :style="head">
            <span style="position: absolute;top: 0;left: 0;" :style="nickname">{{form.nickname.text}}</span>
            <span style="position: absolute;top: 0;left: 0;" :style="name">{{form.name.text}}</span>
            <span style="position: absolute;top: 0;left: 0;" :style="desc">{{form.desc.text}}</span>
            <span style="position: absolute;top: 0;left: 0;" :style="price">{{form.price.text}}</span>
          </div>
          <el-scrollbar style="padding: 0 20px; width: 800px;height: 850px;">
            <el-form label-width="100px" style="width:800px">
              <el-form-item label="背景图">
                <div class="edit-nav-item">
                  <div flex="dir:left box:first cross:center">
                    <div class="flex-row flex-y-center">
                      <div>
                        <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center" v-if="form.bg_pic.url">
                          <img :src="form.bg_pic.url" alt="" style="width: 100%;height: 100%;">
                          <span class="flex-x-center flex-y-center pic-del"> <i class="el-icon-close" @click="deleteImg('bg_pic')"></i></span>
                        </div>
                        <file-picker v-if="!form.bg_pic.url" style="margin-right: 5px;" v-model="form.bg_pic.url" width="88" height="88">
                          <div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
                            <i class="el-icon-upload"></i>
                          </div>
                        </file-picker>
                      </div>
                    </div>
                  </div>
                </div>
              </el-form-item>

              <el-form-item label="商品图片">
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div style="text-align: end;">宽：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.pic.width" :max="750" :min="0" :step="1"></el-slider>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div style="text-align: end;">高：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.pic.height" :max="1330" :min="0" :step="1"></el-slider>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>左：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.pic.left" :max="622" :min="0" :step="1"></el-slider>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>上：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.pic.top" :max="1222" :min="0" :step="1"></el-slider>
                </div>
              </el-form-item>
              <el-form-item label="商品名称">
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;height: 80px;">
                  <div>颜色：</div>
                  <el-color-picker v-model="form.name.color"></el-color-picker>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>字号：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.name.font" :max="100" :min="0" :step="1"></el-slider>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>左：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.name.left" :max="622" :min="0" :step="1"></el-slider>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>上：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.name.top" :max="1222" :min="0" :step="1"></el-slider>
                </div>
              </el-form-item>
              <el-form-item label="价格">
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;height: 80px;">
                  <div>颜色：</div>
                  <el-color-picker v-model="form.price.color"></el-color-picker>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>字号：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.price.font" :max="100" :min="0" :step="1"></el-slider>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>左：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.price.left" :max="622" :min="0" :step="1"></el-slider>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>上：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.price.top" :max="1222" :min="0" :step="1"></el-slider>
                </div>
              </el-form-item>

              <el-form-item label="二维码">
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div style="text-align: end;">大小：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.qr_code.size" :max="400" :min="0" :step="1"></el-slider>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>左：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.qr_code.left" :max="622" :min="0" :step="1"></el-slider>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>上：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.qr_code.top" :max="1222" :min="0" :step="1"></el-slider>
                </div>
              </el-form-item>
              <el-form-item label="头像">

                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;height: 60px;">
                  <div style="text-align: end;">是否显示：</div>
                  <el-radio-group v-model="form.head.is_show">
                    <el-radio :label="0">不显示</el-radio>
                    <el-radio :label="1">显示</el-radio>

                  </el-radio-group>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div style="text-align: end;">大小：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.head.size" :max="200" :min="0" :step="1"></el-slider>
                </div>

                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div style="text-align: end;">左：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.head.left" :max="622" :min="0" :step="1"></el-slider>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>上：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.head.top" :max="1222" :min="0" :step="1"></el-slider>
                </div>
              </el-form-item>
              <el-form-item label="昵称">
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;height: 60px;">
                  <div style="text-align: end;">是否显示：</div>
                  <el-radio-group v-model="form.nickname.is_show">
                    <el-radio :label="0">不显示</el-radio>
                    <el-radio :label="1">显示</el-radio>
                  </el-radio-group>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;height: 80px;">
                  <div>颜色：</div>
                  <el-color-picker v-model="form.nickname.color"></el-color-picker>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>字号：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.nickname.font" :max="100" :min="0" :step="1"></el-slider>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>左：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.nickname.left" :max="622" :min="0" :step="1"></el-slider>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>上：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.nickname.top" :max="1222" :min="0" :step="1"></el-slider>
                </div>
              </el-form-item>
              <el-form-item label="描述">
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;height: 80px;">
                  <div>颜色：</div>
                  <el-color-picker v-model="form.desc.color"></el-color-picker>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>内容：</div>
                  <el-input style="width: 90%;" v-model="form.desc.text"></el-input>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>字号：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.desc.font" :max="100" :min="0" :step="1"></el-slider>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>左：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.desc.left" :max="622" :min="0" :step="1"></el-slider>
                </div>
                <div class="edit-nav-item flex-row flex-y-center" style="width: 100%;">
                  <div>上：</div>
                  <el-slider show-input style="width: 90%;" v-model="form.desc.top" :max="1222" :min="0" :step="1"></el-slider>
                </div>
              </el-form-item>
            </el-form>
          </el-scrollbar>
        </div>
      </div>

    </el-card>
  </div>
</template>

<script>
	export default {
    name:'poster-goods',

		data() {
			return {
				showMenuLink: false,
				showLink: false,
				currentSelectBlock: '',
				currentMenuEditNavIndex: 0,
				currentEditNavIndex: 0,
				form: {
					bg_pic: {
						url: ''
					},
					pic: {
						url: "",
						left: 0,
						top: 0,
						width: 0,
						height: 0,
					},
					head: {
						url: "",
						left: 0,
						top: 0,
						size: ''
					},
					nickname: {
						left: 0,
						top: 0,
						text: '',
						font: '',

					},
					name: {
						left: 0,
						top: 0,
						text: '',
						font: '',
					},
					qr_code: {
						url: "",
						left: 0,
						top: 0,
					},
					desc: {
						'is_show': 1,
						'width': 260,
						'font': 16,
						'top': 1150,
						'left': 495,
						'color': '#777777',
						'text': '长按识别二维码进入',
						'file_type': 'text',
					},
					price: {
						'is_show': 1,
						'width': 260,
						'font': 16,
						'top': 1150,
						'left': 495,
						'color': '#777777',
						'text': '￥ 100.00',
						'file_type': 'text',
					},
				},
				select_index: 0,
				is_loading: false,
			}
		},
		computed: {
			posterBg() {
				return `background:url(${this.form.bg_pic.url}) no-repeat; background-size: cover;`;
			},
			qrcode() {
				return `left:${this.form.qr_code.left}px;top:${this.form.qr_code.top}px;width:${this.form.qr_code.size}px;height:${this.form.qr_code.size}px`;
			},
			head() {
				return `left:${this.form.head.left}px;top:${this.form.head.top}px;width:${this.form.head.size}px;height:${this.form.head.size}px;display:${this.form.head.is_show==1?'block':'none'}`;
			},
			nickname() {
				return `left:${this.form.nickname.left}px;top:${this.form.nickname.top}px;font-size:${this.form.nickname.font}px;color:${this.form.nickname.color};display:${this.form.nickname.is_show==1?'block':'none'}`;
			},
			name() {
				return `left:${this.form.name.left}px;top:${this.form.name.top}px;font-size:${this.form.name.font}px;color:${this.form.name.color};`;
			},
			pic() {
				return `left:${this.form.pic.left}px;top:${this.form.pic.top}px;width:${this.form.pic.width}px;height:${this.form.pic.height}px;width:${this.form.pic.width}px;height:${this.form.pic.height}px;`;
			},
			desc() {
				return `left:${this.form.desc.left}px;top:${this.form.desc.top}px;font-size:${this.form.desc.font}px;color:${this.form.desc.color};font-size:${this.form.desc.font}px;`;
			},
			price() {
				return `left:${this.form.price.left}px;top:${this.form.price.top}px;font-size:${this.form.price.font}px;color:${this.form.price.color};font-size:${this.form.price.font}px;`;
			},
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
					url: '/mall/poster/goods'
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						Object.assign(this.form, res.data.setting);
					}
				})
			},
			selectNav(index) {
				this.currentSelectBlock = 'tabbar';

				this.select_index = index
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

			save() {
				this.is_loading = true;
				this.$request({
					url: '/mall/poster/goods',
					method: 'post',
					data: {
						data: JSON.stringify(this.form)
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.$message.success('保存成功')
					}
				})
			},
			deleteImg(e) {
				if (e == 'bg_pic') {
					this.form.bg_pic.url = '';
				}

			},

		}
	}
</script>

<style lang="scss">
	.mobile-preview {
		width: 750px;
		background-color: #F3F3F3;
		height: 1350px;
		zoom: 50%;
		border-radius: 10px;
		box-sizing: border-box;
		border: 4px solid #F3F3F3;
		overflow: hidden;
		font-size: 24px;

	}

	.el-scrollbar__wrap {
		overflow-x: hidden;
	}

	.scrollbar-wrapper {
		overflow-x: hidden !important;
	}

	.el-scrollbar__bar.is-vertical {
		right: 0px;
	}

	::-webkit-scrollbar {
		display: none
	}

	.el-scrollbar {
		height: 100%;
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
