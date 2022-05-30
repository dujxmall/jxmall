<template>

	<div class="container">
		<div class="diy-component-preview">
			<div class="content">

				<div v-if="data.list.length==0"
					style="color: #b2b2b2;font-size: 14px;text-align: center;height: 60px;line-height: 60px;">
					请添加商品
				</div>
				<div class="scorll-div" style="overflow: auto;width: 100%;" v-if="data.type==1">
					<ul
						style="flex-wrap: nowrap;display: flex;overflow: auto;min-width: 100%;padding: 0;margin: 0;width: 100%;align-content: flex-start;">
						<li v-if="data.list.length" v-for="(goods,index) in data.list">
							<div class="goods-item" :style="goodsStyle">
								<div class="goods-item-box">
									<el-image :src="goods.cover_pic" alt="" :style="goodsHeight" style="width: 100%;"
										fit="none">
									</el-image>
									<div style="padding: 5px;">
										<div style="height: 90px;">
											<div class="goods-title">{{goods.name}}</div>
										</div>
										<div class="flex-x-between bottom-bar" style="">
											<div style="font-weight: bold;" :style="{'color':data.price_color}"
												v-if='data.show_price'>
												<span v-if="goods.is_negotiable==1">面议</span><span
													v-else>{{goods.price}}</span>
											</div>
											<div class="flex-x-center flex-y-center"
												v-if="data.cart_type==1&&data.show_buy_btn==1">
												<el-image style="width: 40px;height: 40px;" :src="data.cart_pic">
													<div slot="error" class="image-slot">
														<i class="el-icon-picture-outline"></i>
													</div>
												</el-image>
											</div>
											<div class="flex-x-center flex-y-center buy-btn"
												:style="{'border-color':data.cart_color,'border-radius':data.is_circle?'20px':'5px'}"
												v-if="data.cart_type==0&&data.show_buy_btn==1">
												<span :style="{'color':data.cart_color}">{{data.cart_text}}</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
				<div style="width: 100%;flex-wrap: wrap;display: flex;" v-if="data.type==0">
					<div class="goods-item" v-if="data.list.length" v-for="(goods,index) in data.list"
						:style="goodsStyle">
						<div class="goods-item-box">
							<el-image :src="goods.cover_pic" alt="" :style="goodsHeight" style="width: 100%;"
								fit="fill">
							</el-image>
							<div style="padding: 5px;">
								<div style="height: 90px;">
									<div class="goods-title">{{goods.name}}</div>
								</div>
								<div class="flex-x-between bottom-bar" style="">
									<div style="font-weight: bold;" :style="{'color':data.price_color}"
										v-if='data.show_price'><span v-if="goods.is_negotiable==1">面议</span><span
											v-else>{{goods.price}}</span></div>
									<div class="flex-x-center flex-y-center"
										v-if="data.cart_type==1&&data.show_buy_btn==1">
										<el-image style="width: 40px;height: 40px;" :src="data.cart_pic">
											<div slot="error" class="image-slot">
												<i class="el-icon-picture-outline"></i>
											</div>
										</el-image>
									</div>
									<div class="flex-x-center flex-y-center buy-btn"
										:style="{'border-color':data.cart_color,'border-radius':data.is_circle?'20px':'5px'}"
										v-if="data.cart_type==0&&data.show_buy_btn==1">
										<span :style="{'color':data.cart_color}">{{data.cart_text}}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="diy-component-edit">
			<el-form label-width="100px">
				<el-form-item label="显示价格">
					<el-radio-group v-model="data.show_price">
						<el-radio :label="0">隐藏</el-radio>
						<el-radio :label="1">显示</el-radio>
					</el-radio-group>
				</el-form-item>
				<el-form-item label="显示购物按钮">
					<el-radio-group v-model="data.show_buy_btn">
						<el-radio :label="0">隐藏</el-radio>
						<el-radio :label="1">显示</el-radio>
					</el-radio-group>
				</el-form-item>
				<el-form-item label="商品图片高度">
					<el-slider show-input :max="2048" v-model="data.cover_pic_height"></el-slider>
				</el-form-item>
				<el-form-item label="购物按钮样式">
					<el-radio-group v-model="data.is_circle">
						<el-radio :label="0">正常</el-radio>
						<el-radio :label="1">圆角</el-radio>
					</el-radio-group>
				</el-form-item>
				<el-form-item label="价格文字颜色">
					<el-color-picker v-model="data.price_color"></el-color-picker>
				</el-form-item>
				<el-form-item label="显示样式">
					<el-radio-group v-model="data.type">
						<el-radio :label="0">正常</el-radio>
						<el-radio :label="1">滚动</el-radio>
					</el-radio-group>
				</el-form-item>
				<el-form-item label="购物车样式">
					<el-radio-group v-model="data.cart_type">
						<el-radio :label="0">文字</el-radio>
						<el-radio :label="1">图标</el-radio>
					</el-radio-group>
				</el-form-item>
				<el-form-item label="购物车文字颜色">
					<el-color-picker v-model="data.cart_color"></el-color-picker>
				</el-form-item>

				<el-form-item label="购物文字">
					<el-input type="text" v-model="data.cart_text"></el-input>
				</el-form-item>

				<el-form-item label="选择图标" v-if="data.cart_type==1">
					<!-- data.cart_pic -->
					<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
						v-if="data.cart_pic">
						<img :src="data.cart_pic" alt="" style="width: 100%;height: 100%;">
						<span class="flex-x-center flex-y-center pic-del" @click="deleteCartPic"> <i
								class="el-icon-close"></i></span>
					</div>
					<file-picker v-if="!data.cart_pic" style="margin-right: 5px;" v-model="data.cart_pic" width="88"
						height="88">
						<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center">
							<i class="el-icon-upload"></i>
						</div>
					</file-picker>
				</el-form-item>
				<el-form-item label="每行数量">
					<el-radio-group v-model="data.columns">
						<el-radio :label="1">1</el-radio>
						<el-radio :label="2">2</el-radio>
						<el-radio :label="3">3</el-radio>
					</el-radio-group>
				</el-form-item>
				<el-form-item label="选择商品">
					<div v-for="(item,index) in data.list" :key="index" class="edit-nav-item"
						style="position: relative;">
						<div class="nav-edit-options" style="text-align: end;position: absolute;right: -50px;">
							<el-button @click="itemDelete(index)" size="mini" type="primary" icon="el-icon-delete"
								style="top: -6px;right: -31px;"></el-button>
						</div>
						<div flex="dir:left box:first cross:center">
							<div>
								<div flex="main:center cross:center" class="add-image-btn flex-x-center flex-y-center"
									v-if="item.cover_pic">
									<img :src="item.cover_pic" alt="" style="width: 100%;height: 100%;">
									<span class="flex-x-center flex-y-center pic-del"> <i
											class="el-icon-close"></i></span>
								</div>
								<file-picker v-if="!item.cover_pic" style="margin-right: 5px;" v-model="item.cover_pic"
									width="88" height="88">
									<div flex="main:center cross:center"
										class="add-image-btn flex-x-center flex-y-center">
										<i class="el-icon-upload"></i>
									</div>
								</file-picker>
							</div>

							<div style="margin-top: 10px;">
								<el-input v-model="item.name" placeholder="商品名称" size="small">

								</el-input>
							</div>
							<div style="margin-top: 10px;">
								<el-input v-model="item.price" placeholder="商品价格" size="small">

								</el-input>
							</div>

							<div style="margin-top: 10px;">
								<div>
									<el-input v-model="item.url" placeholder="点击选择链接" size="small">
										<el-button slot="append" @click="selectLinkClick(index)">选择链接</el-button>
									</el-input>
									<div style="padding-left: 10px;color: #2B2B2B;">如需加入参数，请自行加入参数值</div>
								</div>
							</div>
						</div>
					</div>
					<div style="width: 100%;text-align: center;">
						<el-button type="text" @click="addItem">+添加项目</el-button>
					</div>
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
		name: 'CustomGoods',
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
					cover_pic_height: '375',
					bgColor: '',
					type: 0,
					columns: 2,
					cart_pic: '',
					cart_text: '购买',
					cart_type: 0,
					list: [{
						cover_pic: '',
						name: '',
						price: '',
						url: '',
					}],

					show_price: 1,
					show_buy_btn: 1,
					cart_color: '#FF3C29',
					is_circle: 0,
					price_color: '#FF3C29'
				},
				dialogTableVisible: false,
				page: 1,
				item: {
					cover_pic: '',
					name: '',
					price: '',
					url: ''
				}
			}
		},
		computed: {

			goodsStyle() {
				let width;

				if (this.data.type == 0) {
					width = `width:${100 / this.data.columns}%;`;
				}
				if (this.data.type == 1) {
					if (this.data.columns == 1) {
						width = `width:600px`;
					}
					if (this.data.columns == 2) {
						width = `width:300px`;
					}
					if (this.data.columns == 3) {
						width = `width:250px`;
					}
				}
				return width;
			},
			goodsHeight() {


				return `height:${this.data.cover_pic_height}px`;
				if (this.data.type == 0) {
					if (this.data.columns == 1) {
						return `height:344px`;
					}
					if (this.data.columns == 2) {
						return `height:344px`;
					}
					if (this.data.columns == 3) {
						return `height:224px`;
					}
				}
				if (this.data.type == 1) {
					if (this.data.columns == 1) {
						return `height:344px`;
					}
					if (this.data.columns == 2) {
						return `height:300px`;
					}
					if (this.data.columns == 3) {
						return `height:200px`;
					}
				}

			},
			buyBtn() {
				if (this.data.type == 0) {
					if (this.data.columns == 1) {

					}
					if (this.data.columns == 2) {

					}
					if (this.data.columns == 3) {

					}
				}
				if (this.data.type == 1) {
					if (this.data.columns == 1) {
						return `padding:5px 10px`;
					}
					if (this.data.columns == 2) {
						return `padding:5px`;
					}
					if (this.data.columns == 3) {
						return `padding:0 2px;`;
					}
				}

			},
		},
		created() {
			if (!this.value) {
				this.$emit('input', this.data)
			} else {
				this.data = this.value;
				this.data.columns = parseInt(this.value.columns)
				this.data.is_circle = parseInt(this.value.is_circle)
				this.data.show_price = parseInt(this.value.show_price)
				this.data.show_buy_btn = parseInt(this.value.show_buy_btn)
				this.data.cart_type = parseInt(this.value.cart_type)
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

			selectLinkClick(index) {
				this.currentEditIndex = index;

				this.showLink = true;
		 
			},
			itemDelete(index) {
				this.data.list.splice(index, 1)
			},
			addItem() {
			 
				this.data.list.push(this.item)
			},
			goodsDelete(index) {
				this.data.list.splice(index, 1)
			},
			selectedGoods(e) {
				if (this.data.list.length == 0) {
					this.data.list = (e)
				} else {
					for (let i in e) {
						this.data.list.push(e[i])
					}
				}
			},
			deleteCartPic() {
				this.data.cart_pic = ''
			},
			linkSelected(e) {
				this.showLink = false;
				this.data.list[this.currentEditIndex].url = e.url;
			},
		}
	}
