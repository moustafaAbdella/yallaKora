/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import Toaster from "@meforma/vue-toaster";
import Vuex from 'vuex';
import VueAwesomePaginate from "vue-awesome-paginate";
import Multiselect from '@vueform/multiselect'
import VueSweetalert2 from 'vue-sweetalert2';
import Toggle from '@vueform/toggle';
import Datepicker from '@vuepic/vue-datepicker';
import { VueDraggableNext } from 'vue-draggable-next'
// import the necessary css file
import "vue-awesome-paginate/dist/style.css";
import "@vueform/multiselect/themes/default.css";
import 'sweetalert2/dist/sweetalert2.min.css';
import '@vueform/toggle/themes/default.css';
import '@vuepic/vue-datepicker/dist/main.css';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});
app.use(Toaster);
app.use(Vuex);
app.use(VueAwesomePaginate);
app.use(VueSweetalert2);
app.component("multiselect", Multiselect);
app.component("toggle", Toggle);
app.component('Datepicker', Datepicker);
app.component("draggable", VueDraggableNext);


// import ExampleComponent from './components/ExampleComponent.vue';
// app.component('example-component', ExampleComponent);

import HomeComponent from './components/HomeComponent.vue';
app.component('home-component', HomeComponent);

import LeaguesComponent from './components/LeaguesComponent.vue';
app.component('leagues-component', LeaguesComponent);

import TeamsComponent from './components/TeamsComponent.vue';
app.component('teams-component', TeamsComponent);

import SeasonsComponent from './components/SeasonsComponent.vue';
app.component('seasons-component', SeasonsComponent);

import MatchesComponent from './components/MatchesComponent.vue';
app.component('matches-component', MatchesComponent);

import NotificationsComponent from './components/NotificationsComponent.vue';
app.component('notifications-component', NotificationsComponent);

import UsersComponent from './components/UsersComponent.vue';
app.component('users-component', UsersComponent);

import SubscriptionsComponent from './components/SubscriptionsComponent.vue';
app.component('subscriptions-component', SubscriptionsComponent);

import SettingsComponent from './components/SettingsComponent.vue';
app.component('settings-component', SettingsComponent);

import LivetvComponent from './components/LivetvComponent.vue';
app.component('livetv-component', LivetvComponent);

import ServersComponent from './components/ServersComponent.vue';
app.component('servers-component', ServersComponent);

import CategoriesComponent from './components/CategoriesComponent.vue';
app.component('categories-component', CategoriesComponent);

import PlayerSettingsComponent from './components/PlayerSettingsComponent.vue';
app.component('player-settings-component', PlayerSettingsComponent);

import AdTrackComponent from './components/AdTrackComponent.vue';
app.component('ad-track-component', AdTrackComponent);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');
