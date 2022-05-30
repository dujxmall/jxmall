<template>
	<div class="container">
		<div class="diy-component-preview">
			<div class="content">
				<div style="padding: 0 20px;" :style="cContainerStyle">
					<div :style="{color:data.title_color}" style="height: 80px;font-weight: bold;font-size: 25px;"
						class="flex-y-center">{{data.title}}</div>
					<div>
						<el-image :src="data.cover_pic" :style="{height:data.height+'px'}" fit="fill"></el-image>
					</div>
				</div>
			</div>
		</div>
		<div class="diy-component-edit">
			<el-form label-width="100px" style="width: 650px;">
				<el-form-item label="背景颜色">
					<el-color-picker v-model="data.background"></el-color-picker>
				</el-form-item>
				<el-form-item label="标题文字颜色">
					<el-color-picker v-model="data.title_color"></el-color-picker>
				</el-form-item>
				<el-form-item label="标题">
					<el-input v-model="data.title" placeholder="输入标题" size="small">
					</el-input>
				</el-form-item>
				<el-form-item label="封面高度">
					<el-slider show-input v-model="data.height" :max="1000"></el-slider>
				</el-form-item>
				<el-form-item label="选择封面">
					<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
						v-if="data.cover_pic">
						<img :src="data.cover_pic" alt="" style="width: 100%;height: 100%;">
						<span class="flex-x-center flex-y-center pic-del" @click="data.cover_pic=null">
							<i class="el-icon-close"></i></span>
					</div>
					<file-picker :multiple='false' :max="1" v-model="data.cover_pic"
						v-if="!data.cover_pic|| data.cover_pic==''">
						<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
							<i class="el-icon-upload"></i>
						</div>
					</file-picker>
				</el-form-item>
				<el-form-item label="选择视频">
					<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
						v-if="data.video_url">
						<video :src="data.video_url" alt="" style="width: 100%;height: 100%;"></video>
						<span class="flex-x-center flex-y-center pic-del" @click="data.video_url=null">
							<i class="el-icon-close"></i></span>
					</div>
					<file-picker type="video" :multiple='false' :max="1" v-model="data.video_url"
						v-if="!data.video_url|| data.video_url==''">
						<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
							<i class="el-icon-upload"></i>
						</div>
					</file-picker>
				</el-form-item>
			</el-form>
			<link-picker :showLink="showLink" @selected="linkSelected" @close="showLink=!showLink">
			</link-picker>
		</div>
	</div>
</template>

<script>
	import FilePicker from '@/components/common/FilePicker'
	import LinkPicker from '@/components/mall/LinkPicker'
	export default {
		components: {
			LinkPicker,
			FilePicker
		},
		name: 'VideoBlock',
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
				showLink: false,
				currentEditNavIndex: null,
				data: {
					background: '#ffffff',
					color: '#353535',
					title_color: '#000000',
					title: '标题',
					video_url: '',
					cover_pic: '',
					height: 20,
				},
				dialogTableVisible: false,
			}
		},
		computed: {
			cContainerStyle() {
				return `background:${this.data.background};`;
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

		computed: {
			cContainerStyle() {
				return `background:${this.data.background};`;
			},
			cNavStyle() {
				return `width:${100 / this.data.columns}%;`;
			},
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
			chooseCoverPic(e) {
			 
				if (e.length > 0) {
					this.data.cover_pic = e[0].url;
				}
				this.$forceUpdate();
			},
			linkSelected(e) {
				this.showLink = false;

				Object.assign(this.data, e)
				this.$forceUpdate();

			},
		}
	}
</script>

<style lang="scss">
	.container {
		.add-image-btn {
			position: relative;
			width: 100px;
			height: 100px;
			color: #419EFB;
			border: 1px solid #e2e2e2;
			cursor: pointer;
			padding: 2px;
			margin-right: 10px;

			.pic-del {
				position: absolute;
				top: -10px;
				height: 20px;
				width: 20px;
				background-color: #F56C6C;
				border-radius: 50%;
				color: #FFF0FF;
				right: -10px;
			}
		}

		.edit-item {
			border: 1px solid #e2e2e2;
			line-height: normal;
			padding: 5px;
			margin-bottom: 5px;
			max-width: 450px;
		}
	}
</style>
