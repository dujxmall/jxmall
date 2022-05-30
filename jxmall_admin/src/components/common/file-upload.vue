<template>
	<div class="file-upload" flex="main:center cross:center" @click="handleClick">
		<slot />
		<input ref="input" type="file" :accept="accept" :multiple="multiple" style="display: none" @change="handleChange">
	</div>
</template>

<script>
	import axios from 'axios'
	import {
		getToken
	} from '@/utils/auth'
	import {
		getMallId
	} from '@/utils/mall'
	export default {
		name: 'FileUpload',
		props: {
			disabled: Boolean,
			multiple: Boolean,
			max: Number,
			accept: String,
			params: Object,
			fields: Object,
			is_site: {
				type: Number,
				default: 0
			}
		},
		data() {
			return {
				dialogVisible: false,
				loading: true,
				checkedAttachments: [],
				files: []
			}
		},

		methods: {
			handleClick() {
				if (this.disabled) {
					return
				}
				this.$refs.input.value = null
				this.$refs.input.click()
			},
			handleChange(e) {
				if (!e.target.files) return
				this.uploadFiles(e.target.files)
			},
			uploadFiles(rawFiles) {
				if (this.max && rawFiles.length > this.max) {
					this.$message.error('最多一次只能上传' + this.max + '个文件。')
					return
				}
				this.files = []
				for (let i = 0; i < rawFiles.length; i++) {
					const file = {
						complete: false,
						response: null,
						rawFile: rawFiles[i]
					}
					this.files.push(file)
				}
				this.$emit('start', this.files)
				for (const i in this.files) {
					this.upload(this.files[i])
				}
			},
			upload(file) {
				let self = this;
				let formData = new FormData()
				if (this.params.group_id) {
					formData.append('group_id', this.params.group_id)
				}
				formData.append('is_site', this.is_site)
				formData.append('file', file.rawFile, file.rawFile.name)


				let mallId = getMallId();
				mallId = mallId ? mallId : 0;

				axios.post('/common/file/upload', formData, {
					headers: {
						'Content-Type': 'multipart/form-data',
						'x-mall-id': mallId,
						'x-admin-token': getToken()
					},
				}).then(res => {
					if (res.data.code == 0) {
						file.response = res.data;
						file.complete = true;
						let data = res.data.data;
						this.onSuccess(file)
					} else {
						this.$message.error(res.data.msg)
						this.$emit('fail', file)
					}

				}).catch(e => {
					file.complete = true;
				});
			},
			onSuccess(file) {
				this.$emit('success', file)
				let allComplete = true
				for (let i in this.files) {
					if (!this.files[i].complete) {
						allComplete = false
						break
					}
				}
				if (allComplete) {
				 
					this.$emit('complete', this.files)
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
</style>
