<template>
	<div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>发货记录</span>

      </div>
      <el-row>
        <el-col :span="12">
          <div class="flex-row">
            <el-input style="width: 250px;" v-model="keyword" placeholder="关键字"></el-input>
            <el-button style="margin: 0 20px;" type="primary" @click="search">搜索</el-button>
          </div>
        </el-col>
      </el-row>
      <div style="margin-top: 20px;">

        <el-table :data="list" style="width: 100%" v-loading='is_loading'>
          <el-table-column prop="order_no" label="订单号">
          </el-table-column>

          <el-table-column prop="express_name" label="快递公司" width="100px">

          </el-table-column>
          <el-table-column prop="express_no" label="快递单号">

          </el-table-column>

          <el-table-column prop="name" label="收货人">

          </el-table-column>
          <el-table-column prop="mobile" label="手机号">

          </el-table-column>
          <el-table-column prop="address" label="收件地址">

          </el-table-column>


          <el-table-column prop="num" label="商品数量">

          </el-table-column>
          <el-table-column label="发货方式">
            <template slot-scope="scope">
              <span v-if="scope.row.is_all==1">整包发货</span>
              <span v-else>分包发货</span>
            </template>
          </el-table-column>
          <el-table-column label="电子面单模板">
            <template slot-scope="scope">
						<span v-if="scope.row.eorder_id==0">
							<el-button type="text" @click="chooseEorder(scope.row.id)">选择电子面单模板</el-button>
						</span>
              <span v-else>
							{{scope.row.eorder_name}}
						</span>
            </template>
          </el-table-column>

          <el-table-column label="更新时间" prop="updated_at">


          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-button type='text' style="margin-right: 10px;" @click="getEorder(scope.row.id)">打印电子面单</el-button>
              <!-- 	<el-tooltip class="btn" effect="dark" content="删除" placement="top">
                                <el-popconfirm confirmButtonText='是' cancelButtonText='否' icon="el-icon-info" iconColor="red" title="确定删除？"
                                 @onConfirm="deleteEorder(scope.row.id)">
                                    <el-button type="text" size="mini" slot="reference">删除</el-button>
                                </el-popconfirm>
                            </el-tooltip> -->
            </template>
          </el-table-column>
        </el-table>
        <pagination :pagination="pagination" @pageChange="getList" v-model="page"></pagination>
      </div>

      <el-dialog title="提示" :visible.sync="dialogVisible" :before-close="handleClose">
        <el-table :data="eorder_list" style="width: 100%">
          <el-table-column prop="id" label="ID" width="100px">
          </el-table-column>
          <el-table-column prop="name" label="模板名称">
          </el-table-column>
          <el-table-column label="快递公司">
            <template slot-scope="scope">
              <div>
                {{scope.row.express.name}}
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="tpl_style" label="模板样式">
          </el-table-column>
          <el-table-column label="是否默认">
            <template slot-scope="scope">
              <span v-if="scope.row.is_default==1">默认</span>
              <span v-else>否</span>
            </template>
          </el-table-column>
          <el-table-column label="付款方式">
            <template slot-scope="scope">
              <span v-if="scope.row.pay_type==0">现付</span>
              <span v-if="scope.row.pay_type==1">到付</span>
              <span v-if="scope.row.pay_type==2">月结</span>
            </template>
          </el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-button v-if="scope.$index!=eOrderIndex" style="margin-right: 10px;" @click="selectEorder(scope)">选择</el-button>
              <el-button v-if="scope.$index==eOrderIndex" type='primary' style="margin-right: 10px;">选择</el-button>
            </template>
          </el-table-column>
        </el-table>
        <pagination :pagination="ePagination" @pageChange="getEorderList" v-model="ePage"></pagination>
        <span slot="footer" class="dialog-footer">
				<el-button @click="dialogVisible = false">取 消</el-button>
				<el-button type="primary" @click="confirmSelect">确 定</el-button>
			</span>
      </el-dialog>




    </el-card>
  </div>
</template>

<script>
	import {
		printPdf
	} from "@/utils/print";

	export default {
		name: 'order-express-log',
		data() {
			return {
				keyword:'',
				page: 1,
				dialogVisible: false,
				pagination: null,
				list: [],
				eorder_list: [],
				ePage: 1,
				ePagination: null,
				eOrderIndex: -1,
				eOrder: null,
				currentLogId: '',
			}
		},
		created() {
			this.getList()
			this.getEorderList();

		},
		methods: {

			search() {
				this.list=[];
				this.page=1;
				this.getList();
			},
			getEorderList() {
				this.$request({
					url: "/mall/mall/eorder-list",
					data: {
						page: this.ePage
					}
				}).then(res => {
					if (res.code == 0) {
						this.eorder_list = res.data.list;
						this.ePagination = res.data.pagination;
					}
				})

			},
			chooseEorder(e) {
				this.currentLogId = e;
				this.dialogVisible = true;
			},
			handleClose(e) {
				this.dialogVisible = false
			},
			confirmSelect(e) {

				this.$request({
					url: '/mall/order/express-log-eorder',
					data: {
						eorder_id: this.eOrder.id,
						log_id: this.currentLogId
					},
					method: 'post'
				}).then(res => {
					this.dialogVisible = false;
					if (res.code == 0) {
						this.$message.success(res.msg)
						this.getList();
					}

				})
			},
			selectEorder(e) {
				this.eOrderIndex = e.$index;
				this.eOrder = this.eorder_list[this.eOrderIndex];
			},
			getList() {
				this.is_loading = true;
				this.$request({
					url: '/mall/order/express-log',
					data: {
						page: this.page,
						keyword:this.keyword
					}
				}).then(res => {
					this.is_loading = false;
					if (res.code == 0) {
						this.list = res.data.list;
						this.pagination = res.data.pagination;
					}

				})
			},

			deleteEorder(id) {
				this.$request({
					url: "/mall/mall/eorder-delete",
					data: {
						id: id
					},
					method: 'post',
				}).then(res => {
					if (res.code == 0) {
						this.$message.success(res.msg)
						this.getList();
					}
				})

			},
			getEorder(id) {

				this.$request({
					url: "/mall/order/express-log-eorder",
					data: {
						log_id: id
					}
				}).then(res => {
					if (res.code == 1) {

					}
					if (res.code == 0) {


						//	printPdf(res.data.print_tpl);
						let myWindow = window.open('', '_blank');
						myWindow.document.write(res.data.print_tpl);
						myWindow.focus();
					}
				})




			}
		}
	}
</script>

<style>
</style>
