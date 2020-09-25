<template>
  <el-container class="home-container">
    <!-- 头部区域 -->
    <el-header style="height:48px;">
      <div>
        <i class="el-icon-setting"></i>
        <span>we3z后台管理</span>
      </div>
      <el-button type="info" @click="logout" size="mini" plain>退出</el-button>
    </el-header>
    <!-- 页面主体区域 -->
    <el-container>
      <!-- 左侧侧边栏 -->
      <el-aside :width="isCollapse ? '64px' : '200px'">
        <!-- 侧边栏菜单区域 -->
        <div class="toggle-button" @click="toggleCollapse">|||</div>
        <el-menu
          background-color="#094da0"
          text-color="#fff"
          active-text-color="#ffd04b"
          unique-opened
          :collapse="isCollapse"
          :collapse-transition="false"
          router
          :default-active="defaultActive"
        >
          <!-- 一级菜单 -->
          <el-submenu
            :index="item.id + ''"
            :key="item.id"
            v-for="item in menuList"
          >
            <!-- 一级菜单模板区域 -->
            <template slot="title">
              <!-- 一级菜单图标 -->
              <i :class="iconsList[item.id]"></i>
              <!-- 一级菜单文本 -->
              <span>{{ item.authName }}</span>
            </template>
            <el-menu-item
              :index="'/' + em.path"
              :key="em.id"
              v-for="em in item.children"
              @click="saveNavState('/' + em.path)"
            >
              <template slot="title">
                <!-- 二级菜单图标 -->
                <i class="el-icon-menu"></i>
                <!-- 二级菜单文本 -->
                <span>{{ em.authName }}</span>
              </template>
            </el-menu-item>
          </el-submenu>
        </el-menu>
      </el-aside>
      <!-- 右侧内容主体 -->
      <el-container>
        <el-main>
          <!-- 主题显示内容 -->
          <router-view></router-view>
        </el-main>
      </el-container>
    </el-container>
  </el-container>
</template>

<script>
export default {
  data() {
    return {
      // 左侧菜单数据
      menuList: [],
      iconsList: {
        '125': 'iconfont icon-user',
        '103': 'iconfont icon-tijikongjian',
        '101': 'iconfont icon-shangpin',
        '102': 'iconfont icon-danju',
        '145': 'iconfont icon-baobiao'
      },
      isCollapse: false,
      // 被激活的链接地址
      defaultActive: ''
    }
  },
  methods: {
    logout() {
      window.sessionStorage.clear()
      this.$router.push('/login')
    },
    // 获取所有菜单
    async getMenuList() {
      const { data: res } = await this.$http.get('menus')
      if (res.meta.status !== 200) {
        return this.$message.error(res.meta.msg)
      }
      this.menuList = res.data
    },
    // 点击按钮切换菜单
    toggleCollapse() {
      this.isCollapse = !this.isCollapse
    },
    // 保存点击路径
    saveNavState(path) {
      window.sessionStorage.setItem('activePath', path)
      this.defaultActive = path
    }
  },
  created() {
    this.getMenuList()
    this.defaultActive = window.sessionStorage.getItem('activePath')
  }
}
</script>

<style lang="less" scoped>
.home-container {
  height: 100%;
}
.el-header {
  background-color: #0b448a;
  display: flex;
  justify-content: space-between;
  padding-left: 0;
  align-items: center;
  color: #ffffff;
  font-size: 15px;

  > div {
    display: flex;
    align-items: center;
    padding-left: 20px;
    > i {
      font-size: 20px;
    }
    > span {
      font-weight: bold;
      margin-left: 5px;
      line-height: 20px;
    }
  }
}

.el-aside {
  background-color: #0b448a;
  .el-menu {
    border-right: none;
  }
  .iconfont  {
    color: #ffffff;
  }
  .el-icon-menu {
    color: #ffffff;
  }
}

.el-main {
  background-color: #ffffff;
}

.iconfont {
  margin-right: 8px;
}

.toggle-button {
  background-color: #0b448a;
  font-size: 10px;
  line-height: 24px;
  color: #ffffff;
  text-align: center;
  letter-spacing: 0.2em;
  cursor: pointer;
}
</style>
