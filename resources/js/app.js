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
import VueMask from 'v-mask'
import Vuelidate from 'vuelidate'
import Vue2Filters from 'vue2-filters'
import moment from 'moment'
import Nprogress from 'nprogress'
import VueApexCharts from 'vue-apexcharts'
import 'nprogress/nprogress.css';
import VueTour from 'vue-tour'
require('vue-tour/dist/vue-tour.css')

Vue.use(VueTour)

moment.locale('es')
Vue.use(Vuelidate)
Vue.use(VueMask);

Vue.use(VueApexCharts)
Vue.component('apexchart', VueApexCharts)



Vue.use(Nprogress)
Settings.defaultLocale = 'es'

Vue.filter('formatLargeDate', function(value) {
  if (value) {
    return moment(String(value)).format('DD MMMM YYYY')
  }
})

Vue.filter('formatShortDate', function(value) {
  if (value) {
    return moment(String(value)).format('DD/MM/YYYY')
  }
})

Vue.use(Datetime)
Vue.use(Vue2Filters)

Vue.mixin({ methods: { route } });
Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);

Vue.directive('click-outside', {
  bind: function (el, binding, vnode) {
    el.clickOutsideEvent = function (event) {
      // here I check that click was outside the el and his children
      if (!(el == event.target || el.contains(event.target))) {
        // and if it did, call method provided in attribute value
        vnode.context[binding.expression](event);
      }
    };
    document.body.addEventListener('click', el.clickOutsideEvent)
  },
  unbind: function (el) {
    document.body.removeEventListener('click', el.clickOutsideEvent)
  },
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
