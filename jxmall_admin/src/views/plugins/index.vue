<template>
	<div class="app-container" v-loading="loading">
		<el-card>
			<div class="backgroud-color:#fff">
				<div style="height: 110px;width: 100%;padding: 0 40px;background-color: #FFF;" class="flex-x-between">
					<div>
						<div style="font-size: 25px;">应用</div>
						<div>助力商城运营</div>
					</div>
					<div>
						<el-form :inline="true" ref="searchForm">
							<el-form-item><el-input v-model="keyword" placeholder="搜索应用"></el-input></el-form-item>
							<el-form-item><el-button type="primary"  @click="getList">搜索</el-button></el-form-item>
						</el-form>
					</div>
				</div>

				<div style="margin-top: 20px;">
					<div class="plugin-group flex-row" style="display: flex;" v-if="list.length"
						v-for="(group,i) in list">
						<div class="list" style="align-items: flex-start;">
							<div class="box">
								<el-image style="position: absolute;left: 0;top:0;height: 100%;width: 100%; "
									:src="group.plugin_pic">
								</el-image>
								<div style="position: absolute;left: 0;top:0;color: #FFFFFF;width: 100%;height: 100%;">
									<div style="padding: 20px;">
										<div style="font-size: 25px;">{{group.group_name}} </div>
										<div style="padding-top: 10px;">{{group.description}}</div>
									</div>
								</div>
							</div>
						</div>
						<div class="list" v-if="group.list">
							<div class="box" v-for="plugin in group.list" @click="enterPlugin(plugin)">
								<div class="flex-y-center plugin-item"
									style="height: 100%;width: 100%;padding: 0 10px;">
									<el-image style="height:70px;width: 70px" :src="plugin.logo">
									</el-image>
									<div style="color: #000;">
										<div style="margin-left: 10px;box-sizing: border-box;">
											<div style="font-size: 18px;line-height: 40px;" class="flex-row">
												<div>{{plugin.label}}</div>
											</div>
											<div style="padding-top: 10px;color:#636669;font-size: 10px">
												{{plugin.description}}
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</el-card>
	</div>
</template>

<script>
	export default {
		name: 'plugins-index',
		components: {

		},
		data() {
			return {
				mall: null,
				showEdit: false,
				list: [],
				keyword: '',
				loading: false,
				pagination: null,
				page: 1,
				refresh_cache: 0,
			}
		},
		mounted() {
			this.getList()
		},
		methods: {
			refreshCache() {
				this.refresh_cache = 1;
				this.getList();
			},
			enterPlugin(row) {
			 
				if(!row.path){
					this.$go.push('/plugins/' + row.name + '/index');
				}else{
					this.$go.push('/plugins/' + row.name + '/' + row.path);
				}
			},
			getList() {
				this.loading = true
				this.$request({
					url: '/mall/plugin/index',
					data: {
						keyword: this.keyword
					}
				}).then(res => {
					this.refresh_cache = 0;
					this.loading = false
					if (res.code == 0) {
						this.list = res.data.list
					}
				})
			},
		}
	}
</script>

<style lang="scss" scoped="scoped">
	.container {
		width: 80%;

		margin: 0 auto;
	}

	.plugin-group {
		.list {

			flex-wrap: wrap;
			display: flex;
			align-items: center;
		}

		.box {
			border-radius: 5px;
			overflow: hidden;
			background-color: #FFFFFF;
			position: relative;
			height: 100px;
			width: 285px;
			margin-right: 20px;
			margin-bottom: 20px;
			box-shadow: 0 0 10px 10px #f4f4f5;
		}

		.plugin-item:hover {
			cursor: pointer;

		}

		.group-name {}

		.plugin-list {}

	}
</style>
