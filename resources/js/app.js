require('./bootstrap');

require('moment');

import Vue from 'vue';
import Vuex from 'vuex'
import store from './store/store'
import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';
import { Datetime } from 'vue-datetime'
// You need a specific loader for CSS files
import 'vue-datetime/dist/vue-datetime.css'
import { Settings } from 'luxon'
import VueFilterDateFormat from 'vue-filter-date-format';

import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

Settings.defaultLocale = 'es'

Vue.use(Datetime)

Vue.mixin({ methods: { route } });
Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);
Vue.use(VueFilterDateFormat, {
    dayOfWeekNames: [
      'Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves',
      'Viernes', 'Sabado'
    ],
    dayOfWeekNamesShort: [
      'Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'
    ],
    monthNames: [
      'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
      'Julio', 'Agosto', 'Septiembre', 'Octobre', 'Noviembre', 'Diciembre'
    ],
    monthNamesShort: [
      'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
      'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'
    ]
  });

const app = document.getElementById('app');



new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
        store: store
}).$mount(app);
