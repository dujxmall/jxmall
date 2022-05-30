<template>
	<view>
		<picker :value="value" mode="multiSelector" @change="confirm" @columnchange="columnPicker" :range="multiArray">
			<slot></slot>
		</picker>

	</view>
</template>

<script>
	export default {
		data() {
			return {
				selectList: [], //接口返回picker数据,此处就直接使用本地测试数据
				multiArray: [], //picker数据
				value: [0, 0, 0],
			}
		},
		created: function(e) {
			let address_data = uni.getStorageSync('AREA_DATA');
			if (address_data) {
				this.selectList = address_data;
				this.multiArray = [
					this.toArr(this.selectList),
					this.toArr(this.selectList[0].list),
					this.toArr(this.selectList[0].list[0].list),
				]

				console.log(this.multiArray);
				return;
			}

			this.$request({
				url: '/common/address/area-data'
			}).then(res => {
				if (res.code == 0) {
					this.selectList = res.data.list;
					//#ifndef MP
					uni.setStorageSync('AREA_DATA', res.data.list);
					//#endif
					this.multiArray = [
						this.toArr(this.selectList),
						this.toArr(this.selectList[0].list),
						this.toArr(this.selectList[0].list[0].list),

					]

				}
			})
		},

		methods: {
			columnPicker: function(e) {
				//第几列 下标从0开始
				let column = e.detail.column;
				//第几行 下标从0开始
				let value = e.detail.value;
				if (column === 0) {
					this.multiArray = [
						this.multiArray[0],
						this.toArr(this.selectList[value].list),
						this.toArr(this.selectList[value].list[0].list),
					];
					this.value = [value, 0, 0]
				}
				if (column === 1) {
					this.multiArray = [
						this.multiArray[0],
						this.multiArray[1],
						this.toArr(this.selectList[this.value[0]].list[value].list),

					];
					this.value = [this.value[0], value]
				}
				if (column === 2) {
					this.multiArray = [
						this.multiArray[0],
						this.multiArray[1],
						this.multiArray[2],

					];
					this.value = [this.value[0], this.value[1], value]
				}
				/* if (column === 3) {
					this.value = [this.value[0], this.value[1], this.value[2], value]
				} */
			},
			confirm: function(e) {
				let value = e.detail.value;
				if (this.selectList.length > 0) {
					let provice = this.selectList[value[0]].name
					let city = this.selectList[value[0]].list[value[1]].name
					let county = this.selectList[value[0]].list[value[1]].list[value[2]].name

					let provice_code = this.selectList[value[0]].code
					let city_code = this.selectList[value[0]].list[value[1]].code
					let county_code = this.selectList[value[0]].list[value[1]].list[value[2]].code

					let area = provice + " " + city + " " + county;
					let selectedData = {
						area: area,
						province_name: provice,
						city_name: city,
						county_name: county,
						province_code: provice_code,
						city_code: city_code,
						county_code: county_code,
					}
					this.$emit('selected', selectedData)
				}
			},
			toArr(object) {
				let arr = [];
				for (let i in object) {
					arr.push(object[i].name);
				}
				return arr;
			},
		}
	}
</script>

<style>

</style>
