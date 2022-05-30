<template>
	<div class="app-container">
		<el-card>
			<div slot="header" class="clearfix">
				<span>活动弹窗</span>
				<el-button style="float: right; padding: 3px 0" type="text" @click="save">保存</el-button>
			</div>
			<div class="container flex-row">
				<div class="diy-component-preview">
					<div class="content"
						style="width: 750px;height: 1280px; background-color: #F0F0F0;border: solid #DDDDDD;padding: 10px;background: rgba(0,0,0,0.6);zoom: 0.5;">
						<div style="width: 100%;height: 100%;position: relative;">
							<div class="img-container">
								<div style="position: relative;text-align: center;width: 100%;height: 100%;">
									<el-image :src="data.bg.url" style="margin: 0 auto;overflow: hidden;"
										:style="{top:data.bg.top+'%',height:(data.bg.height+'%'),borderRadius:data.bg.radius+'px',width:data.bg.width+'%'}"
										fit="fill"></el-image>
								</div>
							</div>
							<div style="z-index: 999;position: absolute;width: 100%;left: 0;"
								:style="{top:data.btn.top+'%'}" class="flex-y-center flex-x-center">
								<div style="height: 100px;overflow: hidden;border: solid #f0f0f0 1px;"
									:style="{borderColor:data.btn.borderColor,width:data.btn.width+'%',borderRadius:data.btn.radius+'px',backgroundColor:data.btn.bgColor,color:data.btn.color,fontSize:data.btn.fontSize+'px',height:data.btn.height+'px'}"
									class="flex-y-center flex-x-center" v-if="data.btn.style==0" @click="btnClick">
									{{data.btn.text}}
								</div>
								<el-image :src="data.btn.btnImg"
									:style="{width:data.btn.width+'%',borderRadius:data.btn.radius+'px',height:data.btn.height+'px'}"
									:fit="'fill'" v-if="data.btn.style==1" @click="btnClick"></el-image>
							</div>
						</div>
					</div>
				</div>
				<div class="diy-component-edit flex-row"
					style="padding-left: 20px;padding-right: 50px;padding-top: 50px;width: 100%;">
					<el-form label-width="100px">
						<div style="background-color: #F0F0F0;height:30px;width: 100%; font-weight: bold;margin-bottom: 20px;"
							class="flex-y-center flex-x-center">
							图片设置
						</div>
						<el-form-item label="背景图片">

							<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
								v-if="data.bg.url">
								<img :src="data.bg.url" alt="" style="width: 100%;height: 100%;">
								<span class="flex-x-center flex-y-center pic-del" @click="data.bg.url=''"> <i
										class="el-icon-close"></i></span>
							</div>
							<file-picker v-if="!data.bg.url" style="margin-right: 5px;" v-model="data.bg.url" width="88"
								height="88">
								<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
									<i class="el-icon-upload"></i>
								</div>
							</file-picker>
						</el-form-item>

						<el-form-item label="图片片顶部位置">
							<el-slider show-input v-model="data.bg.top" :max="100" style="width: 250px;"></el-slider>
						</el-form-item>

						<el-form-item label="图片高度">
							<el-slider show-input v-model="data.bg.height" :max="100"></el-slider>
						</el-form-item>

						<el-form-item label="图片宽度">
							<el-slider show-input v-model="data.bg.width" :max="100"></el-slider>
						</el-form-item>
						<el-form-item label="图片圆角">
							<el-slider show-input v-model="data.bg.radius"></el-slider>
						</el-form-item>
					</el-form>
					<div style="width: 50px;height: 50px;">

					</div>

					<el-form label-width="100px">

						<div style="background-color: #F0F0F0;height:30px;width: 100%; font-weight: bold;margin-bottom: 20px;"
							class="flex-y-center flex-x-center">
							按钮设置
						</div>
						<el-form-item label="按钮类型">
							<el-radio-group v-model="data.btn.type">
								<el-radio :label="0">关闭</el-radio>
								<el-radio :label="1">跳转</el-radio>
							</el-radio-group>
						</el-form-item>
						<el-form-item label="选择链接" v-if="data.btn.type==1">
							<el-input v-model="data.btn.url" placeholder="点击选择链接" readonly size="small">
								<el-button @click="showLink=!showLink" slot="append" size="small">选择链接</el-button>
							</el-input>
						</el-form-item>


						<el-form-item label="按钮样式">
							<el-radio-group v-model="data.btn.style">
								<el-radio :label="0">文字</el-radio>
								<el-radio :label="1">图片</el-radio>
							</el-radio-group>
						</el-form-item>
						<el-form-item label="按钮图片" v-if="data.btn.style==1">
							<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
								v-if="data.btn.btnImg">
								<img :src="data.btn.btnImg" alt="" style="width: 100%;height: 100%;" fit="fill">
								<span class="flex-x-center flex-y-center pic-del" @click="data.btn.btnImg=''"> <i
										class="el-icon-close"></i></span>
							</div>
							<file-picker v-if="!data.btn.btnImg" style="margin-right: 5px;" v-model="data.btn.btnImg"
								width="88" height="88">
								<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
									<i class="el-icon-upload"></i>
								</div>
							</file-picker>
						</el-form-item>

						<el-form-item label="文字颜色" v-if="data.btn.style==0">
							<el-color-picker v-model="data.btn.color"></el-color-picker>
						</el-form-item>

						<el-form-item label="按钮背景色" v-if="data.btn.style==0">
							<el-color-picker v-model="data.btn.bgColor"></el-color-picker>
						</el-form-item>
						<el-form-item label="按钮边框颜色" v-if="data.btn.style==0">
							<el-color-picker v-model="data.btn.borderColor"></el-color-picker>
						</el-form-item>
						<el-form-item label="按钮字体大小" v-if="data.btn.style==0">
							<el-slider show-input v-model="data.btn.fontSize" :max="50"></el-slider>
						</el-form-item>
						<el-form-item label="按钮字体大小" v-if="data.btn.style==0">
							<el-input v-model="data.btn.text"></el-input>
						</el-form-item>
						<el-form-item label="按钮顶部位置">
							<el-slider show-input v-model="data.btn.top" :max="100" style="width: 250px;"></el-slider>
						</el-form-item>

						<el-form-item label="按钮高度">
							<el-slider show-input v-model="data.btn.height" :max="100"></el-slider>
						</el-form-item>

						<el-form-item label="按钮宽度">
							<el-slider show-input v-model="data.btn.width" :max="100"></el-slider>
						</el-form-item>
						<el-form-item label="按钮圆角">
							<el-slider show-input v-model="data.btn.radius"></el-slider>
						</el-form-item>
					</el-form>
					<div style="width: 50px;height: 50px;">

					</div>

					<el-form label-width="100px">
						<div style="background-color: #F0F0F0;height:30px;width: 100%; font-weight: bold;margin-bottom: 20px;"
							class="flex-y-center flex-x-center">
							弹出设置
						</div>
						<el-form-item label="启用弹窗">
							<el-radio-group v-model="data.status">
								<el-radio :label="0">关闭</el-radio>
								<el-radio :label="1">启用</el-radio>
							</el-radio-group>
						</el-form-item>
						<el-form-item label="弹出时机">
							<el-radio-group v-model="data.show_time">
								<el-radio :label="0">每天</el-radio>
								<el-radio :label="1">每次打开</el-radio>
							</el-radio-group>
						</el-form-item>

					</el-form>
				</div>

			</div>
			<link-picker :showLink="showLink" @selected="linkSelected" @cancel="showLink=!showLink">

			</link-picker>

		</el-card>

	</div>
