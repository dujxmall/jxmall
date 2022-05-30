<template>
	<div class="com-file">
		<el-dialog class="com-file-dialog" :title="title ? title : '选择文件'" :visible.sync="dialogVisible"
			:close-on-click-modal="false" :width="simple?'25%':'65%'" top="10vh" append-to-body @opened="dialogOpened">
			<template v-if="simple">
				<file-upload v-loading="uploading" class="com-file-simple-upload flex-x-center flex-y-center"
					:disabled="uploading" :multiple="true" :max="10" :params="uploadParams" :fields="uploadFields"
					:accept="accept" flex="main:center cross:center" @start="handleStart" @success="handleSuccess"
					@complete="handleComplete" @fail="handleFail" :is_site="is_site">
					<div v-if="uploading">{{ uploadCompleteFilesNum }}/{{ uploadFilesNum }}</div>
					<i v-else class="el-icon-upload" />
				</file-upload>
			</template>
			<template v-else>
				<div style="height: 0;overflow: hidden;">
					<canvas id="com-file-canvas" style="border: 1px solid #ccc;visibility: hidden;" />
				</div>
				<div flex="cross:center box:last" style="margin-bottom: 12px;">
					<div />
					<div flex="cross:center" style="margin-bottom: 20px;height: 30px;">
						<el-button v-if="!showEditBlock" @click="showEditBlock=true">开启编辑</el-button>
						<template v-if="showEditBlock" class="flex-y-center">
							<el-button style="margin-right: 12px" @click="showEditBlock=false">退出编辑模式</el-button>
							<el-checkbox v-model="selectAll" border label="全选" style="margin-right: 12px;"
								@change="selectAllChange" />
							<el-button :loading="deleteLoading" style="margin-right: 12px" @click="deleteItems">删除
							</el-button>
							<el-dropdown v-loading="moveLoading" trigger="click" :split-button="true"
								@command="moveItems">
								<span>移动至</span>
								<el-dropdown-menu slot="dropdown">
									<el-dropdown-item v-for="(item, index) in group_list" :key="index" :command="index">
										{{ item.name }}</el-dropdown-item>
								</el-dropdown-menu>
							</el-dropdown>
						</template>
					</div>
				</div>
				<div flex="box:first"
					style="border: 1px solid #e3e3e3;margin-bottom: 10px;min-height: 300px;display: flex;">
					<div style="border-right: 1px solid #e3e3e3">
						<el-menu v-loading="groupListLoading" class="group-menu" mode="vertical">
							<div class="flex-row">
								<el-button type="text" style="padding-left: 5px;" @click="showAddGroup(-1)">添加
								</el-button>
								<el-input v-model="keyword" style="width:90%;margin: 20px 5%" placeholder="请输入分类名称搜索"
									@keyup.enter.native="getGroupList" />
							</div>
							<el-scrollbar style="height:450px;width:100%">
								<el-menu-item index="all" @click="switchGroup(-1)">
									<i class="el-icon-tickets" />
									<span>全部</span>
								</el-menu-item>
								<template v-for="(item, index) in group_list">
									<el-menu-item :key="index" :index="'' + index" @click="switchGroup(index)">
										<div flex="dir:left box:last" class="flex-row">
											<div style="overflow: hidden;text-overflow: ellipsis;width: 80%;">
												<i class="el-icon-tickets" />
												<span>{{ item.name }}</span>
											</div>
											<div flex="dir:left" class="flex-row">
												<el-button type="text" @click.stop="showAddGroup(index)">编辑</el-button>
												<div style="color:#409EFF;margin:0 2px">|</div>
												<el-button type="text" @click.stop="deleteGroup(index)">删除</el-button>
											</div>
										</div>
									</el-menu-item>
								</template>
							</el-scrollbar>
						</el-menu>
					</div>
					<div v-loading="loading" flex="dir:top" style="width: 100%;">
						<el-scrollbar class="scrollbar">
							<div class="search" style="margin-right: 12px">
								<el-input v-model="p_keyword" placeholder="请输入名称搜索" class="input-with-select"
									@keyup.enter.native="fileSearch">
									<el-button slot="append" icon="el-icon-search" @click="fileSearch" />
								</el-input>
							</div>

							<div class="com-file-list">
								<div class="com-file-item com-file-upload">
									<file-upload style="height: 100%;" v-loading="uploading" :disabled="uploading"
										:multiple="true" :max="10" :params="uploadParams" :fields="uploadFields"
										:accept="accept" @fail="handleFail" flex="main:center cross:center"
										@start="handleStart" @complete="handleComplete" :is_site="is_site">
										<div class="flex-x-center flex-y-center" style="height: 100%;">

											<div v-if="uploading">{{ uploadCompleteFilesNum }}/{{ uploadFilesNum }}
											</div>
											<i v-else class="el-icon-upload" />
										</div>

									</file-upload>
								</div>
								<template v-for="(item, index) in files">
									<el-tooltip :key="index" class="item" effect="dark" :content="item.name"
										placement="top" :open-delay="1">
										<div :class="'com-file-item'+((item.checked)?' checked':'')"
											@click="handleClick(item)">
											<!-- :key="index" -->
											<el-image v-if="item.type == 'image'" class="com-file-img" :src="item.thumb_url"
												style="width: 100px;height: 100px;" fit="cover">
                      </el-image>
											<div v-if="item.type == 'video'" class="com-file-img"
												style="width: 100px;height: 100px;position: relative">
												<div v-if="item.cover_pic_src" class="com-file-video-cover"
													:style="'background-image: url('+item.cover_pic_src+');'" />
												<video :id="'app_attachment_'+ _uid + '_' + index"
													style="width: 0;height: 0;visibility: hidden;">
													<source :src="item.url">
												</video>
												<div class="com-file-video-info">
													<i class="el-icon-video-play" />
													<span>{{ item.duration?item.duration:'--:--' }}</span>
												</div>
											</div>
											<div v-if="item.type == 'doc'" class="com-file-img"
												style="width: 100px;height: 100px;line-height: 100px;text-align: center">
												<i class="file-type-icon el-icon-document" />
											</div>
											<div class="com-file-name">{{ item.name }}</div>
											<i v-if="false" class="com-file-active-icon el-icon-circle-check" />
										</div>
									</el-tooltip>
								</template>
							</div>
						</el-scrollbar>
						<div style="padding: 5px;text-align: right;margin-top:auto" v-if='pagination'>
							<el-pagination background :current-page="page" :page-size="pagination.pageSize"
								layout="prev, pager, next, jumper" :total="pagination.total_count"
								@size-change="handleLoadMore" @current-change="handleLoadMore" />
						</div>
					</div>
				</div>
				<div style="text-align: right;margin-top: 50px;">
					<el-button type="primary" @click="confirm">选定</el-button>
				</div>
			</template>
		</el-dialog>
		<el-dialog append-to-body title="分组管理" :visible.sync="addGroupVisible" :close-on-click-modal="false"
			width="25%">
			<el-form ref="groupForm" label-width="80px" :model="groupForm" :rules="groupFormRule"
				@submit.native.prevent>
				<el-form-item label="分组名称" prop="name" style="margin-bottom: 22px;">
					<el-input v-model="groupForm.name" maxlength="8" show-word-limit />
				</el-form-item>
				<el-form-item style="text-align: right">
					<el-button type="primary" :loading="groupFormLoading" @click="groupFormSubmit('groupForm')">保存
					</el-button>
				</el-form-item>
			</el-form>
		</el-dialog>
		<div style="line-height: normal;" :style="'display:'+(display?display:'inline-block')"
			@click="dialogVisible = !dialogVisible">
			<slot />
		</div>
	</div>
