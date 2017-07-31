startApp({
    'routes': [
        { path: '/', component: require('./news/List.vue.html') },
        { path: '/create', component: require('./news/Edit.vue.html') },
        { path: '/edit/:id', component: require('./news/Edit.vue.html') }
    ]
});
