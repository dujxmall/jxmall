<template>
	<view>

		<!-- 红包封面 -->
		<view class="rbag_model" v-if="rbagmodelshow" @touchmove.prevent.stop>
			<view class="rbag_con hidden">
				<view class="rbag_top">
					<view class="rbag_top_info">
						<image class="rbag_logo" :src="logo" mode=""></image>
						<view class="app_name">{{sub_title}}</view>
						<view class="rbag_tips">{{title}}</view>
					</view>
				</view>
				<view class="open_rbag_btn" :animation="openbrnanimation" @click="openbtn()">開</view>
			</view>
			<view class="rbag_con">
				<view class="hide_btn" @click.stop="hidebtn">
					<icon type="cancel" color="#fbd977" size="28" />
				</view>
			</view>
		</view>

		<!-- 打开红包 -->
		<view class="open_rbag_model" v-if="openrbagmodelshow" @touchmove.prevent.stop>
			<image class="rbag_conbg" src="/static/icon/openrbag.png"></image>
			<view class="rbag_conbg open_rbag_con">
				<view class="open_title">— 恭喜您获得 —</view>
				<view class="rbag_detail">
					<view class="open_money">
						<countup :num="num" color="#c95948" width='21' height='34' fontSize='34'></countup>
						<view class="danwei">元</view>
					</view>
					<view class="open_tips">已存入账户，可直接提现</view>
				</view>
				<view class="lookbag_box">
					<view class="lookbag_btn">
						<view class="text" @click.stop="lookbagbrn()">查看我的账户</view>
					</view>
				</view>
				<view class="hide_btn" @click.stop="hideopenbtn()">
					<icon type="cancel" color="#fbd977" size="28" />
				</view>
			</view>
		</view>


	</view>
</template>

<script>
	// 应用数字滚动插件
	import countup from '@/components/countUp.vue';
	export default {
		props: {
			rbagmodelshow: {
				type: Boolean,
				default: false
			},
			num: {
				type: [Number, String],
				default: '0.00'
			},
			logo: {
				type: String,
				default: ''
			},
			title: {
				type: String,
				default: '立即开红包'
			},
			sub_title: {
				type: String,
				default: '恭喜发财'
			}
		},
		data() {
			return {
				show: false,
				bag1animation: {},
				openbrnanimation: {},

				openrbagmodelshow: false,
			};
		},
		mounted() {
			this.show = this.rbagmodelshow

		},
		created: function() {


		},
		methods: {
			// 侧边红包 => 动画
			// 侧边红包 => 点击

			// 红包封面 => 关闭按钮
			hidebtn: function() {
				this.show = false;
				this.$emit('close');
			},
			// 红包封面 => 開红包按钮
			openbtn: function() {
				var that = this;
				var animation = uni.createAnimation({
					duration: 1000,
					timingFunction: 'ease',
				})
				that.openbrnanimation = animation;
				// animation.rotate3d(0,1,0,360).step();
				animation.rotateY(360).step();
				that.openbrnanimation = animation.export();
				setTimeout(function() {
					that.rbagmodelshow = false;
					that.openrbagmodelshow = true;
					that.openbrnanimation = {};
				}, 1000);
			},

			// 打开红包  => 关闭按钮
			hideopenbtn: function() {
				this.openrbagmodelshow = false;
			},
			// 打开红包  => 查看钱包
			lookbagbrn: function() {
				uni.redirectTo({
					url: "/pages/manage/manage"
				})
			}
		},
		components: {
			countup
		},
	}
</script>

<style lang="scss">
	@import 'scss/red_bag.scss';
</style>
