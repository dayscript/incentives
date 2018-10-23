
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Passport components
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);


Vue.component('edit-user', require('./components/users/EditUser.vue'));
Vue.component('edit-client', require('./components/clients/EditClient.vue'));
Vue.component('edit-rule', require('./components/rules/EditRule.vue'));
Vue.component('edit-var', require('./components/vars/EditVar.vue'));
Vue.component('edit-template', require('./components/templates/EditTemplate.vue'));


Vue.component('edit-goal', require('./components/goals/EditGoal.vue'));
// Vue.component('avatar',require('vue-avatar/dist/Avatar'));
import Avatar from 'vue-avatar-component'
// import VueI18n from 'vue-i18n'
import Element from 'element-ui'
// import enLocale from 'element-ui/lib/locale/lang/en'
// import esLocale from 'element-ui/lib/locale/lang/es'
// window.Vue.use(VueI18n)
window.Vue.use(Element)
// window.Vue.config.lang = 'es'
// window.Vue.locale('en', enLocale)
// window.Vue.locale('es', esLocale)

const app = new Vue({
    el: '#app',
    components: { Avatar },
    data(){
        return {
            active:0
        }
    }
});
window.vm = app;
