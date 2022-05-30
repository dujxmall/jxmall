<template>
	<div class="app-container">
		<el-card class="box-card" v-loading="is_loading">
			<div slot="header" class="clearfix">
				<span>插件列表</span>
			</div>
			<div>
				<el-table v-loading="loading" :data="list" border style="width: 100%">
					<el-table-column label="logo">
						<template slot-scope='scope'>
							<div class="flex-y-center">
								<el-avatar :size="50" :src="scope.row.logo"></el-avatar>
								<file-picker v-if="scope.row.status==1" :multiple='false' @selected="logoPic" v-model="scope.row.logo">
									<el-button type="text" style="margin: 0 10px;" @click="changeLogo(scope.$index)">更换logo</el-button>
								</file-picker>
							</div>
						</template>
					</el-table-column>
					<el-table-column prop="label" label="插件名称">
					</el-table-column>
					<el-table-column label="版本号">
						<template slot-scope="scope">
							<div v-if="scope.row.is_local==1">
								<div v-if="scope.row.is_update==1">{{scope.row.local_version}}
									<el-button type="text" @click="update(scope.row.name)">发现更新（版本号：{{scope.row.version}}）</el-button>
								</div>
								<div v-if="scope.row.is_update==0">{{scope.row.local_version}}</div>
							</div>
							<div v-if="scope.row.is_local==0">
								<div>{{scope.row.version}}</div>
							</div>
						</template>
					</el-table-column>
					<el-table-column prop="dsc" label="描述">
					</el-table-column>
					<el-table-column label="状态">
						<template slot-scope="scope">
							<el-tag type="success" v-if="scope.row.status==1">已安装</el-tag>
							<el-tag v-if="scope.row.status==0">未安装</el-tag>
						</template>
					</el-table-column>
					<el-table-column label="操作">
						<template slot-scope="scope">
							<el-button type="text" size="small" @click="install(scope.row)" v-if="scope.row.status==0">安装</el-button>
							<el-button type="text" size="small" @click="uninstall(scope.row)" v-if="scope.row.status==1">卸载</el-button>
						</template>
					</el-table-column>
				</el-table>

			</div>
		</el-card>
	</div>
</template>

<script>

	export default {
		name: 'index',
		components: {

		},
		data() {
			return {
				is_loading: false,
				is_site: 1,
				mall: null,
				showEdit: false,
				list: [],
				loading: false,
				pagination: null,
				page: 1,
				logo_index: -1,
			}
		},
		mounted() {
			this.getList()
		},
		methods: {
			update(plugin) {

				this.$confirm('确定更新', '提示').then(res => {
					this.is_loading = true;
					this.$request({
						url: '/admin/plugin/update',
						data: {
							name: plugin
						},
					}).then(res => {
						this.is_loading = false;
						if (res.code == 0) {
							this.$message.success(res.msg)
							this.getList();

						}
					})
				})
			},
			install(row) {

				this.$request({
					url: "/admin/plugin/install",
					data: {
						name: row.name
					},
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						this.$message.success(res.msg);
						this.getList();
					}

				})
			},
			uninstall(row) {
				this.$confirm('确定卸载插件：' + row.label, '提示').then(() => {
					this.$request({
						url: "/admin/plugin/uninstall",
						data: {
							name: row.name
						},
						method: 'post'
					}).then(res => {
						if (res.code == 0) {
							this.$message.success(res.msg);
							this.getList();
						}

					})

				})

			},
			logoPic(e) {
				if (e.length == 0) {
					return;
				}

				let plugin = this.list[this.logo_index];
				this.$request({
					url: "/admin/plugin/logo",
					data: {
						name: plugin.name,
						logo: e[0].url
					},
					method: 'post'
				}).then(res => {
					if (res.code == 0) {
						this.$message.success(res.msg);
					}
				})
			},
			changeLogo(index) {
				this.logo_index = index
			},
			refresh() {
				this.getList();
			},
			pageChange(e) {
				this.page = e
				this.getList()
			},
			getList() {
				this.loading = true
				this.$request({
					url: '/admin/plugin/list',
					data: {
						page: this.page
					}
				}).then(res => {
					this.loading = false
					if (res.code == 0) {
						this.list = res.data.list
					}
				}).catch(res => {
					this.loading = false;
				})
			},
			addMall() {
				this.showEdit = true
			},
			cancel() {
				this.mall = null;
				this.showEdit = !this.showEdit;
			},
			btnClick() {
				this.$router.replace({
					path: '/admin/mall',
				})
			}
		}
	}
</script>

<style>
</style>
