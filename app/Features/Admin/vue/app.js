require('../../../../resources/assets/js/app');

import VueRouter from 'vue-router';

Vue.use(VueRouter);


Vue.component('datepicker', require('./components/DatePicker.vue.html'));

window.appDashboardBreadcrumItem = {name: '首页', url: '/admin/#', icon: 'fa-dashboard'};
window.makeAppBreadcrum = function(name, url, icon) {
    let obj = {name};
    if (url) {
        obj.url = url;
    }
    if (icon) {
        obj.icon = icon;
    }
    return obj;
};

window.startApp = function(options) {
    if (options && options.routes) {
        options.router = new VueRouter({
            routes: options.routes
        });
        options.router.beforeEach((to, from, next) => {
            next();
            
            setTimeout(function() {
                $('body').layout('fix');
            }, 50);
        });

        delete options.routes;
    }

    window.app = new Vue(_.defaults(options, {
        el: '#app',
        components: {
            "app-logo": require('./components/AppLogo.vue.html'),
            "side-menu": require('./components/SideMenu.vue.html'),
            "page-footer": require('./components/PageFooter.vue.html'),
            "navbar": require('./components/Navbar.vue.html')
        }
    }));
};