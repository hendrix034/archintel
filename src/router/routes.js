import Login from './../pages/layout/login';
import Master from './../pages/layout/master';
import Dashboard from './../pages/dashboard';
import User from './../pages/user';
import Company from './../pages/company'

const isAuthenticated = () => {
  return localStorage.getItem('user') !== null;
};

const requireAuth = (to, from, next) => {
    if (!isAuthenticated() && to.name !== 'Login') {
      next({ name: 'Login' });
    } else if (isAuthenticated() && to.name === 'Login') {
      next({ name: 'dashboard' });
    } else {
      next();
    }
  };
  

export default [
  {
    name: 'Login',
    path: '/login',
    component: Login,
  },
  {
    path: '/',
    component: Master,
    redirect: '/dashboard',
    children: [
      {
        name: 'dashboard',
        path: 'dashboard',
        component: Dashboard,
        beforeEnter: requireAuth,
      },
      {
        name: 'user',
        path: 'user',
        component: User,
        beforeEnter: requireAuth,
      },
      {
        name: 'company',
        path: 'company',
        component: Company,
        beforeEnter: requireAuth,
      },
    ],
  },
];
