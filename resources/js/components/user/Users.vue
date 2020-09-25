<template>
  <div>
    <!-- 面包屑导航区域 -->
    <el-breadcrumb separator=">">
      <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>用户管理</el-breadcrumb-item>
      <el-breadcrumb-item>用户列表</el-breadcrumb-item>
    </el-breadcrumb>

    <!-- 卡片视图区域 -->
    <el-card class="box-card">
      <!-- 搜索与添加区域 -->
      <el-row :gutter="20">
        <el-col :span="7">
          <el-input
            placeholder="请输入内容"
            class="input-with-select"
            v-model="queryInfo.query"
            @clear="clearSearchInput"
            clearable
          >
            <el-button slot="append" icon="el-icon-search" @click="getUserList"></el-button>
          </el-input>
        </el-col>
        <el-col :span="4">
          <el-button type="primary" @click="addDialogVisiable = true">添加用户</el-button>
        </el-col>
      </el-row>
      <el-table :data="userList" stripe style="width: 100%;" border>
        <el-table-column type="index" label="序号"></el-table-column>
        <el-table-column prop="username" label="姓名" width="180"></el-table-column>
        <el-table-column prop="email" label="邮箱" width="180"></el-table-column>
        <el-table-column prop="mobile" label="电话" width="180"></el-table-column>
        <el-table-column prop="role_name" label="角色"></el-table-column>
        <el-table-column label="状态">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.mg_state" @change="changeUserStatus(scope.row)"></el-switch>
          </template>
        </el-table-column>
        <el-table-column label="操作" width="180">
          <template slot-scope="scope">
            <!-- 分配角色按钮 -->
            <el-tooltip effect="dark" content="为用户分配角色" placement="top-start" :enterable="false">
              <el-button
                type="warning"
                size="mini"
                icon="el-icon-setting"
                @click="allowUserRole(scope.row)"
              ></el-button>
            </el-tooltip>
            <!-- 编辑用户按钮 -->
            <el-tooltip effect="dark" content="编辑用户" placement="top-start" :enterable="false">
              <el-button
                type="primary"
                size="mini"
                icon="el-icon-edit"
                @click="editUser(scope.row.id)"
              ></el-button>
            </el-tooltip>

            <!-- 删除用户按钮 -->
            <el-tooltip effect="dark" content="删除用户" placement="top-start" :enterable="false">
              <el-button
                type="danger"
                size="mini"
                icon="el-icon-delete"
                @click="deleteUser(scope.row.id)"
              ></el-button>
            </el-tooltip>
          </template>
        </el-table-column>
      </el-table>
      <el-pagination
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        :current-page="queryInfo.pagenum"
        :page-sizes="[2, 3, 5, 10]"
        :page-size="queryInfo.pagesize"
        layout="total, sizes, prev, pager, next, jumper"
        :total="total"
      ></el-pagination>
    </el-card>
    <!-- 添加用户的对话框 -->
    <el-dialog title="添加用户" :visible.sync="addDialogVisiable" width="30%" @close="closeAddUserForm">
      <!-- 内容主体区域 -->
      <el-form
        ref="addUserFormRef"
        label-position="right"
        label-width="80px"
        :model="addUserForm"
        :rules="addUserFormRules"
      >
        <el-form-item label="用户名" prop="username">
          <el-input v-model="addUserForm.username"></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input v-model="addUserForm.password" type="password"></el-input>
        </el-form-item>
        <el-form-item label="邮箱" prop="email">
          <el-input v-model="addUserForm.email"></el-input>
        </el-form-item>
        <el-form-item label="手机" prop="mobile">
          <el-input v-model="addUserForm.mobile"></el-input>
        </el-form-item>
      </el-form>
      <!-- 底部区域 -->
      <span slot="footer" class="dialog-footer">
        <el-button @click="closeAddUserForm">取 消</el-button>
        <el-button type="primary" @click="addUserFormHandle">确 定</el-button>
      </span>
    </el-dialog>

    <!-- 编辑用户对话框 -->
    <el-dialog
      title="编辑用户信息"
      :visible.sync="editDialogVisiable"
      width="30%"
      @close="closeEditUserForm"
    >
      <!-- 内容主体区域 -->
      <el-form
        ref="editUserFormRef"
        label-position="right"
        label-width="80px"
        :model="editUserForm"
        :rules="editUserFormRules"
      >
        <el-input v-model="editUserForm.id" type="hidden"></el-input>
        <el-form-item label="用户名">
          <el-input v-model="editUserForm.username" :disabled="true"></el-input>
        </el-form-item>
        <el-form-item label="邮箱" prop="email">
          <el-input v-model="editUserForm.email"></el-input>
        </el-form-item>
        <el-form-item label="手机" prop="mobile">
          <el-input v-model="editUserForm.mobile"></el-input>
        </el-form-item>
      </el-form>
      <!-- 底部区域 -->
      <span slot="footer" class="dialog-footer">
        <el-button @click="closeEditUserForm">取 消</el-button>
        <el-button type="primary" @click="editUserFormHandle">确 定</el-button>
      </span>
    </el-dialog>
    <!-- 分配角色对话框 -->
    <el-dialog title="提示" :visible.sync="allowUserRoleVisable" width="30%">
      <p>当前的用户: {{nowUserName}}</p>
      <p>当前的角色: {{nowRoleName}}</p>
      <p>
        <span>分配新角色 </span>
        <el-select v-model="checkedRoleId" placeholder="请选择" size="medium">
          <el-option v-for="item in roleList" :key="item.id" :label="item.roleName" :value="item.id"></el-option>
        </el-select>
      </p>
      <span slot="footer" class="dialog-footer">
        <el-button @click="closeUserAllowRoleDialog">取 消</el-button>
        <el-button type="primary" @click="submitUserAllowRole">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data() {
    // 验证邮箱规则
    var checkEmail = (rule, value, callback) => {
      if (!value) {
        return callback(new Error('请输入邮箱'))
      } else {
        // 正则验证邮箱
        const regEmail = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/
        if (!regEmail.test(value)) {
          return callback(new Error('邮箱格式不正确'))
        }
        callback()
      }
    }
    // 验证手机号规则
    var checkMobile = (rule, value, callback) => {
      if (!value) {
        return callback(new Error('请输入手机号'))
      } else {
        // 正则验证手机号
        const regMobile = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/
        if (!regMobile.test(value)) {
          return callback(new Error('手机号格式不正确'))
        }
        callback()
      }
    }
    return {
      userList: [],
      total: 1,
      queryInfo: {
        query: '',
        // 当前页数
        pagenum: 1,
        pagesize: 5,
      },
      // 控制添加用户对话框的显示和隐藏
      addDialogVisiable: false,
      // 添加用户数据
      addUserForm: {
        username: '',
        password: '',
        email: '',
        mobile: '',
      },
      // 表单验证规则
      addUserFormRules: {
        username: [
          { required: true, message: '请输入用户名', trigger: 'blur' },
          {
            min: 3,
            max: 15,
            message: '用户名的长度在3~15个字符',
            trigger: 'blur',
          },
        ],
        password: [
          { required: true, message: '请输入密码', trigger: 'blur' },
          {
            min: 6,
            max: 15,
            message: '密码的长度在3~15个字符',
            trigger: 'blur',
          },
        ],
        email: [
          { required: true, message: '请输入邮箱', trigger: 'blur' },
          { validator: checkEmail, trigger: 'blur' },
        ],
        mobile: [
          { required: true, message: '请输入手机号', trigger: 'blur' },
          { validator: checkMobile, trigger: 'blur' },
        ],
      },
      // 控制编辑用户信息对话框的显示和隐藏
      editDialogVisiable: false,
      editUserForm: {
        id: '',
        username: '',
        email: '',
        mobile: '',
      },
      editUserFormRules: {
        email: [
          { required: true, message: '请输入邮箱', trigger: 'blur' },
          { validator: checkEmail, trigger: 'blur' },
        ],
        mobile: [
          { required: true, message: '请输入手机号', trigger: 'blur' },
          { validator: checkMobile, trigger: 'blur' },
        ],
      },
      // 分配用户角色
      allowUserRoleVisable: false,
      // 角色列表
      roleList: [],
      // 选中的角色
      checkedRoleId: '',
      allowRoleUserId: '',
      nowUserName: '',
      nowRoleName: ''
    }
  },
  methods: {
    // 获取相关数据
    async getUserList() {
      const { data: res } = await this.$http.get('/users', {
        params: this.queryInfo,
      })
      if (res.meta.status !== 200) {
        return this.$message.error(res.meta.msg)
      } else {
        this.userList = res.data.users
        this.total = res.data.total
      }
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
    // 监听 switch 开关状态,修改用户状态
    async changeUserStatus(userInfo) {
      const { data: res } = await this.$http.put(
        `/users/${userInfo.id}/state/${userInfo.mg_state}`
      )
      if (res.meta.status !== 200) {
        // 更新失败，按钮不允许变动
        userInfo.mg_state = !userInfo.mg_state
        return this.$message.error(res.meta.msg)
      } else {
        return this.$message.success(res.meta.msg)
      }
    },
    // 情况搜索框事件
    clearSearchInput() {
      this.queryInfo.query = ''
      this.getUserList()
    },
    // 关闭添加用户数据框
    closeAddUserForm() {
      this.addDialogVisiable = false
      this.$refs.addUserFormRef.resetFields()
    },
    // 保存新增数据
    addUserFormHandle() {
      this.$refs.addUserFormRef.validate(async (valid) => {
        // 进行数据验证
        if (!valid) {
          return
        }
        const { data: res } = await this.$http.post('/users', this.addUserForm)
        if (res.meta.status !== 201) {
          return this.$message.error(res.meta.msg)
        } else {
          this.getUserList()
          this.addDialogVisiable = false
          return this.$message.success(res.meta.msg)
        }
      })
    },
    // 打开编辑用户信息界面
    async editUser(id) {
      const { data: res } = await this.$http.get(`/users/${id}`)
      if (res.meta.status !== 200) {
        return this.$message.error(res.meta.msg)
      } else {
        this.editUserForm.id = res.data.id
        this.editUserForm.username = res.data.username
        this.editUserForm.email = res.data.email
        this.editUserForm.mobile = res.data.mobile
        this.editDialogVisiable = true
      }
    },
    // 关闭编辑用户信息界面
    closeEditUserForm() {
      this.editDialogVisiable = false
      this.$refs.editUserFormRef.resetFields()
    },
    // 处理编辑用户功能
    editUserFormHandle() {
      this.$refs.editUserFormRef.validate(async (valid) => {
        // 进行数据验证
        if (!valid) {
          return
        }
        const { data: res } = await this.$http.put(
          `users/${this.editUserForm.id}`,
          {
            email: this.editUserForm.email,
            mobile: this.editUserForm.mobile,
          }
        )
        if (res.meta.status !== 200) {
          return this.$message.error(res.meta.msg)
        } else {
          // 修改页面显示数据
          var index = this.userList.findIndex(function (item) {
            return item.id === res.data.id
          })
          this.userList[index].email = res.data.email
          this.userList[index].mobile = res.data.mobile
          // 关闭对话框
          this.editDialogVisiable = false
          // 给出提示
          return this.$message.success(res.meta.msg)
        }
      })
    },
    // 删除角色
    deleteUser(id) {
      this.$confirm('此操作将永久删除该用户, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      })
        .then(async () => {
          const { data: res } = await this.$http.delete(`users/${id}`)
          if (res.meta.status !== 200) {
            this.$message.error(res.meta.msg)
          } else {
            this.getUserList()
            this.$message.success(res.meta.msg)
          }
        })
        .catch(() => {
          this.$message.info('已取消删除')
        })
    },
    // 分配角色权限
    async allowUserRole(userInfo) {
      // 查询用户信息
      const { data: resUser } = await this.$http.get(`users/${userInfo.id}`)
      if (resUser.meta.status !== 200) {
        return this.$message.error(resUser.meta.msg)
      }
      // 获取权限角色列表
      const { data: resRole } = await this.$http.get('roles')
      if (resRole.meta.status !== 200) {
        return this.$message.error(resRole.meta.msg)
      }
      this.checkedRoleId = resUser.data.role_id
      this.allowRoleUserId = userInfo.id
      this.roleList = resRole.data
      this.nowUserName = userInfo.username
      this.nowRoleName = userInfo.role_name
      this.allowUserRoleVisable = true
    },
    // 关闭用户分配角色弹框
    closeUserAllowRoleDialog() {
      this.checkedRoleId = ''
      this.allowRoleUserId = ''
      this.allowUserRoleVisable = false
      this.nowUserName = ''
      this.nowRoleName = ''
      this.roleList = []
    },
    // 提交用户分配角色权限
    async submitUserAllowRole() {
      const userId = this.allowRoleUserId
      const roleId = this.checkedRoleId
      const { data: res } = await this.$http.put(`users/${userId}/role`, {
        rid: roleId,
      })
      if (res.meta.status !== 200) {
        return this.$message.error(res.meta.msg)
      }
      this.$message.success(res.meta.msg)
      this.closeUserAllowRoleDialog()
      this.getUserList()
    },
  },
  created() {
    // 初始化数据
    this.getUserList()
  },
}
</script>

<style lang="less" scoped>
.el-table {
  margin-top: 15px;
  font-size: 12px;
}
</style>
