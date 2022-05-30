<template>
  <div style="text-align: start;">
    <el-dialog title="选择链接" :visible.sync="dialogVisible" :before-close="handleClose">
      <!-- 	<el-scrollbar style="height: 450px;">
                <div @click="linkClick(item,i)" v-for="(item,i) in link_list" class="flex-y-center flex-x-between item" :class="{'active':index==i}">
                    <span>
                        {{item.name}}
                    </span>
                    <span style="font-size: 20px;">
                        <i class="el-icon-arrow-right"></i>
                    </span>
                </div>
            </el-scrollbar> -->
      <el-tabs v-model="activeName" @tab-click="tabClick" v-loading='is_loading'>
        <el-tab-pane label="商城页面" name="mall">
          <el-scrollbar style="height: 450px;">
            <div v-if="activeName == 'mall'">
              <div style="margin-bottom: 20px;" v-for="(item, index) in list">
                <div class="link-group-title">{{ item.name }}</div>
                <div class="links">
                  <div class="flex-y-center flex-x-center link-item" :class="{ active: url == link.url ? true : false }"
                    v-for="(link, i) in item.links" @click="linkClick(link)"><span>{{ link.name }}</span></div>
                </div>
              </div>
            </div>
          </el-scrollbar>
        </el-tab-pane>
        <el-tab-pane label="商品" name="goods">
          <el-row>
            <el-col :span="12">
              <div class="flex-row">
                <el-input v-model="keyword" placeholder="输入商品名称进行搜索"></el-input>
                <el-button @click="getGoodsList" type="primary" style="margin: 0 10px;">搜索</el-button>
              </div>
            </el-col>
          </el-row>
          <el-table :data="list" style="width: 100%">
            <el-table-column label="商品" width="500px">
              <template slot-scope="scope">
                <div class="flex-row">
                  <el-image :src="scope.row.cover_pic" style="width: 60px;height: 60px;">
                    <div slot="error" class="image-slot">
                      <i class="el-icon-picture-outline"></i>
                    </div>
                  </el-image>
                  <div style="padding: 20px;">
                    {{ scope.row.name }}
                  </div>
                </div>
              </template>
            </el-table-column>
            <el-table-column prop="price" label="价格" width="180">
            </el-table-column>
            <el-table-column prop="stock" label="库存">
            </el-table-column>
            <el-table-column label="操作">
              <template slot-scope="scope">
                <el-button size="mini" v-if="scope.row.selected == 1" type="primary"
                  @click="selectRow(scope.row, scope.$index)">
                  已选
                </el-button>
                <el-button size="mini" v-if="scope.row.selected !== 1" @click="selectRow(scope.row, scope.$index)"
                  plain>
                  选择
                </el-button>
              </template>
            </el-table-column>
          </el-table>
          <pagination :pagination="paginatin" @pageChange="getGoodsList" v-model="page"></pagination>
        </el-tab-pane>
        <el-tab-pane label="自定义页面" name="diy">
          <el-row>
            <el-col :span="12">
              <div class="flex-row">
                <el-input v-model="keyword" placeholder="输入商品名称进行搜索"></el-input>
                <el-button @click="getGoodsList" type="primary" style="margin: 0 10px;">搜索</el-button>
              </div>
            </el-col>
          </el-row>
          <el-table :data="list" style="width: 100%">
            <el-table-column prop="id" label="ID">
            </el-table-column>
            <el-table-column prop="name" label="名称">
            </el-table-column>
            <el-table-column label="页面链接">
              <template slot-scope="scope">
                /pages/diy/diy?id={{ scope.row.id }}
              </template>
            </el-table-column>
            <el-table-column label="操作">
              <template slot-scope="scope">
                <el-button size="mini" v-if="scope.row.selected == 1" type="primary"
                  @click="selectRow(scope.row, scope.$index)">
                  已选
                </el-button>
                <el-button size="mini" v-if="scope.row.selected !== 1" @click="selectRow(scope.row, scope.$index)"
                  plain>
                  选择
                </el-button>
              </template>
            </el-table-column>
          </el-table>
          <pagination :pagination="paginatin" @pageChange="getGoodsList" v-model="page"></pagination>

        </el-tab-pane>
        <el-tab-pane label="分类" name="cat">
          <el-row>
            <el-col :span="12">
              <div class="flex-row">
                <el-input v-model="keyword" placeholder="输入商品名称进行搜索"></el-input>
                <el-button @click="getCatList" type="primary" style="margin: 0 10px;">搜索</el-button>
              </div>
            </el-col>
          </el-row>
          <el-table :data="list" style="width: 100%">
            <el-table-column prop="id" label="ID">
            </el-table-column>
            <el-table-column prop="name" label="名称">
            </el-table-column>
            <el-table-column label="页面链接">
              <template slot-scope="scope">
                /pages/goods-list/goods-list?cat_id={{ scope.row.id }}
              </template>
            </el-table-column>
            <el-table-column label="操作">
              <template slot-scope="scope">
                <el-button size="mini" v-if="scope.row.selected == 1" type="primary"
                  @click="selectRow(scope.row, scope.$index)">
                  已选
                </el-button>
                <el-button size="mini" v-if="scope.row.selected !== 1" @click="selectRow(scope.row, scope.$index)"
                  plain>
                  选择
                </el-button>
              </template>
            </el-table-column>
          </el-table>
          <pagination :pagination="paginatin" @pageChange="getCatList" v-model="page"></pagination>

        </el-tab-pane>
        <el-tab-pane label="插件" name="plugin">
          <el-scrollbar style="height: 450px;">
            <div v-if="activeName == 'plugin'">
              <div style="margin-bottom: 20px;" v-for="(item, index) in list">
                <div class="link-group-title">{{ item.name }}</div>
                <div class="links">
                  <div class="flex-y-center flex-x-center link-item" :class="{ active: url == link.url ? true : false }"
                    v-for="(link, i) in item.links" @click="linkClick(link)"><span>{{ link.name }}</span></div>
                </div>
              </div>
            </div>
          </el-scrollbar>
        </el-tab-pane>
        <el-tab-pane label="外部链接" name="outside">
          <el-scrollbar style="height: 450px;">
            <div v-if="activeName == 'outside'">
              <el-table :data="list" style="width: 100%">
                <el-table-column prop="id" label="ID">
                </el-table-column>
                <el-table-column prop="name" label="名称">
                </el-table-column>
                <el-table-column prop="link" label="链接地址">
                </el-table-column>
                <el-table-column label="操作">
                  <template slot-scope="scope">
                    <el-button size="mini" v-if="scope.row.selected == 1" type="primary"
                      @click="selectRow(scope.row, scope.$index)">
                      已选
                    </el-button>
                    <el-button size="mini" v-if="scope.row.selected !== 1" @click="selectRow(scope.row, scope.$index)"
                      plain>
                      选择
                    </el-button>
                  </template>
                </el-table-column>
              </el-table>
            </div>
          </el-scrollbar>
          <div style="text-align: right;margin: 20px 0;">
            <pagination :pagination="pagination" @pageChage="getOutsideList">
            </pagination>
          </div>
        </el-tab-pane>
      </el-tabs>
      <span slot="footer" class="dialog-footer">
        <el-button @click="handleClose">取 消</el-button>
        <el-button type="primary" @click="confirm">确 定</el-button>
      </span>
    </el-dialog>
    <div @click="dialogVisible = !dialogVisible">
      <slot />
    </div>
  </div>
