import Vue from 'vue'
import Router from 'vue-router'

import Home from './components/Home.vue'
import MovieDetail from './components/MovieDetail.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/movie/:id',
      name: 'movieDetail',
      component: MovieDetail
    }
  ]
})
