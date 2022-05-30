<template>
	<div class="app-container">
		<el-card class="box-card">
			<div slot="header" class="clearfix">
				<span>批量导入</span>
			</div>
			<el-row>
				<el-col :span="12">
					<el-upload class="upload-demo" action="" multiple :limit="3" :on-change="upload"
						:auto-upload="false" :file-list="fileList">
						<el-button size="small" type="primary">点击上传</el-button>
						<div slot="tip" class="el-upload__tip">只能上传xlsx文件，且不超过500kb</div>
					</el-upload>
					<el-button style="margin-top: 10px;" size="small" type="danger" @click="confirmSend">核对无误，确认发货
					</el-button>
				</el-col>
			</el-row>
			<div style="margin-top: 20px;">
				<el-table :data="list" style="width: 100%" v-loading='is_loading'>
					<el-table-column prop="订单ID" label="订单ID">
					</el-table-column>
					<el-table-column prop="订单号" label="订单号">
					</el-table-column>
					<el-table-column prop="收货人" label="收货人">
					</el-table-column>
					<el-table-column prop="电话" label="电话">
					</el-table-column>
					<el-table-column prop="收货地址" label="收货地址">
					</el-table-column>
					<el-table-column prop="快递单号" label="快递单号">
					</el-table-column>
					<el-table-column prop="快递名称" label="快递名称">
					</el-table-column>
				</el-table>
			</div>


		</el-card>
	</div>
</template>

<script>
import XLSX from 'xlsx'

export default {
	data() {
		return {
			is_loading: false,
			fileList: [],
			list: [],
		}
	},
	created() {


	},
	methods: {


		confirmSend() {
			if (this.list.length == 0) {
				this.$message.error('请先上传数据');
				return;
			}
			this.$request({
				url: "/mall/order/excel-send",
				data: {
					excel_data: JSON.stringify(this.list)
				},
				method: 'post'
			}).then(res => {
				if (res.code == 0) {
					this.$message.success(res.msg);
				}
			})
		},


		upload(file, fileList) {
			this.readExcel(file.raw);
		},
		readExcel(file) { //表格导入
			var that = this;
			if (!/\.(xls|xlsx)$/.test(file.name.toLowerCase())) {
				this.$message.error('上传格式不正确，请上传xls或者xlsx格式');
				return false;
			}

			const fileReader = new FileReader();
			fileReader.onload = (ev) => {
				try {
					const data = ev.target.result;
					const workbook = XLSX.read(data, {
						type: 'binary'
					});
					const wsname = workbook.SheetNames[0]; //取第一张表
					const ws = XLSX.utils.sheet_to_json(workbook.Sheets[wsname]); //生成json表格内容

					this.list = ws;


					this.$refs.upload.value = '';
				} catch (e) {

					return false;
				}
			};
			fileReader.readAsBinaryString(file);
		},


	}
}
</script>

<style>
</style>