</script>

<style lang="scss">
	.scorll-div::-webkit-scrollbar {
		display: none;
	}

	.el-scrollbar .el-scrollbar__wrap .el-scrollbar__view {
		white-space: nowrap;
	}

	li {
		list-style: none;
	}

	.container {
		.edit-item {
			border: 1px solid #e2e2e2;
			line-height: normal;
			padding: 5px;
			margin-bottom: 5px;
			max-width: 450px;
		}
	}

	.goods-title {
		line-height: 40px;
		padding: 0 8px;
		margin: 8px 0 4px;
		overflow: hidden;
		text-overflow: ellipsis;
		font-size: 28px;
		-webkit-line-clamp: 2;
		height: 80px;
		-webkit-box-orient: vertical;
		white-space: normal;
		display: -webkit-box;
	}

	.buy-btn {

		border: solid #FF3C29 2px;
		padding: 5px 10px;
		border-radius: 5px;
		font-size: 14px;
	}

	.bottom-bar {
		color: #FF3C29;
		padding-bottom: 10px;
		padding: 0 10px 10px 10px;
	}

	.goods-item {
		display: inline-block;
		padding: 6px;

	}

	.goods-item-box {
		width: 100%;
		height: 100%;
		margin-bottom: 10px;
		border-radius: 10px;
		overflow: hidden;
		background-color: #FFFFFF;

		img {
			width: 100%;



		}
	}
</style>
