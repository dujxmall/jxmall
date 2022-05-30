<template>
	<!-- 签到 -->
	<view class="qian_dao">
		<view class="header dis_f_sb_c">
			<view class="left dis_f_c_c" @click.stop="date_change('last')">
				<text>上月</text>
			</view>
			<view class="center picker_btn">
				<text>{{date_info.n}}年{{date_info.y}}月</text>
				<picker mode="date" fields="month" :value="date_info.ny" :start="startDate" :end="endDate"
					@change="date_change_fun">
					<text></text>
				</picker>
			</view>
			<view class="right dis_f_c_c false" v-if="today.ny==date_info.ny">
				<text>下月</text>
			</view>
			<view class="right dis_f_c_c" @click.stop="date_change('next')" v-else>
				<text>下月</text>
			</view>
		</view>
		<view class="section">
			<!-- 周，星期 -->
			<view class="weeks dis_f_c_c">
				<view class="weeks_ul dis_f_c_c">
					<block v-for="(week,index) in weeks" :key="index">
						<view class="weeks_li dis_f_c_c box">
							<text>{{week}}</text>
						</view>
					</block>
				</view>
			</view>
			<!-- 天 -->
			<view class="days dis_f_c_c">
				<view class="ul">
					<view class="li dis_f_c_c" v-for="(li,li_index) in days" :key="li_index">
						<block v-for="(day,index) in li" :key="index">
							<!-- 上月和下月 -->
							<template v-if="day.type=='last_month'||day.type=='next_month'">
								<view class="day dis_f_c_c box" :class="[day.type]">
									<text>{{day.r}}</text>
								</view>
							</template>
							<!-- 当前月 -->
							<template v-else>
								<view class="day dis_f_co_c_c box"
									:class="{'true':day.click,'today':day.nyr==today.nyr}" @click.stop="list_fun(day)">
									<text>{{day.r}}</text>
								</view>
							</template>
						</block>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			// 签到的日期列表
			list: {
				type: Array,
				default: Array
			},
			// 当前年月YYYY-MM
			date: {
				type: String,
				default: "",
			},
			// 存放时间的key
			time_key: {
				type: String,
				default: "",
			},
		},
		data() {
			return {
				// 周，星期
				weeks: ["周一", "周二", "周三", "周四", "周五", "周六", "周天"],

				// 今天的时间信息
				today: {},
				// 当前date的时间信息
				date_info: {},

				// 当前月的列表数据
				days: [],
			};
		},
		created() {
			// 获取今天的时间信息
			this.get_today_fun();
		},
		methods: {
			// 获取当前系统时间的年月日
			get_today_fun() {
				let date = new Date();
				let n = date.getFullYear(),
					y = date.getMonth() + 1,
					r = date.getDate();
				y = this.get_str(y);
				r = this.get_str(r);
				this.today = {
					n: n,
					y: y,
					r: r,
					ny: n + "-" + y,
					nyr: n + "-" + y + "-" + r,
				}

				// 获取当前月的列表数据
				this.get_days_fun();
			},
			// 获取列表数据
			get_days_fun() {
				// 当前日期
				let date = this.date || this.today.ny;
				let date_arr = date.split("-");

				// 当前年月
				let n = date_arr[0],
					y = date_arr[1];

				// 当前date的时间信息
				this.date_info = {
					n: n,
					y: y,
					ny: date,
				}

				// 存放数据的列表
				let days = [];

				// 获取上月的数据
				days = this.get_last_month_fun(days, n, y - 1);

				// 获取这月的数据
				days = this.get_this_month_fun(days, n, y - 1);

				// 获取下月的数据
				days = this.get_next_month_fun(days, n, y - 1);

				// 对数据进行处理，七个一组
				let list = [];
				let arr = [];
				let index = 0;
				days.forEach((obj) => {
					arr.push(obj);
					index++;
					if (index == 7) {
						list.push(arr);
						arr = [];
						index = 0;
					}
				});
				this.days = list;
			},
			// 获取上月的日期
			get_last_month_fun(list_days, n, y) {
				// 获取当前月份第一天是周几
				let week = new Date(n, y, 1).getDay();
				// 如果不是周一，说明需要上月的天数补齐
				if (week != 1) {
					week = week === 0 ? 7 : week;
					// 计算需要补齐的天数
					let number = week - 1;
					// 获取上月的天数（当天数为0 js自动处理为上一月的最后一天）
					let days = y == 0 ? 31 : new Date(n, y, 0).getDate()
					y = this.get_str(y);
					for (var i = days - number + 1; i <= days; i++) {
						let r = i;
						list_days.push({
							r: r,
							type: "last_month",
						})
					}
				}
				return list_days;
			},
			// 获取这月的数据
			get_this_month_fun(list_days, n, y) {
				y = y + 1;
				// 获取这月的天数（当天数为0 js自动处理为上一月的最后一天）
				let days = new Date(n, y, 0).getDate();
				y = this.get_str(y);
				for (var i = 1; i <= days; i++) {
					let r = this.get_str(i);
					let nyr = n + "-" + y + "-" + r;
					let yr = y + "-" + r;
					let click = false;
					// 获取已经签到的列表
					let list = this.list;
					// 这天的数据
					let data = "";
					// 如果有数据
					if (list.length) {
						// 如果有符合条件的
						for (let j = 0; j < list.length; j++) {
							// 存放时间的key
							let key = this.time_key;
							let obj = list[j];
							if (key) {
								if (obj[key] == r || obj[key] == yr || obj[key] == nyr) {
									click = true;
									// 如果有key值，说明数据中需要其他字段，直接将数据赋值到这天的data中
									data = obj;
									break;
								}
							} else {
								if (obj == r || obj == yr || obj == nyr) {
									click = true;
									data = obj;
									break;
								}
							}
						}
					}
					list_days.push({
						nyr: nyr,
						yr: yr,
						r: r,
						type: "this_month",
						click: click,
						data: data,
					})
				}
				return list_days;
			},
			// 获取下月的日期
			get_next_month_fun(list_days, n, y) {
				// 获取这月的天数（当天数为0 js自动处理为上一月的最后一天）
				let days = new Date(n, y + 1, 0).getDate();
				// 获取当前月份最后一天是周几
				let week = new Date(n, y, days).getDay();
				// 如果不是周日
				if (week !== 0) {
					let number = 7 - week;
					y = y + 2;
					y = this.get_str(y);
					for (var i = 1; i <= number; i++) {
						let r = this.get_str(i);
						list_days.push({
							r: r,
							type: "next_month",
						})
					}
				}
				return list_days;
			},
			// 日期改变时触发
			date_change(type) {
				// 当前date的时间信息
				let date_info = this.date_info;
				let n = date_info.n - 0,
					y = date_info.y - 0;
				if (type == "last") {
					if (y == 1) {
						n -= 1;
						y = 12;
					} else {
						y = this.get_str(y - 1);
					}
				} else {
					if (y == 12) {
						n += 1;
						y = "01";
					} else {
						y = this.get_str(y + 1);
					}
				}
				let ny = n + "-" + y;
				this.$emit('date_change', ny);
			},
			// 日期改变时触发
			date_change_fun(e) {
				let date = e.target.value;
				this.$emit('date_change', date);
			},
			// 获取字符串，小于10的时候前面加上0
			get_str(num) {
				num = num - 0;
				return num < 10 ? '0' + num : '' + num;
			},
			getDate(type) {
				const date = new Date();
				let year = date.getFullYear();
				let month = date.getMonth() + 1;
				let day = date.getDate();

				if (type === 'start') {
					year = year - 60;
				} else if (type === 'end') {
					year = year;
				}
				month = month > 9 ? month : '0' + month;;
				day = day > 9 ? day : '0' + day;
				return `${year}-${month}-${day}`;
			},
			// 列表的点击事件
			list_fun(item) {
				if (item.nyr == this.today.nyr) {
					item.type = "today";
				}
				// 点击的时间戳
				let item_time = new Date(item.nyr.replace(/\-/g, '/')).getTime();
				// 当前的时间戳
				let active_time = new Date().getTime();
				// 只有当点击的时间戳小于或等于当前的时候才能签到
				if (item_time <= active_time) {
					this.$emit("day_change", item);
				}
			}
		},
		watch: {
			// 当前日期改变时触发
			date(date) {
				// 获取列表数据
				this.get_days_fun();
			},
			// 签到列表更新时触发
			list(list) {
				let ul = JSON.parse(JSON.stringify(this.days));
				ul.forEach((li) => {
					li.forEach((item) => {
						// 这天的数据
						let data = "";
						let click = false;
						// 如果有数据
						if (list.length) {
							// 如果有符合条件的
							for (let j = 0; j < list.length; j++) {
								// 存放时间的key
								let key = this.time_key;
								let obj = list[j];
								if (key) {
									if (obj[key] == item.r || obj[key] == item.yr || obj[key] == item
										.nyr) {
										click = true;
										// 如果有key值，说明数据中需要其他字段，直接将数据赋值到这天的data中
										data = obj;
										break;
									}
								} else {
									if (obj == item.r || obj == item.yr || obj == item.nyr) {
										click = true;
										data = obj;
										break;
									}
								}
							}
						}
						item.click = click;
						item.data = data;
					})
				});
				this.days = ul;
			},
		},
		computed: {
			startDate() {
				return this.getDate('start');
			},
			endDate() {
				return this.getDate('end');
			}
		},
	}
