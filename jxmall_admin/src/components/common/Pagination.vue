<template>
	<div style="text-align: right;margin-top: 20px;">
		<el-pagination v-if="pagination" background :page-size="pagination.pageSize" @current-change="pageChange"
			layout="total,prev, pager, next" :current-page="pagination.current_page" :total="pagination.total_count">
		</el-pagination>

	</div>
</template>

<script>
	export default {
		name: 'Pagination',
		props: {
			pagination: {
				type: Object,
				default: () => {
					return null;
				}

			}
		},
		methods: {
			pageChange(e) {
				this.$emit('input', e);
				this.$emit('pageChange', {
					page: e
				})
			},
		}
	}
</script>

<style>
</style>
