<template>
	<div class="app-container">
		<el-card>
			<div style="padding:20px 30px;background-color: #FFFFFF;margin-bottom: 20px;">
				<div style="padding: 10px 0;font-size: 18px;color: #000000;font-weight: bold;">佣金概览</div>
				<div class="flex-row">
					<div style="width: 33.33%;border-radius: 0px;border: solid #f3f3f3 1px;height: 150px;margin-right: 10px;" class="flex-y-center">
						<div class="flex-y-center" style="margin-left: 30px;">
							<div style="font-size: 35px;color:#F66000 ;">
								<i class="el-icon-document"></i>
							</div>
							<div style="margin-left: 20px;">
								<div style="padding: 10px 0px;">
									待结算佣金(元)
								</div>
								<div>
									<span style="font-size: 30px;">{{info.status_0}}</span>
								</div>
							</div>
						</div>
					</div>
					<div style="width: 33.33%;border-radius: 0px;border: solid #f3f3f3 1px;height: 150px;margin-right: 10px;" class="flex-y-center">
						<div class="flex-y-center" style="margin-left: 30px;">
							<div style="font-size: 35px;color:#F66000 ;">
								<i class="el-icon-document"></i>

							</div>
							<div style="margin-left: 20px;">
								<div style="padding: 10px 0px;">
									已结算佣金(元)
								</div>
								<div>
									<span style="font-size: 30px;">{{info.status_1}}</span>
								</div>
							</div>
						</div>

					</div>
					<div style="width: 33.33%;border-radius: 0px;border: solid #f3f3f3 1px;height: 150px;margin-right: 10px;" class="flex-y-center">
						<div class="flex-y-center" style="margin-left: 30px;">
							<div style="font-size: 35px;color:#F66000 ;">
								<i class="el-icon-document"></i>

							</div>
							<div style="margin-left: 20px;">
								<div style="padding: 10px 0px;">
									分销订单
								</div>
								<div>
									<span style="font-size: 30px;">{{info.order}}</span>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
			<div style="padding:20px 30px;background-color: #FFFFFF;margin-bottom: 20px;">
				<div style="padding: 10px 0;font-size: 18px;color: #000000;font-weight: bold;">分销商概览</div>
				<div class="flex-row">
					<div style="width: 50%;border-radius: 0px;border: solid #f3f3f3 1px;height: 150px;margin-right: 10px;" class="flex-y-center">
						<div class="flex-y-center" style="margin-left: 30px;">
							<div style="font-size: 35px;color:#F66000 ;">
								<i class="el-icon-user-solid"></i>
							</div>
							<div style="margin-left: 20px;">
								<div style="padding: 10px 0px;">
									待审核分销商(人)
								</div>
								<div>
									<span style="font-size: 30px;padding: 0 20px;">{{info.apply}}</span>
									<el-button type="text" @click.stop="checkApply">去审核</el-button>
								</div>
							</div>
						</div>
					</div>
					<div style="width: 50%;border-radius: 0px;border: solid #f3f3f3 1px;height: 150px;margin-right: 10px;" class="flex-y-center">
						<div class="flex-y-center" style="margin-left: 30px;">
							<div style="font-size: 35px;color:#F66000 ;">
								<i class="el-icon-user"></i>
							</div>
							<div style="margin-left: 20px;">
								<div style="padding: 10px 0px;">
									总分销商人数/总会员人数
								</div>
								<div>
									<span style="font-size: 30px;">{{info.share}}/{{info.user}}</span>
								</div>
							</div>
							<div>
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
    name: 'plugins-share-index',
		data() {
			return {

				info: {
					apply: "0",
					order: "0",
					share: "2",
					status_0: "0.00",
					status_1: "0.00",
					user: "0",
				}
			}
		},
		created() {

			this.getData();
		},
		methods: {
			checkApply() {
				console.log('====');
				this.$router.replace({
					path: '/plugins/share/apply'
				})
			},
			getData() {
				this.$request({
					url: '/plugin/share/mall/share/data'
				}).then(res => {
					if (res.code == 0) {
						this.info = res.data.info;
					}
					//
				})


			},
		}
	}
</script>

<style>
</style>
