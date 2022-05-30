<template>
	<div class="container">
		<div class="diy-component-preview">
			<div class="content">
				<div class='notice-board' :style="cContainerStyle">
					<div class="icon-bg flex-y-center" style="width: 30px;height: 30px;">
						<el-image :src="data.icon" style="width:100%;height: 100% ;">
							<div slot="error" class="image-slot">		
								<i class="el-icon-picture-outline"></i>
							</div>
						</el-image>
					</div>
					<div class="scorll-view" v-if="data.type==1">
						<div class="notice animation">{{data.list[0].content}}</div>
					</div>
					<div class="scorll-view" v-if="data.type==2">

					</div>
				</div>
			</div>
		</div>
		<div class="diy-component-edit">
			<el-form label-width="100px">
				<el-form-item label="背景颜色">
					<el-color-picker v-model="data.bg_color"></el-color-picker>
				</el-form-item>
				<el-form-item label="文字颜色">
					<el-color-picker v-model="data.text_color"></el-color-picker>
				</el-form-item>
				<!-- 	<el-form-item label="样式选择">
					<el-radio-group v-model="data.type">
						<el-radio :label="1">样式1</el-radio>
						<el-radio :label="2">样式2</el-radio>
						<el-radio :label="3">样式3</el-radio>
					</el-radio-group>
				</el-form-item> -->
				<el-form-item label="通知图标">
					<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center" v-if="data.icon">
						<img :src="data.icon" alt="" style="width: 100%;height: 100%;">
						<span class="flex-x-center flex-y-center pic-del" @click="deleteIcon"> <i class="el-icon-close"></i></span>
					</div>
					<file-picker v-if="!data.icon" style="margin-right: 5px;" v-model="data.icon" width="88" height="88">
						<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
							<i class="el-icon-upload"></i>
						</div>
					</file-picker>
				</el-form-item>

				<el-form-item label="内容">
					<div v-for="(item,index) in data.list" class="edit-nav-item">
						<div flex="dir:left box:first cross:center">
							<div style="margin-top: 10px;">
								<el-input v-model="item.content" placeholder="输入内容" size="small" style="margin-bottom: 5px"></el-input>
								<div>
									<el-input v-model="item.url" placeholder="点击选择链接" readonly size="small">
										<el-button size="small" slot="append" @click="selectLinkClick(index)">选择链接</el-button>
									</el-input>
								</div>
							</div>
						</div>
					</div>
					<!-- 	<el-button size="small" @click="addItem">添加内容</el-button> -->
				</el-form-item>
			</el-form>
		</div>
	<link-picker :showLink="showLink" @selected="linkSelected" @close="showLink=!showLink">
	</link-picker>
	</div>
</template>

<script>
	import LinkPicker from '@/components/mall/LinkPicker'
	export default {
		components: {
			LinkPicker
		},
		name: 'TitleBar',
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
				currentEditIndex: null,
				data: {
					type: 1,
					bg_color: '#ffffff',
					text_color: '#000000',
					icon: '',
					list: [{
						content: '',
						url: ''
					}],
					color: '#353535',
					title_color: '#000000',
					sub_title_color: '#000000',
					title: '标题',
					sub_title: '查看更多'
				},
				dialogTableVisible: false,
			}
		},
		computed: {
			cContainerStyle() {
				return `background-color:${this.data.bg_color};color:${this.data.text_color}`;
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
			linkSelected(e) {
				this.data.list[this.currentEditIndex].url = e.url
				this.$forceUpdate();
				this.showLink = false;
			},
			deleteIcon() {
				this.data.icon = ''
			},
			itemDelete(index) {
				this.data.list.splice(index, 1);
			},
			addItem() {
				this.data.list.push({
					content: '',
					url: '',
				});
			},
			selectLinkClick(index) {
				this.showLink = true;
				this.currentEditIndex = index;
			},
		}
	}
</script>

<style lang="scss">
	.container {
		h2 {
			padding: 20px 0
		}

		.icon-bg {
			padding-left: 30rpx;
			position: relative;
			z-index: 10;
		}

		.scorll-view {
			flex: 1;
			line-height: 1;
			white-space: nowrap;
			overflow: hidden;

		}

		.swiper {
			font-size: 28rpx;
			height: 50rpx;
			flex: 1;
		}

		.swiper-item {
			display: flex;
			align-items: center
		}

		.notice-board {
			width: 100%;
			padding: 0 20px;
			box-sizing: border-box;
			font-size: 28px;
			height: 75px;
			display: flex;
			align-items: center;
			z-index: 999;
		}

		.notice {
			-webkit-backface-visibility: hidden;
			-webkit-perspective: 1000;
			transform: translate3d(100%, 0, 0);
		}

		.animation {
			-webkit-animation: tui-rolling 7s linear infinite;
			animation: tui-rolling 7s linear infinite;
		}

		@-webkit-keyframes tui-rolling {
			0% {
				transform: translate3d(100%, 0, 0);
			}

			100% {
				transform: translate3d(-170%, 0, 0);
			}
		}

		@keyframes tui-rolling {
			0% {
				transform: translate3d(100%, 0, 0);
			}

			100% {
				transform: translate3d(-170%, 0, 0);
			}
		}
	}



	.marquee {
		width: 100%;
		height: 50px;
		align-items: center;
		color: #3A3A3A;
		background-color: #b3effe;
		display: flex;
		box-sizing: border-box;
	}

	.marquee_title {
		padding: 0 20px;
		height: 30px;
		font-size: 14px;
		border-right: 1px solid #d8d8d8;
		align-items: center;
	}

	.marquee_box {
		display: block;
		position: relative;
		width: 60%;
		height: 30px;
		overflow: hidden;
	}

	.marquee_list {
		display: block;
		position: absolute;
		top: 0;
		left: 0;
	}

	.marquee_top {
		transition: all 0.5s;
		margin-top: -30px
	}

	.marquee_list li {
		height: 30px;
		line-height: 30px;
		font-size: 14px;
		padding-left: 20px;
	}

	.marquee_list li span {
		padding: 0 2px;
	}

	.red {
		color: #FF0101;
	}
</style>