</script>

<style lang="scss" scoped>
	.box {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}

	.dis_f_sb_c {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.dis_f_c_c {
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.dis_f_co_c_c {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}

	/* 将picker作为隐藏按钮的样式 */
	.picker_btn {
		position: relative;
	}

	.picker_btn picker {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: 0;
	}

	.picker_btn picker text,
	.picker_btn picker view {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}

	.qian_dao {
		.header {
			height: 90rpx;

			.left,
			.right {
				font-size: 30rpx;
				height: 100%;
				padding: 0 30rpx;

				&.false {
					color: #ccc;
				}
			}

			.center {
				font-size: 36rpx;
				font-weight: bold;
				color: #333;
			}
		}

		.section {
			.weeks {
				.weeks_ul {
					border-right: 1px solid #f0f0f0;

					.weeks_li {
						width: 100rpx;
						font-size: 24upx;
						color: #333;
						height: 70rpx;
						border-top: 1px solid #f0f0f0;
						border-left: 1px solid #f0f0f0;

						&:last-child {
							border-right: none;
						}
					}
				}
			}

			.days {
				.ul {
					border-right: 1px solid #f0f0f0;
					border-bottom: 1px solid #f0f0f0;

					.day {
						width: 100rpx;
						height: 100rpx;
						font-size: 26upx;
						color: #323232;
						position: relative;
						border-top: 1px solid #f0f0f0;
						border-left: 1px solid #f0f0f0;

						&.last_month,
						&.next_month {
							color: #ccc;
						}

						&.today {
							color: red;
							background: #F6F6C0;
						}

						&.true {
							background: #FEDC41;
							color: #fff;
						}
					}
				}
			}
		}
	}
</style>
