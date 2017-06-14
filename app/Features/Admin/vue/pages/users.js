startApp({
    'routes': [
        { path: '/', component: require('./users/List.vue.html') },
        { path: '/profile', component: require('./users/Profile.vue.html') }
    ]
});