</template>

<script>
	import LinkPicker from '@/components/mall/LinkPicker'
	export default {
    name:'mall-alert-adv',
		components: {
			LinkPicker
		},

		data() {
			return {
				visible: false,
				showLink: false,

				data: {
					status: 0,
					show_time: 0,
					show: true,
					bg: {
						top: 10,
						height: 70,
						width: 80,
						url: '',
						radius: 30
					},
					btn: {
						type: 0, //0 close 跳转按钮
						top: 60,
						width: 60,
						text: '关闭',
						radius: 60,
						height: 100,
						color: '#000',
						bgColor: '',
						borderColor: '#fff',
						url: '',
						fontSize: 35,
						btnImg: '',
						style: 0, //0 普通按钮  1 图片按钮
					},
				},
			}
		},
		computed: {

		},
		created() {
			this.getData();
		},

		methods: {
			getData() {
				this.$request({
					url: '/mall/mall/alert-adv',

				}).then(res => {
					if (res.code == 0) {
						if (res.data.alert_data) {
							this.data = res.data.alert_data
						}
					}

				})
			},
			save() {
				this.visible = false;

				this.$request({
					url: '/mall/mall/alert-adv',
					data: {
						data: JSON.stringify(this.data)
					},
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						this.$message.success(res.msg)
					}
				})

			},
			selectLinkClick() {
				this.showLink = true;
			},

			linkSelected(e) {
				this.data.btn.url = e.url;
				this.showLink = false;
			},
			btnClick(e) {},
		}
	}
</script>

<style lang="scss" scoped="scoped">
	.container {
		.edit-item {
			border: 1px solid #e2e2e2;
			line-height: normal;
			padding: 5px;
			margin-bottom: 5px;
			max-width: 450px;
		}
	}

	.img-container {
		width: 100%;
		position: absolute;
		left: 0%;
		top: 0%;
		height: 100%;
		border-radius: 20px;
		overflow: hidden;
	}
</style>