</template>
<script>
export default {
  props: {
    showLink: {
      type: Boolean,
      default: false
    },
    display: String,
  },
  data() {
    return {
      paginatin: null,
      is_loading: false,
      activeName: 'mall',
      dialogVisible: false,
      url: '',
      index: -1,
      list: [],
      page: 1,
      keyword: '',
      url_name: '',
      outside_link: '',
      link_list: [{
        name: '分类',
        url: '/pages/cat/cat',
        nav_type: 'navigate'
      },
      {
        name: '订单',
        url: '/pages/order/order',
        nav_type: 'redirect'
      },

      ]
    }
  },
  watch: {
    showLink(newVal, oldVal) {
      if (newVal) {
        this.dialogVisible = true;
      }
    },
    dialogVisible(newVal, oldVal) {
      if (newVal) {
        this.getList();
      }
    }
  },

  methods: {

    tabClick(e) {
      this.activeName = e.name;
      this.list = [];
      this.url = "";
      this.page = 1;
      this.keyword = "";
      if (this.activeName == 'goods') {
        this.getGoodsList();
      }
      if (this.activeName == 'mall') {
        this.getList();
      }

      if (this.activeName == 'diy') {
        this.getDiyList();
      }
      if (this.activeName == 'cat') {
        this.getCatList();
      }
      if (this.activeName == 'plugin') {
        this.getList();
      }
      if (this.activeName == 'outside') {
        this.getOutsideList();
      }
    },
    selectRow(row, index) {
      if (row.selected != 1) {
        row.selected = 1;
        this.$set(this.list, index, row)
        if (this.activeName == 'goods') {
          this.url = "/pages/goods/goods?id=" + row.id
          this.url_name = row.name;
        }
        if (this.activeName == 'diy') {
          this.url = "/pages/diy/diy?id=" + row.id
          this.url_name = row.name;
        }
        if (this.activeName == 'cat') {
          this.url = "/pages/goods-list/goods-list?cat_id=" + row.id
          this.url_name = row.name;
        }
        if (this.activeName == 'outside') {
          this.url = "/pages/outside/outside?id=" + row.id
          this.url_name = row.name;
        }
      } else {
        row.selected = 0;
        this.url = "";
        this.$set(this.list, index, row)
      }
      this.$forceUpdate();
    },

    getGoodsList() {
      this.is_loading = true;
      this.$request({
        url: '/mall/goods/list',
        data: {
          page: this.page,
          keyword: this.keyword,
          status: 1
        }
      }).then(res => {
        this.is_loading = false;
        if (res.code == 0) {
          this.list = res.data.list;
          for (let i in this.list) {
            this.list[i].selected = 0;
          }
          this.paginatin = res.data.pagination
        }
      })
    },

    getDiyList() {
      this.is_loading = true;
      this.$request({
        url: '/mall/mall/diy',
        data: {
          page: this.page
        }
      }).then(res => {
        this.is_loading = false;
        if (res.code == 0) {
          this.list = res.data.list
        }
      })
    },
    getCatList() {
      this.is_loading = true;
      this.$request({
        url: '/mall/goods/cat',
        data: {
          page: this.page,
          keyword: this.keyword
        }
      }).then(res => {
        this.is_loading = false;
        if (res.code == 0) {
          this.list = res.data.list
        }
      })
    },
    getOutsideList() {
      this.is_loading = true;
      this.$request({
        url: '/mall/mall/outside-link-list',
        data: {
          page: this.page,
          keyword: this.keyword
        }
      }).then(res => {
        this.is_loading = false;
        if (res.code == 0) {
          this.list = res.data.list
        }
      })
    },

    getList() {
      this.is_loading = true;
      this.$request({
        url: '/mall/link/list',
        data: {
          type: this.activeName
        }
      }).then(res => {
        this.is_loading = false;
        if (res.code == 0) {
          this.list = res.data.list
        }
      })
    },

    linkClick(e) {
      this.url = e.url;
      this.url_name = e.name;
    },

    handleClose() {
      this.dialogVisible = false;
      this.$emit('cancel');

    },
    confirm() {
      this.dialogVisible = false;
      //这里应该将url 传回去

      if (this.outside_link) {
        this.$emit('input', this.url)
        this.$emit('selected', {
          url: this.outside_link,
          url_name: '外部链接'
        })
        return;
      }
      this.$emit('input', this.url)
      this.$emit('selected', {
        url: this.url,
        url_name: this.url_name
      })



    }
  }
}
</script>

<style lang="scss" scoped="scoped">
.item {
  width: 100%;
  height: 38px;
  border-bottom: solid #f0f0f0 1px;
  padding-left: 20px;

  &:hover {
    background-color: #DCECF7;
    color: #409EFF;
    cursor: pointer;
  }
}

.link-group-title {
  font-weight: bold;
  color: #000000;
  height: 50px;
  line-height: 50px;
}

.active {
  background-color: #409EFF;
  color: #FFFFFF;
}

.el-scrollbar__wrap {
  overflow-x: hidden;
}

.links {
  display: flex;
  flex-wrap: nowrap;
  flex-flow: row wrap;
  justify-content: flex-start;
  align-items: center;

}

.link-item {

  width: 150px;
  border: solid #F3F3F3 1px;
  height: 50px;
  margin-right: 20px;
  margin-bottom: 20px;

}

.link-item:hover {
  cursor: pointer;
}
</style>
