<template>
	<view>
		<tui-calendar-new start_at="2021-09-15" end_at="2021-10-02" active_color="#ff55ff" :is_date_limit="true"
			other_value_color="#ff55ff" other_value_active_color="#13ff26" default_extra_value='9.00'
			:default_extra_data='defaultExtraData' default_date="2021-09-15" @change="change"></tui-calendar-new>
		<tui-calendar></tui-calendar>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				defaultExtraData: [{
					date: '2021-09-21',
					value: '500.00'
				}],
			}
		},
		methods: {
			change(e) {
				console.log(e);
			},
		}
	}
</script>

<style lang="scss">
	.main {
		background-color: $uni-color-primary;
		width: 100px;
		height: 100px;
	}
</style>
