import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../components/Login.vue'
import Home from '../components/Home.vue'
import Welcome from '../components/Welcome.vue'
import Users from '../components/user/Users.vue'
import Rights from '../components/power/Rights.vue'
import Roles from '../components/power/Roles.vue'
import Category from '../components/goods/Category.vue'

// 解决ElementUI导航栏中的vue-router在3.0版本以上重复点菜单报错问题
const originalPush = VueRouter.prototype.push
VueRouter.prototype.push = function push(location) {
    return originalPush.call(this, location).catch(err => err)
}
Vue.use(VueRouter)

const routes = [
    { path: '/', redirect: '/login' },
    { path: '/login', component: Login },
    {
        path: '/home',
        component: Home,
        redirect: '/welcome',
        children: [
            { path: '/welcome', component: Welcome },
            { path: '/users', component: Users },
            { path: '/rights', component: Rights },
            { path: '/roles', component: Roles },
            { path: '/categories', component: Category }
        ]
    },
]

const router = new VueRouter({
    routes
})

// 为路由对象，添加 beforeEach 导航守卫
router.beforeEach((to, from, next) => {
    // 如果用户访问的登录页，直接放行
    // to 将要访问的路径，from 从哪个路径过来的， next 是个函数，表示放行
    //  1.next() 放行; 2 next('/login') 强制跳转
    if (to.path === '/login') {
        return next()
    }
    // 从 sessionStorage 中获取 token
    const tokenStr = window.sessionStorage.getItem('token')
    // 没有token，强制跳转
    if (!tokenStr) {
        return next('/login')
    }
    next()
})

export default router
