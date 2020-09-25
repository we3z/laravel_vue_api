<template>
  <div>
    <!-- 面包屑导航 -->
    <el-breadcrumb separator=">">
      <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>权限管理</el-breadcrumb-item>
      <el-breadcrumb-item>角色列表</el-breadcrumb-item>
    </el-breadcrumb>

    <el-card class="box-card">
      <el-row>
        <el-col>
          <el-button type="primary" size="small" @click="addDialogVisiable = true">添加角色</el-button>
        </el-col>
      </el-row>
      <!-- 角色列表区域 -->
      <el-table :data="rolesList" stripe border>
        <!-- 展开列 -->
        <el-table-column type="expand">
          <template slot-scope="scope2">
            <el-row
              :class="['vcenter', 'bdbottom', index1 === 0 ? 'bdtop' : '']"
              :key="item1.id"
              v-for="(item1, index1) in scope2.row.children"
            >
              <!-- 渲染一级权限 -->
              <el-col :span="5">
                <el-tag closable @close="removeRightById(scope2.row, item1.id)">{{ item1.authName }}</el-tag>
                <i class="el-icon-caret-right"></i>
              </el-col>
              <!-- 渲染二级三级权限 -->
              <el-col :span="19">
                <el-row
                  :class="['vcenter', index2 !== 0 ? 'bdtop' : '']"
                  :key="item2.id"
                  v-for="(item2, index2) in item1.children"
                >
                  <el-col :span="6">
                    <el-tag
                      type="success"
                      closable
                      @close="removeRightById(scope2.row, item2.id)"
                    >{{ item2.authName }}</el-tag>
                    <i class="el-icon-caret-right"></i>
                  </el-col>
                  <el-col :span="18">
                    <el-tag
                      type="warning"
                      :key="item3.id"
                      v-for="item3 in item2.children"
                      closable
                      @close="removeRightById(scope2.row, item3.id)"
                    >{{ item3.authName }}</el-tag>
                  </el-col>
                </el-row>
              </el-col>
            </el-row>
          </template>
        </el-table-column>
        <el-table-column type="index" label="#" width="50"></el-table-column>
        <el-table-column prop="roleName" label="角色名称"></el-table-column>
        <el-table-column prop="roleDesc" label="角色描述"></el-table-column>
        <el-table-column prop="level" label="操作">
          <template slot-scope="scope1">
            <el-button
              type="primary"
              size="mini"
              icon="el-icon-edit"
              @click="editRole(scope1.row.id)"
            >编辑</el-button>
            <el-button
              type="danger"
              size="mini"
              icon="el-icon-delete"
              @click="deleteRole(scope1.row.id)"
            >删除</el-button>
            <el-button
              type="warning"
              size="mini"
              icon="el-icon-setting"
              @click="showSetRightDialog(scope1.row)"
            >分配权限</el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
    <!-- 添加角色对话框 -->
    <el-dialog title="新增角色" :visible.sync="addDialogVisiable" width="50%">
      <el-form :model="addRoleForm" :rules="addRoleFormRule" ref="addRoleFormRef">
        <el-form-item label="角色名称" prop="roleName">
          <el-input v-model="addRoleForm.roleName" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="角色描述" prop="roleDesc">
          <el-input v-model="addRoleForm.roleDesc" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="addDialogVisiable = false">取 消</el-button>
        <el-button type="primary" @click="addRoleFormSubmit">确 定</el-button>
      </div>
    </el-dialog>
    <!-- 修改角色对话框 -->
    <el-dialog title="编辑角色" :visible.sync="editDialogVisiable" width="50%">
      <el-form :model="editRoleForm" :rules="editRoleFormRule" ref="editRoleFormRef">
        <el-input type="hidden" v-model="editRoleForm.roleId" autocomplete="off"></el-input>
        <el-form-item label="角色名称" prop="roleName">
          <el-input v-model="editRoleForm.roleName" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="角色描述" prop="roleDesc">
          <el-input v-model="editRoleForm.roleDesc" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="editDialogVisiable = false">取 消</el-button>
        <el-button type="primary" @click="editRoleFormSubmit">确 定</el-button>
      </div>
    </el-dialog>
    <!-- 分配权限对话框 -->
    <el-dialog title="提示" :visible.sync="showSetRightDialogVisable" width="50%">
      <!-- 树形控件 -->
      <el-tree
        :data="rightList"
        :props="rightTreeProps"
        show-checkbox
        node-key="id"
        :default-expand-all="true"
        :default-checked-keys="defaultRightKeys"
        ref="setRightsRef"
      ></el-tree>
      <span slot="footer" class="dialog-footer">
        <el-button @click="closeShowSetRightsDialog()">取 消</el-button>
        <el-button type="primary" @click="submitSetRights()">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      rolesList: [],
      pagenum: 1,
      total: 1,
      addDialogVisiable: false,
      addRoleForm: {
        roleName: '',
        roleDesc: '',
      },
      // 新增角色相关
      addRoleFormRule: {
        roleName: [
          { required: true, message: '请输入角色名称', trigger: 'blur' },
          {
            min: 2,
            max: 25,
            message: '长度在 2 到 25 个字符',
            trigger: 'blur',
          },
        ],
        roleDesc: [
          { required: true, message: '请输入角色描述', trigger: 'blur' },
          {
            min: 2,
            max: 25,
            message: '长度在 2 到 25 个字符',
            trigger: 'blur',
          },
        ],
      },
      // 编辑角色相关信息
      editDialogVisiable: false,
      editRoleForm: {
        roleId: '',
        roleName: '',
        roleDesc: '',
      },
      editRoleFormRule: {
        roleName: [
          { required: true, message: '请输入角色名称', trigger: 'blur' },
          {
            min: 2,
            max: 25,
            message: '长度在 2 到 25 个字符',
            trigger: 'blur',
          },
        ],
        roleDesc: [
          { required: true, message: '请输入角色描述', trigger: 'blur' },
          {
            min: 2,
            max: 25,
            message: '长度在 2 到 25 个字符',
            trigger: 'blur',
          },
        ],
      },
      // 分配权限相关
      showSetRightDialogVisable: false,
      // 所有权限数据
      rightList: [],
      // 属性控件绑定对象
      rightTreeProps: {
        children: 'children',
        label: 'authName',
      },
      // 默认选中节点的id值
      defaultRightKeys: [],
      // 分配权限的角色ID
      roleIdBySetRights: 0,
    }
  },
  methods: {
    async getRolesList() {
      const { data: res } = await this.$http.get('roles')
      if (res.meta.status !== 200) {
        return this.$message.error(res.meta.msg)
      }
      this.rolesList = res.data
    },
    // 处理添加权限窗口关闭
    handleClose() {
      this.addDialogVisiable = false
      this.addRoleForm.roleName = ''
      this.addRoleForm.roleDesc = ''
    },
    // 处理新增角色
    addRoleFormSubmit() {
      this.$refs.addRoleFormRef.validate(async (valid) => {
        if (!valid) {
          return
        }
        const { data: res } = await this.$http.post('roles', this.addRoleForm)
        if (res.meta.status !== 201) {
          this.$message.error(res.meta.msg)
        } else {
          this.$message.success(res.meta.msg)
          this.addDialogVisiable = false
          this.addRoleForm.roleName = ''
          this.addRoleForm.roleDesc = ''
        }
      })
    },
    // 编辑角色，查询角色详情
    async editRole(id) {
      const { data: res } = await this.$http.get(`roles/${id}`)
      if (res.meta.status !== 200) {
        return this.$message.error(res.meta.msg)
      }
      this.editRoleForm.roleId = res.data.roleId
      this.editRoleForm.roleName = res.data.roleName
      this.editRoleForm.roleDesc = res.data.roleDesc
      this.editDialogVisiable = true
    },
    // 处理编辑角色
    editRoleFormSubmit() {
      this.$refs.editRoleFormRef.validate(async (valid) => {
        if (!valid) {
          return
        }
        const { data: res } = await this.$http.put(
          `roles/${this.editRoleForm.roleId}`,
          this.editRoleForm
        )
        if (res.meta.status !== 200) {
          this.$message.error(res.meta.msg)
        } else {
          this.$message.success(res.meta.msg)
          // 修改父组件显示数据
          const roleIndex = this.rolesList.findIndex((item) => {
            return item.id === res.data.roleId
          })
          this.rolesList[roleIndex].roleName = res.data.roleName
          this.rolesList[roleIndex].roleDesc = res.data.roleDesc
          this.editDialogVisiable = false
          this.editRoleForm.roleId = ''
          this.editRoleForm.roleName = ''
          this.editRoleForm.roleDesc = ''
        }
      })
    },
    // 删除角色
    deleteRole(id) {
      this.$confirm('此操作将永久删除该角色, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      })
        .then(async () => {
          const { data: res } = await this.$http.delete(`roles/${id}`)
          if (res.meta.status !== 200) {
            this.$message.error(res.meta.msg)
          } else {
            this.getRolesList()
            this.$message.success(res.meta.msg)
          }
        })
        .catch(() => {
          this.$message.info('已取消删除')
        })
    },
    // 移除角色权限
    async removeRightById(role, right_id) {
      // 弹框提示用户确定是否确定删除
      const confirmResult = await this.$confirm(
        '此操作将移除该角色此权限, 是否继续?',
        '提示',
        {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning',
        }
      ).catch((err) => err)
      if (confirmResult !== 'confirm') {
        return this.$message.info('取消了删除')
      }
      const { data: res } = await this.$http.delete(
        `roles/${role.id}/rights/${right_id}`
      )
      if (res.meta.status != 200) {
        return this.$message.error('删除权限失败!')
      }
      // 修改该用户下的权限
      role.children = res.data
    },
    // 打开分配权限的弹框
    async showSetRightDialog(role) {
      // 获取所有权限的数据
      const { data: res } = await this.$http.get('rights/tree')
      if (res.meta.status !== 200) {
        return this.$message.error(res.meta.msg)
      }
      // 把获取的数据保存到data中
      this.rightList = res.data
      // 默认勾选的权限
      this.getLeafThirdRightKeys(role, this.defaultRightKeys)
      this.roleIdBySetRights = role.id
      this.showSetRightDialogVisable = true
    },
    // 递归函数获取所有三级节点
    getLeafThirdRightKeys(node, arr) {
      // 如果当前node节点不包含children属性，则是三级节点
      if (!node.children) {
        return arr.push(node.id)
      }
      node.children.forEach((item) => this.getLeafThirdRightKeys(item, arr))
    },
    // 点击取消关闭分配权限弹窗
    closeShowSetRightsDialog() {
      this.showSetRightDialogVisable = false
      this.rightList = []
      this.defaultRightKeys = []
      this.roleIdBySetRights = 0
    },
    async submitSetRights() {
      const keys = [
        ...this.$refs.setRightsRef.getCheckedKeys(),
        ...this.$refs.setRightsRef.getHalfCheckedKeys(),
      ]
      if (keys.length === 0) {
        return this.$message.warning('你尚未选中任意权限')
      }
      const roleId = this.roleIdBySetRights
      const { data: res } = await this.$http.post(`roles/${roleId}/rights`, {
        rids: keys.join(','),
      })
      if (res.meta.status !== 200) {
        return this.$message.error(res.meta.msg)
      }
      this.$message.success(res.meta.msg)
      this.closeShowSetRightsDialog()
      this.getRolesList()
    },
  },
  created() {
    this.getRolesList()
  },
}
</script>

<style lang="less" scoped>
.el-tag {
  margin: 7px;
}
.bdtop {
  border-top: 1px solid #eeeeee;
}
.bdbottom {
  border-bottom: 1px solid #eeeeee;
}
.vcenter {
  display: flex;
  align-items: center;
}
</style>
