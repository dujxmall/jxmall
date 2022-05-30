<template>
    <div class="app-container">
        <el-card class="box-card" v-loading="is_loading">
            <div slot="header" class="clearfix">
                <span>外部链接</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="dialogVisible = true">新建链接
                </el-button>
            </div>

            <el-table :data="list" border style="width: 100%">
                <el-table-column prop="id" label="ID">
                </el-table-column>
                <el-table-column prop="name" label="名称">
                </el-table-column>
                <el-table-column prop="link" label="链接地址">
                </el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <el-button type="text" @click="editLink(scope.row)">编辑</el-button>

                    </template>
                </el-table-column>
            </el-table>
            <div style="text-align: right;margin: 20px 0;">
                <pagination :pagination="pagination" @pageChage="getList">
                </pagination>
            </div>
        </el-card>
        <el-dialog title="提示" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">

            <el-form :model="form" ref="form" label-width="80px" :inline="false" size="normal">
                <el-form-item label="链接名称">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="链接地址">
                    <el-input v-model="form.link"></el-input>
                </el-form-item>
            </el-form>


            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">取 消</el-button>
                <el-button type="primary" @click="submit">确 定</el-button>
            </span>
        </el-dialog>

    </div>
</template>
<script>
export default {
    name: 'outside-link',
    data() {
        return {
            dialogVisible: false,
            page: 1,
            list: [],
            is_loading: false,
            pagination: null,
            form: {
                id: '',
                name: '',
                link: ''
            }
        }
    },
    created() {
        this.getList();
    },
    methods: {
        submit() {
            if (this.is_loading) {
                return;
            }
            this.is_loading = true;
            this.$request({
                url: '/mall/mall/outside-link',
                data: this.form,
                method: 'post'
            }).then(res => {
                this.is_loading = false;
                if (res.code == 0) {
                    this.$message.success(res.msg);
                    this.handleClose();
                    this.form.id = '';
                    this.form.name = '';
                    this.form.link = '';
                    this.getList();
                }
            }).catch(e => {
                this.is_loading = false;
            })

        },
        handleClose(e) {
            this.dialogVisible = false;
        },
        getList() {
            this.is_loading = true;
            this.$request({
                url: '/mall/mall/outside-link-list',
                data: {
                    page: this.page,
                }
            }).then(res => {
                this.is_loading = false;
                if (res.code == 0) {
                    this.list = res.data.list
                    this.pagination = res.data.pagination
                }
            })
        },
        editLink(row) {
            this.form = Object.assign(this.form, row);
            this.dialogVisible = true;
        },

        //没有做
        deletePage(row) {
            this.$confirm('确认删除链接：' + row.name + '？', '提示', {
                type: 'warning'
            }).then(() => {
                this.is_loading = true;
                this.$request({
                    url: "/mall/mall/link-delete",
                    data: {
                        id: row.id
                    },
                    method: 'POST'
                }).then(res => {
                    this.is_loading = false;
                    if (res.code == 0) {
                        this.$message.success(res.msg)
                        this.getList();
                    }
                })
            }).catch(() => {


            })


        },



    }

}
</script>
<style scoped>
</style>