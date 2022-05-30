<template>
	<div class="areaBox"
		:style="{width: areaInit.areaWidth+'px',height: areaInit.areaHeight+'px',left: areaInit.starX+'px',top: areaInit.starY+'px'}"
		@dblclick="dialogVisible=true" @mousedown.left.stop="mouseDown($event)" @mouseup.left.stop="mouseUp($event)">
		<div>
			<div   style="color: #FFFFFF;font-weight: bold;text-align: center;">{{ areaInit.name }}</div>
			<span class="promptText" style="color: #FFFFFF;">{{ promptText }}</span>
		</div>
		<!--删除-->
		<div class="del" @click.stop="del()" />
		<!--形变点-->
		<div class="shape" @mousedown.left.stop="shapeDown($event)" @mouseup.left.stop="mouseUp($event)" />
		<el-dialog title="板块名称" :visible.sync="dialogVisible" width="40%" :before-close="handleClose">
			<el-form label-width="80px">
				<el-form-item label="板块名称">
					<el-input v-model="areaInit.name" placeholder="板块名称" clearable></el-input>
				</el-form-item>
			</el-form>
			<span slot="footer" class="dialog-footer">
				<el-button type="primary" @click="dialogVisible = false">确 定</el-button>
			</span>
		</el-dialog>
	</div>
</template>

<script>
 	export default {

		name: 'AreaBox',
		props: {
			areaInit: {
				type: Object,
				default: () => {}
			},
			id: {
				type: Number,
				default: null
			}
		},
		data() {
			return {
				dialogVisible: false,
				editBoxShow: false,
				promptText: '双击设置热区',
				showLink: false,
				// box操作初始点
				move: {
					// 拖动
					startX: 0,
					starY: 0,
					// 形变
					start1X: 0,
					start1Y: 0
				},
			}
		},
		methods: {
			handleClose(e) {
				this.dialogVisible = false;
			},

			// 删除
			del() {
				this.$emit('DelAreaBox', this.id)
			},

			// 开始拖动
			mouseDown(e) {
				this.starX = e.clientX
				this.starY = e.clientY
				if (!document.onmousemove) {
					const initX = this.areaInit.starX
					const initY = this.areaInit.starY
					document.onmousemove = (ev) => {
						this.areaInit.starX = initX + ev.clientX - this.starX
						this.areaInit.starY = initY + ev.clientY - this.starY
					}
				}
			},
			// 结束拖动/变形
			mouseUp() {
				document.onmousemove = null
			},
			// 形变开始
			shapeDown(e) {
				this.star1X = e.clientX
				this.star1Y = e.clientY
				if (!document.onmousemove) {
					const initX = this.areaInit.areaWidth
					const initY = this.areaInit.areaHeight
					document.onmousemove = (ev) => {
						this.areaInit.areaWidth = initX + ev.clientX - this.star1X
						this.areaInit.areaHeight = initY + ev.clientY - this.star1Y
					}
				}
			},
			// 点击选择改变跳转的url
			handleSelect(index, row, scope) {
				this.urlMessage.sign = row.shopNo
			},
			// 切换页面
			pageSizeChange(val) {
			 
			}
		}
	}
</script>

<style scoped lang="scss" ref="stylesheet/scss">
	.areaBox {
		position: absolute;
		background: rgba(#2980b9, 0.3);
		border: 0.7px dashed #34495e;
		display: flex;
		justify-content: center;
		align-items: center;
		color: #34495e;
		font-size: 14px;
		cursor: move;

		.promptText {
			overflow: hidden;
			display: inline;
			max-width: 100%;
			max-height: 100%;
			text-align: center;
		}

		.del {
			width: 10px;
			height: 10px;
			background: #bdc3c7;
			border-radius: 50%;
			position: absolute;
			right: 0;
			top: 0;
			transform: translate3d(50%, -50%, 0);
			cursor: default;
		}

		.del:hover {
			background: #ecf0f1;
		}

		.shape {
			position: absolute;
			width: 7px;
			height: 7px;
			background: transparent;
			right: 0;
			bottom: 0;
			transform: translate3d(50%, 50%, 0);
			cursor: nwse-resize;
		}

		.editBox {
			position: fixed;
			display: block;
			min-width: 100%;
			min-height: 100%;
			z-index: 999;
			cursor: default;
			background: rgba(#2c3e50, 0.8);
			top: 0;
			left: 0;
			animation: fadeIn 0.5s;

			.editCase {
				width: 50%;
				/*height: 100%;*/
				border-radius: 5px;
				overflow: hidden;
				background: #ecf0f1;
				position: absolute;
				left: 50%;
				top: 50%;
				transform: translate3d(-50%, -50%, 0);
				display: flex;
				flex-direction: column;

				.caseTitle {
					height: 30px;
					line-height: 30px;
					padding-left: 7px;
					background: #409EFF;
					color: #ecf0f1;
					position: relative;

					.close {
						width: 10px;
						height: 10px;
						border-radius: 50%;
						background: #bdc3c7;
						position: absolute;
						right: 0;
						top: 50%;
						transform: translate3d(-80%, -50%, 0);
					}

					.close:hover {
						background: #ecf0f1;
					}
				}

				.caseContent {
					flex: 1;
					display: flex;
					flex-direction: column;
					padding: 0 15px 15px 25px;

					.urlMethod {
						display: flex;
						height: 50px;
						align-items: center;

						>div {
							width: 100px;
							height: 27px;
							border: 0.7px solid #409EFF;
							text-align: center;
							line-height: 27px;
							transition: all 0.5s;
						}

						>div:first-child {
							border-right: 0;
						}

						.active {
							background: #409EFF;
							color: #ecf0f1;
						}
					}

					.selectBox {
						display: flex;
						height: 30px;
						align-items: center;

						.openWay {
							display: flex;
							justify-content: center;
							align-items: center;
							margin-left: 10px;

							.dot {
								width: 12px;
								height: 12px;
								border: 0.7px solid #409EFF;
								border-radius: 50%;
								margin-right: 3px;
							}

							.active {
								background: #409EFF;
								transition: all 0.5s;
							}
						}
					}

					.contentBox {
						flex: 1;
						animation: fadeIn 0.5s;

						.searchBox {
							display: flex;
							align-items: center;
							height: 40px;

							input {
								outline: none;
								width: 200px;
								height: 30px;
								border: 0.7px solid #409EFF;
								border-radius: 3px;
								padding-left: 5px;
							}

							.urlInput {
								width: 80%;
							}

							.btn {
								width: 70px;
								height: 30px;
								border-radius: 3px;
								background: #409EFF;
								color: #ecf0f1;
								display: flex;
								align-items: center;
								justify-content: center;
								margin-left: 10px;
							}
						}

						.handle {
							margin-bottom: 70px;
						}

						.searchList {
							margin-bottom: 30px;
							background: #fff;
						}
					}

					.btnBox {
						display: flex;
						justify-content: flex-end;

						>div {
							width: 70px;
							height: 30px;
							border-radius: 3px;
							background: #409EFF;
							color: #ecf0f1;
							display: flex;
							align-items: center;
							justify-content: center;
						}

						>div:first-child {
							margin-right: 7px;
						}
					}
				}
			}
		}
	}

	@keyframes fadeIn {
		from {
			opacity: 0
		}

		to {
			opacity: 1
		}
	}
</style>