</template>

<script>
	import FileUpload from '@/components/common/file-upload'
	export default {
		name: 'FilePicker',
		components: {
			'file-upload': FileUpload
		},
		props: {
			display: String,
			title: String,
			multiple: Boolean,
			max: Number,
			params: Object,
			simple: {
				type: Boolean,
				value: false
			},
			type: {
				type: String,
				default: 'image'
			},
			value: {
				type: String,
				default: ''
			},
			openDialog: {
				type: Boolean,
				default: false
			},
			is_site: {
				type: Number,
				default: 0
			}
		},
		data() {
			return {
				canvas: null,
				uploading: false,
				dialogVisible: false,
				loading: true,
				loadingMore: false,
				noMore: false,
				files: [],
				checkedfiles: [],
				uploadParams: {},
				uploadFields: {},
				uploadCompleteFilesNum: 0,
				uploadFilesNum: 0,
				page: 1,
				addGroupVisible: false,
				noMall: true,
				group_list: [],
				group_list: [],
				current_group_id: 0,
				groupListLoading: false,
				groupForm: {
					id: null,
					name: ''
				},
				groupFormRule: {
					name: [{
						required: true,
						message: '请填写分组名称。'
					}]
				},
				groupFormLoading: false,
				showEditBlock: false,
				selectAll: false,
				deleteLoading: false,
				moveLoading: false,
				video: null,
				keyword: '',
				pagination: null,
				p_keyword: ''
			}
		},
		computed: {
			accept: {
				get() {
					if (this.type === 'image') {
						return 'image/*'
					}
					if (this.type === 'video') {
						return 'video/*'
					}
					return '*/*'
				}
			}
		},
		watch: {
			openDialog(newVal, oldVal) {
				this.dialogVisible = newVal
			},
			dialogVisible(newVal, oldVal) {
				if (!newVal) {
					this.$emit('closed')
				}
			},
			keyword(newVal, oldVal) {
				let group_list = this.group_list
				let arr = []
				group_list.map(v => {
					if (v.name.indexOf(newVal) !== -1) {
						arr.push(v)
					}
				})
				this.group_list = arr
			}
		},
		created() {},
		methods: {
			handleFail(e) {
				this.uploading = false
			},
			fileSearch() {
				this.page = 1
				this.loading = true
				this.getGroupList()
				this.getList({})
			},

			dialogOpened() {
				if (this.simple) {
					return
				}
				if (!this.files || !this.files.length) {
					this.loading = true
					this.getGroupList()
					this.getList({})
				}
			},
			deleteItems() {
				const itemIds = []
				for (const i in this.files) {
					if (this.files[i].checked) {
						itemIds.push(this.files[i].id)
					}
				}
				if (!itemIds.length) {
					this.$message.warning('请先选择需要删除的图片。')
					return
				}
				this.$confirm('确认删除所选的' + itemIds.length + '张图片？', '提示', {
						type: 'warning'
					})
					.then((e) => {
						this.$request({
							url: '/common/file/delete',
							data: {
								ids: itemIds
							},
							method: 'post'
						}).then(res => {

							if (res.code == 0) {
								this.$message.success('成功删除' + res.data.res + '条数据');
								this.getList();
								this.showEditBlock = false;
							}

						})
					})
					.catch(() => {})
			},
			selectAllChange(value) {
				for (const i in this.files) {
					this.files[i].checked = value
				}
			},
			selectItem(item) {
				item.selected = !item.selected
			},
			moveItems(index) {
				let group = this.group_list[index];
				const itemIds = []
				for (const i in this.files) {
					if (this.files[i].checked) {
						itemIds.push(this.files[i].id)
					}
				}
				if (!itemIds.length) {
					this.$message.warning('请先选择需要移动的图片。')
					return
				}
				this.$confirm('确认移动所选的' + itemIds.length + '张图片？', '提示', {
						type: 'warning'
					})
					.then(() => {
						this.moveLoading = true
						this.$request({
							url: '/common/file/move',
							data: {
								ids: itemIds,
								group_id: group.id,
							},
							method: 'post'
						}).then(res => {
							this.moveLoading = false;
							if (res.code == 0) {
								this.$message.success('成功移动' + res.data.res + '条数据');
								this.getList();
								this.showEditBlock = false;
							} else {
								this.$message.error('操作失败');
							}

						})



					})
					.catch(() => {})
			},

			getGroupList() {
				this.groupListLoading = true
				this.groupListLoading = false
				this.$request({
					url: '/common/file/group-list',
					data: {
						keyword: this.keyword,
						is_site: this.is_site
					}
				}).then(res => {
					if (res.code == 0) {
						this.group_list = res.data.list
					} else {
						this.$message.error(res.msg);
					}
				})

			},
			showAddGroup(index) {
				if (index > -1) {
					this.groupForm.id = this.group_list[index].id
					this.groupForm.name = this.group_list[index].name
				} else {
					this.groupForm.id = null
					this.groupForm.name = ''
				}
				this.groupForm.edit_index = index
				this.addGroupVisible = true
			},
			deleteGroup(index) {
				this.groupFormLoading = true;
				const title = '是否确认将分组放入回收站中？删除的分组可通过回收站还原'
				this.$confirm(title, '提示', {
						confirmButtonText: '确定',
						cancelButtonText: '取消',
						type: 'warning'
					})
					.then(() => {
						let id = this.group_list[index].id;
						this.$request({
							url: '/common/file/group-delete',
							data: {
								id: id
							},
							method: 'post'
						}).then(res => {
							this.groupFormLoading = false
							if (res.code === 0) {
								this.$message.success(res.msg)
								this.group_list.splice(index, 1);
							} else {
								this.$message.error(res.msg)
							}
						})


					})
					.catch(() => {})
			},
			groupFormSubmit(formName) {
				this.$refs[formName].validate(valid => {
					if (valid) {
						this.groupFormLoading = true
						this.$request({
								url: '/common/file/group-edit',
								method: 'post',
								data: Object.assign({}, this.groupForm, {
									type: this.type,
									is_site: this.is_site
								})
							})
							.then(e => {
								this.groupFormLoading = false
								if (e.code === 0) {
									this.$message.success(e.msg)
									this.addGroupVisible = false
									if (this.groupForm.edit_index > -1) {
										this.group_list[this.groupForm.edit_index] = e.data.group
									} else {
										this.group_list.push(e.data.group)
									}
								} else {
									this.$message.error(e.msg)
								}
							})
							.catch(e => {
								this.groupFormLoading = false
							})
					}
				})
			},
			switchGroup(index) {
				this.files = []
				this.page = 1
				this.noMore = false
				this.loading = true
				this.current_group_id = index > -1 ? this.group_list[index].id : null;
				this.uploadParams = {
					group_id: this.current_group_id
				}
				this.current_group_id =
					index > -1 ? this.group_list[index].id : null
				this.getList({})
			},
			getList(params) {
				this.noMore = false
				this.selectAll = false
				this.checkedfiles = []
				this.loading = true
				this.loadingMore = false
				this.$request({
					url: '/common/file/list',
					data: {
						page: this.page,
						group_id: this.current_group_id,
						type: this.type,
						keyword: this.p_keyword,
						is_site: this.is_site
					}
				}).then(res => {
					this.loading = false;
					if (res.code == 0) {
						this.files = res.data.list;
						this.pagination = res.data.pagination;
					}
				})
			},
			handleClick(item) {
				if (item.checked) {
					item.checked = false
					for (const i in this.checkedfiles) {
						if (item.id === this.checkedfiles[i].id) {
							this.checkedfiles.splice(i, 1)
						}
					}
					this.$forceUpdate();
					return
				}
				if (this.multiple) {
					let checkedCount = 0
					for (const i in this.files) {
						if (this.files[i].checked) checkedCount++
					}
					if (this.max && !item.checked && checkedCount >= this.max) return
					item.checked = true
					this.checkedfiles.push(item)
				} else {
					for (const i in this.files) this.files[i].checked = false
					item.checked = true
					this.checkedfiles = [item]
				}
				this.$forceUpdate();
			},
			confirm() {
				this.$emit('selected', this.checkedfiles, this.params)
				this.dialogVisible = false
				let urls = []
				for (const i in this.checkedfiles) {
					urls.push(this.checkedfiles[i].url)
				}
				for (const i in this.files) {
					this.files[i].checked = false
				}
				this.checkedfiles = []
				if (!urls.length) {
					return
				}
				if (this.multiple) {
					this.$emit('input', urls)
				} else {
					this.$emit('input', urls[0])
				}
			},
			handleStart(files) {
				this.uploading = true
				this.uploadFilesNum = files.length
				this.uploadCompleteFilesNum = 0
			},
			handleComplete(files) {
				this.uploading = false
				if (this.simple) {
					const urls = []
					const upload_files = []
					for (const i in files) {
						let newItem = files[i]
						urls.push(newItem.url)
						upload_files.unshift(newItem)
					}
					const _this = this
					setTimeout(() => {
						if (!urls.length) {
							return
						}
						_this.dialogVisible = false
						_this.$emit('selected', upload_files, _this.params)
						if (_this.multiple) {
							_this.$emit('input', urls)
						} else {
							_this.$emit('input', urls[0])
						}
					}, 1000)
					return;
				}
				for (const i in files) {
					if (files[i].response.data && files[i].response.code === 0) {
						this.files.unshift(files[i].response.data);
					}
				}
			},
			handleSuccess(file) {

				if (file.response && file.response.data && file.response.code === 0) {
					const newItem = {
						checked: false,
						selected: false,
						created_at: file.response.data.created_at,
						deleted_at: file.response.data.deleted_at,
						id: `${file.response.data.id}`,
						is_delete: file.response.data.is_delete,
						mall_id: file.response.data.mall_id,
						name: file.response.data.name,
						size: file.response.data.size,
						storage_id: file.response.data.storage_id,
						thumb_url: file.response.data.thumb_url,
						type: file.response.data.type,
						updated_at: file.response.data.updated_at,
						url: file.response.data.url,
						user_id: file.response.data.user_id,
						duration: null,
						cover_pic_src: null,
					};
					this.files.unshift(newItem);
					this.uploadCompleteFilesNum++;
					this.updateVideo();
				}
			},
			handleLoadMore(currentPage) {
				if (this.noMore) {
					return
				}
				this.page = currentPage
				this.loading = true
				this.loadingMore = true
				this.getList({})
			},
			updateVideo() {
				if (!this.canvas) {
					this.canvas = document.getElementById('com-file-canvas')
				}
				for (const i in this.files) {
					if (this.files[i].type == 2) {
						if (this.files[i].duration) {
							continue
						}
						let times = 0
						let video = null
						const maxRetry = 10
						const id = 'app_attachment_' + this._uid + '_' + i
						const timer = setInterval(() => {
							times++
							if (times >= maxRetry) {
								clearInterval(timer)
							}
							if (!video) {
								video = document.getElementById(id)
							}
							if (!video) {
								return
							}
							try {
								const zoom = 0.15
								this.canvas.width = video.videoWidth * zoom
								this.canvas.height = video.videoHeight * zoom
								this.canvas
									.getContext('2d')
									.drawImage(video, 0, 0, this.canvas.width, this.canvas.height)
								this.files[i].cover_pic_src = this.canvas.toDataURL(
									'image/jpg'
								)
							} catch (e) {
								console.warn('获取视频封面异常: ', e)
							}

							if (video.duration && !isNaN(video.duration)) {
								let m = Math.trunc(video.duration / 60)
								let s = Math.trunc(video.duration) % 60
								m = m < 10 ? `0${m}` : `${m}`
								s = s < 10 ? `0${s}` : `${s}`
								this.files[i].duration = `${m}:${s}`
								clearInterval(timer)
							}
						}, 500)
					}
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
	.com-file-list {
		padding: 5px;

		* {
			box-sizing: border-box;
		}

		&:after {
			clear: both;
			display: block;
			content: " ";
		}
	}

	.com-file-item {
		display: inline-block;
		cursor: pointer;
		position: relative;
		float: left;
		width: 120px;
		height: 140px;
		margin: 7.5px;
		text-align: center;
		padding: 10px 10px 0;

		&:hover {}

		.com-file-img {
			display: block;
		}

		.file-type-icon {
			width: 30px;
			height: 30px;
			border-radius: 30px;
			background: #666;
			color: #fff;
			text-align: center;
			line-height: 30px;
			font-size: 24px;
		}
	}

	.com-file-active-icon {
		position: absolute;
		right: 5px;
		top: 5px;
		font-size: 28px;
		color: #409eff;
		text-shadow: 0 0 1px rgba(255, 255, 255, 0.75);
		opacity: 0;
	}

	.com-file-item.checked,
	.com-file-item.selected {
		box-shadow: 0 0 0 1px #1ed0ff;
		background: #daf5ff;
		border-radius: 5px;
	}

	.com-file-item.checked .com-file-active-icon,
	.com-file-item.selected .com-file-active-icon {
		opacity: 1;
	}

	.com-file-upload {
		box-shadow: none;
		border: 1px dashed #b2b6bd;
		height: 100px;
		width: 100px;
		margin: 17.5px;
		padding: 0;

		i {
			font-size: 30px;
			color: #909399;
		}

		&:hover {
			box-shadow: none;
			border: 1px dashed #409eff;

			i {
				color: #409eff;
			}
		}

		&:active {
			border: 1px dashed #20669c;

			i {
				color: #20669c;
			}
		}
	}

	.com-file-dialog {
		.group-menu {
			border-right: none;
			width: 230px;

			.el-menu-item {
				padding-left: 10px !important;
				padding-right: 10px;

				.el-button {
					padding: 3px 0;

					i {
						margin-right: 0;
					}
				}
			}
		}

		.com-file-name {
			color: #666666;
			margin-top: 5px;
			font-size: 13px;
			word-break: break-all;
			text-overflow: ellipsis;
			display: -webkit-box;
			-webkit-box-orient: vertical;
			-webkit-line-clamp: 1;
			overflow: hidden;
		}

		.search {
			margin-top: 10px;
			margin-left: 20px;
			width: 250px;

			.el-input__inner {
				border-right: 0;

				&:hover {
					border: 1px solid #dcdfe6;
					border-right: 0;
					outline: 0;
				}

				&:focus {
					border: 1px solid #dcdfe6;
					border-right: 0;
					outline: 0;
				}
			}

			.el-input-group__append {
				background-color: #fff;
				border-left: 0;
				width: 10%;
				padding: 0;
				background-color: #fff;
				border-left: 0;
				width: 10%;
				padding: 0;

				.el-button {
					padding: 0;
					margin: 0;
				}
			}
		}

		.scrollbar {
			height: 100%;

			.el-scrollbar__wrap {
				overflow-y: hidden;
			}
		}
	}

	.del-com-file-dialog {
		.group-menu {
			.el-menu-item {
				.el-button {
					&:hover {
						background: #e2e2e2;
					}
				}
			}
		}
	}

	.com-file-simple-upload {
		width: 100% !important;
		height: 120px;
		border: 1px dashed #e3e3e3;
		cursor: pointer;

		&:hover {
			background: rgba(0, 0, 0, 0.05);
		}

		i {
			font-size: 32px;
		}
	}

	.com-file-video-cover {
		background-size: cover;
		background-position: center;
		width: 100%;
		height: 100%;
	}

	.com-file-video-info {
		background: rgba(0, 0, 0, 0.35);
		color: #fff;
		position: absolute;
		left: 0;
		bottom: 0;
		padding: 1px 3px;
		font-size: 14px;
	}

	.com-file-video-name {
		position: absolute;
		top: 100%;
		left: 0;
		width: 100%;
		font-size: 12px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
</style>
