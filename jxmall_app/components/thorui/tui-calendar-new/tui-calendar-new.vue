<template>
	<view class="content" style="padding: 20rpx;background-color: #FFFFFF;">
		<view class="flex-y-center flex-x-center" style="height: 100rpx;">
			<view style="padding: 0 30rpx;" @click="monthChange(0)">
				<tui-icon name="arrowleft"></tui-icon>
			</view>
			<view class="month-title">
				{{year}}年{{month}}月
			</view>
			<view style="padding: 0 30rpx;" @click="monthChange(1)">
				<tui-icon name="arrowright"></tui-icon>
			</view>
		</view>
		<view class="flex-x-between">
			<view class="item-width tui-color-grey">日</view>
			<view class="item-width">一</view>
			<view class="item-width">二</view>
			<view class="item-width">三</view>
			<view class="item-width">四</view>
			<view class="item-width">五</view>
			<view class="item-width">六</view>
		</view>
		<view v-for="week in  week_list" class="flex-x-between week-group">
			<view style="text-align: center;width: 100rpx;" class="flex-y-center" v-for="item in week">
				<view v-if="item.date!=''" style="width: 100rpx;height: 100rpx;"
					:style="{color:item.is_disable?'#b2b2b2':select_date==item.full_date?active_color:default_color}"
					:class="select_date==item.full_date?'active':''" class="flex-y-center flex-x-center"
					@click="dateClick(item)">
					<view>
						<view class="flex-x-center date-item">{{item.date}}
						</view>
						<view class="flex-x-center date-item" style="font-size:10rpx;"
							:style="{color:item.full_date==select_date?other_value_active_color:other_value_color}">
							{{item.value}}
						</view>
					</view>
				</view>
				<view v-else>
					<view class="date-item"> </view>
				</view>
			</view>
		</view>
	</view>
</template>
<script>
	export default {
		name: 'tuiCalendarNew',
		props: {
			start_at: {
				type: String,
				default: ''
			},
			end_at: {
				type: String,
				default: ''
			},
			active_color: {
				type: String,
				default: '#ff455'
			},
			other_value_color: {
				type: String,
				default: '#ff455'
			},
			other_value_active_color: {
				type: String,
				default: '#ff455'
			},
			default_color: {
				type: String,
				default: '#000'
			},
			is_date_limit: {
				type: Boolean,
				default: false
			},
			default_date: {
				type: String,
				default: ''
			},
			default_extra_value: {
				type: String,
				default: ''
			},
			default_extra_data: {
				type: Array,
				default: () => {
					return []
				}
			},
		},
		data() {
			return {
				title: 'Hello',
				date_list: [],
				year: '',
				month: '',
				week_list: [],
				select_date: '',
			}
		},
		created() {
			var date = new Date();
			this.year = date.getFullYear();
			this.month = date.getMonth() + 1;
			this.select_date = this.default_date;
			this.initDate();
		},
		methods: {
			dateClick(row) {
				if (row.is_disable) {
					return;
				}
				this.select_date = row.full_date;
				this.$emit('change', row)
			},
			monthChange(type) {
				if (type == 0) {
					this.month--;
				} else {
					if (this.month == 12) {
						this.year++;
						this.month = 1;
					} else {
						this.month++;
					}
				}
				this.date_list = [];
				this.initDate();
			},

			getDateExtraVlue(date) {
				for (var i = 0; i < this.default_extra_data.length; i++) {
					let item = this.default_extra_data[i];
					if (item.date == date) {
						return item.value;
					}
				}
				return this.default_extra_value;
			},
			initDate() {
				let days = this.getMonthDays(this.year, this.month)
				for (var i = 1; i <= days; i++) {
					let date = i;
					if (i < 10) {
						date = '0' + i;
					}
					let month = this.month;
					if (this.month < 10) {
						month = '0' + month;
					}
					let full_date = this.year + '-' + month + '-' + date;
					let value = this.getDateExtraVlue(full_date);
					let is_disable = false;
					if (this.is_date_limit && this.start_at && this.end_at) {
						is_disable = this.disableDate(full_date);
					}
					this.date_list.push({
						date: i,
						full_date: full_date,
						value: value,
						is_disable: is_disable
					})
				}
				let full_date = this.year + '-' + this.month + '-' + this.date_list[0].date;
				let weekDate = this.getWeekDate(full_date);
				for (var i = 0; i < weekDate; i++) {
					this.date_list.unshift({
						date: '',
					})
				}
				let week_count = this.getWeeks(this.year, this.month);
				this.week_list = this.split_array(this.date_list, 7);
				let lastWeek = this.week_list[this.week_list.length - 1];
				if (lastWeek.length < 7) {
					let cut = 7 - lastWeek.length;
					for (var i = 0; i < cut; i++) {
						lastWeek.push({
							date: ''
						})
					}
					this.week_list[this.week_list.length - 1] = lastWeek;
				}
			},
			disableDate(date) {
				if (this.start_at && !this.end_at) {
					let start_at = Date.parse(this.start_at);
					let cur_date = Date.parse(date);
					if (cur_date >= start_at) {
						return false
					}
				} else {
					let start_at = Date.parse(this.start_at);
					let end_at = Date.parse(this.end_at);
					let cur_date = Date.parse(date);
					if (cur_date >= start_at && cur_date <= end_at) {
						return false
					}
				}
				return true;
			},
			split_array(arr, len) {
				var a_len = arr.length;
				var result = [];
				for (var i = 0; i < a_len; i += len) {
					result.push(arr.slice(i, i + len));
				}
				return result;
			},
			getWeeks(year, month) {
				var d = new Date();
				// 该月第一天
				d.setFullYear(year, month - 1, 1);
				var w1 = d.getDay();
				if (w1 == 0) w1 = 7;
				// 该月天数
				d.setFullYear(year, month, 0);
				var dd = d.getDate();
				// 第一个周一
				let d1;
				if (w1 != 1) d1 = 7 - w1 + 2;
				else d1 = 1;
				let week_count = Math.ceil((dd - d1 + 1) / 7);
				return week_count;
			},
			getWeekDate(date) {
				let a = new Array("日", "一", "二", "三", "四", "五", "六");
				var week = new Date(date).getDay();
				return week;
			},
			getMonthDays(year, month) {
				let days = [31, 28, 31, 30, 31, 30, 31, 30, 30, 31, 30, 31]
				if ((year % 4 === 0) && (year % 100 !== 0 || year % 400 === 0)) {
					days[1] = 29
				}
				return days[month - 1]
			}

		}
	}
</script>

<style>
	.item-width {
		text-align: center;
		width: 80rpx;

	}

	.date-item {

		font-size: 11pt;
		text-align: center;
		width: 100%;
	}

	.week-group {
		text-align: center;
		text-align: center;
		height: 120rpx;
		padding-top: 20rpx;
	}

	.month-title {
		width: 250rpx;
		text-align: center;
		font-weight: bold;
		font-size: 12pt;
	}

	.active {
		background: #f0ecec;
		font-weight: bold;
	}
</style>
