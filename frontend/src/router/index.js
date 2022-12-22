import Vue from 'vue'
import VueRouter from 'vue-router'
import axios from 'axios'
import useJwt from '@/auth/jwt/useJwt'

// Routes
import { canNavigate } from '@/libs/acl/routeProtection'
import { isUserLoggedIn, getUserData, getHomeRouteForLoggedInUser } from '@/auth/utils'
import apps from './routes/apps'
import dashboard from './routes/dashboard'
/* import uiElements from './routes/ui-elements/index'
import pages from './routes/pages'
import chartsMaps from './routes/charts-maps' */
import formsTable from './routes/forms-tables'
/* import others from './routes/others' */

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    { path: '/', redirect: { name: 'dashboard-ecommerce' } },

    ...dashboard,

    ...formsTable,
    ...apps,

    {
      path: '*',
      redirect: { name: 'error-404' },
    },
  ],
})

router.beforeEach((to, _, next) => {
  const appLoading = document.getElementById('loading-bg')
  if (appLoading) {
    appLoading.style.display = 'block'
  }
  const isLoggedIn = isUserLoggedIn()

  if (!canNavigate(to)) {
    // Redirect to login if not logged in
    if (!isLoggedIn) {
      console.log('not loggedin')
      return next({ name: 'auth-login' })
    }

    // refresh permissions

    // useJwt.setPermissions();
    // refresh permissions END
    // console.log('not-authorized');
    // If logged in => not authorized
    // return next({ name: 'misc-not-authorized' })
  }

  // Redirect if logged in
  if (to.meta.redirectIfLoggedIn && isLoggedIn) {
    console.log('problem')
    const userData = getUserData()
    next('/userLevel'/* getHomeRouteForLoggedInUser(userData ? userData.role : null) */)
  }

  return next()
})

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(() => {
  // Remove initial loading
  const appLoading = document.getElementById('loading-bg')
  if (appLoading) {
    appLoading.style.display = 'none'
  }
})

export default router
