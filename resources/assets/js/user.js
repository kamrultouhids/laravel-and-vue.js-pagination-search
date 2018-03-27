require('./vue-assets');

Vue.component('create-user', require('./components/CreateUser.vue'));
Vue.component('view-user', require('./components/ViewUser.vue'));

var app = new Vue({
    el: '#my-app'
});

