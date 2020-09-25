<template>
  <div>
    <!-- 面包屑导航 -->
    <el-breadcrumb separator=">">
      <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>权限管理</el-breadcrumb-item>
      <el-breadcrumb-item>角色列表</el-breadcrumb-item>
    </el-breadcrumb>

    <!-- 卡片视图区域 -->
    <el-card class="box-card">
      <el-table :data="rightsList" stripe border>
        <el-table-column type="index" label="#" width="50"></el-table-column>
        <el-table-column prop="authName" label="权限名称" >
        </el-table-column>
        <el-table-column prop="path" label="路径">
        </el-table-column>
        <el-table-column prop="level" label="权限等级">
          <template slot-scope="scope">
            <el-button
              v-if="scope.row.level === '0'"
              type="primary"
              size="mini"
              plain
              >一级</el-button
            >
            <el-button
              v-else-if="scope.row.level === '1'"
              type="success"
              size="mini"
              plain
              >二级</el-button
            >
            <el-button
              v-else-if="scope.row.level === '2'"
              type="warning"
              size="mini"
              plain
              >三级</el-button
            >
          </template>
        </el-table-column>
      </el-table>
    </el-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      rightsList: []
    }
  },
  methods: {
    async getRightsList() {
      const type = 'list'
      const { data: res } = await this.$http.get(`rights/${type}`)
      if (res.meta.status !== 200) {
        return this.$message.error(res.meta.msg)
      } else {
        this.rightsList = res.data
      }
    }
  },
  created() {
    this.getRightsList()
  }
}
</script>

<style lang="less" scoped></style>
