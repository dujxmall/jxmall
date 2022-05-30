<template>
	<view style="padding-bottom: 50rpx;">
		<block v-if="value.length>0">
			<block v-for="item in value">
				<!-- #ifdef H5 -->
				<tui-block :marginTop="item.marginTop==0?false:true">
					<component :is="'tui-'+item.name" :value="item.data"></component>
				</tui-block>
				<!-- #endif -->
				<!-- #ifdef APP-PLUS -->
				<tui-block :marginTop="item.marginTop==0?false:true">
					<component :is="'tui-'+item.name" :value="item.data"></component>
				</tui-block>
				<!-- #endif -->
				<!-- #ifdef MP-WEIXIN -->

				<tui-block :marginTop="item.marginTop==0?false:true">
					<block v-if="item.name=='blank'">
						<tui-blank :value="item.data">
						</tui-blank>
					</block>
					<block v-if="item.name=='custom-goods'">
						<tui-custom-goods :value="item.data">
						</tui-custom-goods>
					</block>
					<block v-if="item.name=='video-block'">
						<tui-video-block :value="item.data">
						</tui-video-block>
					</block>
					<block v-if="item.name=='img-group'">
						<tui-img-group :value="item.data">
						</tui-img-group>
					</block>
					<block v-if="item.name=='search-bar'">
						<tui-search-bar :value="item.data">
						</tui-search-bar>
					</block>
					<block v-if="item.name=='banner'">
						<tui-banner :value="item.data">
						</tui-banner>
					</block>
					<block v-if="item.name=='notice'">
						<tui-notice :value="item.data">
						</tui-notice>
					</block>
					<block v-if="item.name=='title-bar'">
						<tui-title-bar :value="item.data">
						</tui-title-bar>
					</block>
					<block v-if="item.name=='goods-group'">
						<tui-goods-group :value="item.data">
						</tui-goods-group>
					</block>
					<block v-if="item.name=='navbar'">
						<tui-navbar :value="item.data">
						</tui-navbar>
					</block>
					<block v-if="item.name=='pt-block'">
						<tui-pt-block :value="item.data">
						</tui-pt-block>
					</block>
					<block v-if="item.name=='hot-image'">
						<tui-hot-image :value="item.data">
						</tui-hot-image>
					</block>
					<block v-if="item.name=='float-button'">
						<tui-float-button :value="item.data">
						</tui-float-button>
					</block>
				</tui-block>
				<!-- #endif -->


			</block>

		</block>
		<view style="text-align: center;font-size: 9pt;width: 100%;color: #B2B2B2;margin-top: 40rpx;"
			v-if="show_bottom">不能再底哦~</view>
	</view>
</template>

<!-- <tui-block :marginTop="item.marginTop==0?false:true">
				<block v-if="item.name=='blank'">
					<tui-alert-adv :value="item.data"></tui-alert-adv>
				</block>

			</tui-block> -->
<script>
	import TuiBlock from '@/components/thorui/tui-block/tui-block.vue'
	import TuiBlank from '@/components/thorui/tui-blank/tui-blank'
	import TuiBanner from '@/components/thorui/tui-banner/tui-banner'
	import TuiImgGroup from '@/components/thorui/tui-img-group/tui-img-group'
	import TuiImgList from '@/components/thorui/tui-img-list/tui-img-list'
	import TuiSearchBar from '@/components/thorui/tui-search-bar/tui-search-bar'
	import TuiNotice from '@/components/thorui/tui-notice/tui-notice'
	import TuiTitleBar from '@/components/thorui/tui-title-bar/tui-title-bar'
	import TuiGoodsGroup from '@/components/thorui/tui-goods-group/tui-goods-group'
	import TuiNavbar from '@/components/thorui/tui-navbar/tui-navbar'
	import TuiVideoBlock from '@/components/thorui/tui-video-block/tui-video-block'
	import TuiPtBlock from '@/components/thorui/tui-pt-block/tui-pt-block'
	import TuiCustomGoods from '@/components/thorui/tui-custom-goods/tui-custom-goods'
	import TuiHotImage from '@/components/thorui/tui-hot-image/tui-hot-image'
	import TuiFloatButton from '@/components/thorui/tui-float-button/tui-float-button'
	export default {
		components: {
			TuiBlank,
			TuiBanner,
			TuiImgGroup,
			TuiImgList,
			TuiSearchBar,
			TuiNotice,
			TuiTitleBar,
			TuiGoodsGroup,
			TuiNavbar,
			TuiBlock,
			TuiVideoBlock,
			TuiPtBlock,
			TuiCustomGoods,
			TuiHotImage,
			TuiFloatButton
		},

		props: {
			value: {
				type: Array,
				default: () => {
					return []
				}
			},

		},
		data() {
			return {
				show_bottom: false,


			}
		},
		created() {

			setTimeout(() => {
				this.show_bottom = true;
			}, 500)

		},
		mounted() {


		},
		methods: {

		}
	}
</script>

<style>

</style>
