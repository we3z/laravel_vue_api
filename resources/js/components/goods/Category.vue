<template>
  <div>
    <!-- 面包屑导航区域 -->
    <el-breadcrumb separator=">">
      <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>商品管理</el-breadcrumb-item>
      <el-breadcrumb-item>商品分类</el-breadcrumb-item>
    </el-breadcrumb>
    <!-- 卡片视图 -->
    <el-card class="box-card">
      <el-row>
        <el-col>
          <el-button type="primary">添加分类</el-button>
        </el-col>
      </el-row>
      <!-- 表格占位 -->
      <el-table :data="categoryList" stripe style="width: 100%;" border>
        <el-table-column type="index" label="序号" width="150"></el-table-column>
        <el-table-column prop="cat_name" label="分类名称"></el-table-column>
        <el-table-column prop="email" label="是否有效"></el-table-column>
        <el-table-column prop="cat_level" label="排序"></el-table-column>
        <el-table-column label="操作" width="180">
          <template slot-scope="scope">
            <!-- 编辑分类按钮 -->
            <el-tooltip effect="dark" content="编辑用户" placement="top-start" :enterable="false">
              <el-button
                type="primary"
                size="mini"
                icon="el-icon-edit"
                @click="editCategory(scope.row.id)"
              ></el-button>
            </el-tooltip>
            <!-- 删除分类按钮 -->
            <el-tooltip effect="dark" content="删除用户" placement="top-start" :enterable="false">
              <el-button
                type="danger"
                size="mini"
                icon="el-icon-delete"
                @click="deleteCategory(scope.row.id)"
              ></el-button>
            </el-tooltip>
          </template>
        </el-table-column>
      </el-table>
      <!-- 分页区域 -->
      <el-pagination
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        :current-page="queryInfo.pageNum"
        :page-sizes="[2, 3, 5, 10]"
        :page-size="queryInfo.pageSize"
        layout="total, sizes, prev, pager, next, jumper"
        :total="total"
      ></el-pagination>
    </el-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
        queryInfo: {
            pageSize: 5,
            pageNum: 1,
            type: 3
        },
        total: 0,
      
      categoryList: [],
    }
  },
  created() {
    this.getCategoryList()
  },
  methods: {
    // 获取商品分类列表
    async getCategoryList() {
      const { data: res } = await this.$http.get('categories', {params : this.queryInfo})
      if (res.meta.status !== 200) {
        return this.$message.error(res.meta.msg)
      }
      this.categoryList = res.data.data
      this.queryInfo.total = res.data.totalPage
    },
    // 处理分页数据条数变化
    handleSizeChange(newPageSize) {
      this.queryInfo.pagesize = newPageSize
      this.getUserList()
    },
    // 处理分页页码变化
    handleCurrentChange(newPageNum) {
      this.queryInfo.pagenum = newPageNum
      this.getUserList()
    },
    // 编辑商品分类
    editCategory(categoryId) {},
    // 删除商品分类
    deleteCategory(categoryId) {},
  },
}
</script>

<style lang="less" scoped>
</style>