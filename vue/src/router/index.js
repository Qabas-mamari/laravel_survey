import { createRouter, createWebHistory } from "vue-router";
import store from '../store/index.js';

import Dashboard from '../views/Dashboard.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Surveys from '../views/Surveys.vue';
import SurveyView from '../views/SurveyView.vue';

import DefaultLayout from '../components/DefaultLayout.vue';
import AuthLayout from "../components/AuthLayout.vue" 

// list store the routes 
const routes = [
    {
       path: '/',
       redirect: '/dashboard',
       component: DefaultLayout, 
       // only authenticated users can access to those pages
       meta: {requiresAuth: true}, 
       children: [
        {path: '/dashboard', name: 'Dashboard', component: Dashboard},
        {path: '/surveys', name: 'Surveys', component: Surveys},
        {path: '/surveys/create', name: 'SurveyCreate', component: SurveyView},
        { path: "/surveys/:id", name: "SurveyView", component: SurveyView },
      ]
    },
    {
      path: '/auth',
      redirect: '/login',
      name: "Auth",
      component: AuthLayout,
      meta: {isGuest: true},
      children: [
        {path: '/login', name: 'Login', component: Login},
        {path: '/register', name: 'Register', component: Register}
      ]
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next)=> {
  // console.log(to, from, next);
    // instead of having to check every route record with to.matched.some(record => record.meta.requiresAuth)
    // to.meta.requiresAuth -> to is desired page, check if it require auth and if the user is authenticated 
  if(to.meta.requiresAuth && !store.state.user.token){ //if not 
    next({name: "Login"})
  }else if(store.state.user.token && (to.meta.isGuest)){ //if user login and want to access to login or register page
    next({name: "Dashboard"}) //redirect to dashboard page 
  }else{
    next()
  }
})
export default router;