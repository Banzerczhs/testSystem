import Vue from 'vue';
import Router from 'vue-router';
import Main from '@/view/main/main';
import Login from "@/view/login/login";

Vue.use(Router);

export default new Router({
  mode : 'history',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Main
    },
    {
      path : '/login',
      name : 'Login',
      component : Login 
    }
  ]
})
