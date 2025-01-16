<template>
    <div class="row">
        <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                    عدد الطلبات
                                </p>
                                <h5 class="font-weight-bolder mb-0">{{ trecks.total }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-start">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                    عدد الزوار
                                </p>
                                <h5 class="font-weight-bolder mb-0">{{ states.deviceCount }} / <span class="text-success" > {{states.deviceCountOnline}}</span> </h5>
                            </div>
                        </div>
                        <div class="col-4 text-start">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                    عدد المستخدمين
                                </p>
                                <h5 class="font-weight-bolder mb-0">{{ states.userCount }} / <span class="text-success" > {{states.userCountOnline}}</span> </h5>
                            </div>
                        </div>
                        <div class="col-4 text-start">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row my-3" v-if="!show">
        <div class=" col-3 d-flex align-items-center">
            <div class="input-group"><span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                <input type="text" class="form-control" placeholder="البحث" v-model="query" @keyup="getByType"></div>
        </div>
        <div class="col-3">
               
               <a :href="exportUrl" class="btn btn-success">
                   <i class="fas fa-download"></i> تصدير البيانات
               </a>
           </div>
        <div class="col-3">
            <multiselect v-model="selectedApp" @select="getByType" mode="single" :close-on-select="true" :options="[
                { value: 'all', label: 'جميع تطبيقات' },
                { value: 'main', label: 'تطبيق الاساسي' },
                { value: 'player', label: 'تطبيق البلاير' },
            ]" />
        </div>
        <div class="col-3">
            <multiselect v-model="selectedFilter" @select="getByType" mode="single" :close-on-select="true" :options="[
                { value: 'count_error', label: 'error' },
                { value: 'count_success', label: 'success' },
            ]" />
        </div>

       

    </div>
    <div class="row my-3">
        <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="row mb-3">
                        <div class="col-6">
                            <h6 v-if="!show">طلبات الاعلانات </h6>
                            <div v-else class="col-12 m-auto me-2" v-if="adDetails.device != null" >
                                <p class="text-xs font-weight-bold mb-1"> id : {{ adDetails.device.id }} <span class="text-danger" v-if="adDetails.device.blocked === 1"> (blocked) </span></p>
                                <p class="text-xs font-weight-bold mb-1"> {{ adDetails.app_info.app_name }} V{{ adDetails.app_info.app_version }} / {{ adDetails.app_info.app_build_number }} </p>
                                <p class="text-xs text-secondary mb-1"> build signature : {{ adDetails.app_info.app_build_signature }} </p>
                                <p class="text-xs text-secondary mb-1"> package name : {{ adDetails.app_info.app_package_name }} </p>
                                <p class="text-xs text-secondary mb-1"> installer store : {{ adDetails.app_info.app_installerStore }} </p>
                                <p class="text-xs text-secondary mb-0"> system : {{ adDetails.operating_system }} </p>
                                <p class="text-xs text-secondary mb-0"> device : {{ adDetails.device.device}}</p>
                                <p class="text-xs text-secondary mb-0"> ip : {{ adDetails.device.ip}}</p>
                                <p class="text-xs text-secondary mb-0"> Last Online : {{ adDetails.device.last_activity}}</p>
                                <p class="text-xs text-secondary mb-0"> type :
                                    <span  v-if="adDetails.user != null" class="badge badge-sm bg-gradient-success">مستخدم</span>
                                    <span  v-if="adDetails.device != null" class="badge badge-sm bg-gradient-warning">زائر</span>
                                </p>
                                <p class="text-xs font-weight-bold mb-1 text-success "> count success : {{ adDetails.count_ads_success }} </p>
                                <p class="text-xs font-weight-bold mb-1 text-danger"> count error : {{ adDetails.count_ads_failed }} </p>
                                <p class="text-xs text-secondary mb-1"> last show success {{ adDetails.last_success_ads_at }}</p>
                                <p class="text-xs text-secondary mb-1"> last show error {{ adDetails.last_failed_ads_at }}</p>
                            </div>
                        </div>
                        <div class="col-6 text-start">
                            <a class="btn  bg-gradient-dark mb-0" v-if="show" @click="back()" href="javascript:;"><i class="ni ni-bold-right btn-sm" aria-hidden="true"></i>&nbsp;&nbsp;رجوع </a>

                            <!-- <div class="dropdown float-start ps-4">
                            <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-v text-secondary"></i>
                            </a>
                             <ul class="dropdown-menu px-2 py-3 me-n4" aria-labelledby="dropdownTable">
                                <li>
                                    <a class="dropdown-item border-radius-md" href="javascript:;">عمل</a>
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md" href="javascript:;">عمل آخر</a>
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md" href="javascript:;">شيء آخر هنا</a>
                                </li>
                            </ul>
                        </div> -->
                        </div>
                    </div>
                </div>
                <div v-if="!show">
                    <div v-if="trecks !== null" class="card-body p-0 pb-2">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>

                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder ">
                                        المستخدم / زائر
                                    </th>
<!--                                    <th-->
<!--                                        class="col-sm-2 text-center text-uppercase text-secondary text-xs font-weight-bolder ">-->
<!--                                        نوع-->
<!--                                    </th>-->
                                    <th
                                        class=" text-uppercase text-secondary text-xs font-weight-bolder ">
                                        بيانات تطبيق
                                    </th>
                                    <th
                                        class=" text-uppercase text-secondary text-xs font-weight-bolder ">
                                        بيانات الاي بي
                                    </th>
                                    <th
                                        class=" text-uppercase text-secondary text-xs font-weight-bolder ">
                                        بيانات الاعلانات
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        Action
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(p, index) in paginated" :key="index">
                                        <td>
                                            <div v-if="p.user != null">
                                                {{ p.user.id + ' ' + p.user.name }}
                                                {{ p.user.email }}
                                            </div>
                                            <div class="col-sm-3 m-auto me-2"  v-if="p.user != null" >
                                                <p class="text-xs font-weight-bold mb-1"> id : {{ p.user.id }} <span class="text-danger" v-if="p.user.blocked === 1"> (blocked) </span></p>
                                                <p class="text-xs text-secondary mb-0"> name : {{ p.user.name }}</p>
                                                <p class="text-xs text-secondary mb-0"> email : {{ p.user.email}}</p>
                                                <p class="text-xs text-secondary mb-0"> system : {{ p.operating_system }} </p>
                                                <p class="text-xs text-secondary mb-0"> Last Online : {{ p.user.last_activity}}</p>
                                            </div>
                                            <div class="col-sm-3 m-auto me-2" v-if="p.device != null" >
                                                <p class="text-xs font-weight-bold mb-1"> id : {{ p.device.id }} <span class="text-danger" v-if="p.device.blocked === 1"> (blocked) </span></p>
                                                <p class="text-xs text-secondary mb-0"> system : {{ p.operating_system }} </p>
                                                <p class="text-xs text-secondary mb-0"> device : {{ p.device.device}}</p>
                                                <p class="text-xs text-secondary mb-0"> ip : {{ p.device.ip}}</p>
                                                <p class="text-xs text-secondary mb-0"> Last Online : {{ p.device.last_activity}}</p>
                                                <p class="text-xs text-secondary mb-0"> type :
                                                    <span  v-if="p.user != null" class="badge badge-sm bg-gradient-success">مستخدم</span>
                                                    <span  v-if="p.device != null" class="badge badge-sm bg-gradient-warning">زائر</span>
                                                </p>
                                            </div>

                                        </td>
<!--                                        <td class=" align-middle text-center ">-->
<!--                                            <div class="d-flex justify-content-center">-->
<!--                                                <span  v-if="p.user != null"-->
<!--                                                      class="badge badge-sm bg-gradient-success">مستخدم</span>-->
<!--                                                <span  v-if="p.device != null"-->
<!--                                                      class="badge badge-sm bg-gradient-warning">زائر</span>-->
<!--                                            </div>-->
<!--                                        </td>-->
                                        <td class="align-middle text-center">
                                            <div class="col-sm-3 m-auto me-2" >
                                                <p class="text-xs font-weight-bold mb-1"> {{ p.app_info.app_name }} V{{ p.app_info.app_version }} / {{ p.app_info.app_build_number }} </p>
<!--                                                <p class="text-xs text-secondary mb-1"> build signature : {{ p.app_info.app_build_signature }} </p>-->
                                                <p class="text-xs text-secondary mb-1"> package name : {{ p.app_info.app_package_name }} </p>
                                                <p class="text-xs text-secondary mb-1"> installer store : {{ p.app_info.app_installerStore }} </p>
                                                <p class="text-xs text-secondary mb-1"> is_guest : {{ p.app_info.is_guest }} / is_vip : {{ p.app_info.is_vip }} </p>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="col-sm-3 m-auto me-2" >
                                                <p class="text-xs font-weight-bold mb-1"> ip : {{ p.ip_info.ipAddress }} </p>
                                                <!--                                                <p class="text-xs text-secondary mb-1"> build signature : {{ p.app_info.app_build_signature }} </p>-->
                                                <p class="text-xs text-secondary mb-1"> {{ p.ip_info.countryName }}  / {{ p.ip_info.cityName }}</p>
                                                <p class="text-xs text-secondary mb-1"> {{ p.ip_info.regionName}}</p>
                                                <p class="text-xs text-secondary mb-1"> is vpn or proxy : {{ p.ip_info.isProxy }} </p>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="col-sm-3 m-auto me-2" >
                                                <p class="text-xs font-weight-bold mb-1 text-success "> count success : {{ p.count_ads_success }} </p>
                                                <p class="text-xs font-weight-bold mb-1 text-danger"> count error : {{ p.count_ads_failed }} </p>

                                                <!--                                                <p class="text-xs text-secondary mb-1"> build signature : {{ p.app_info.app_build_signature }} </p>-->
                                                <p class="text-xs text-secondary mb-1"> last show success {{ p.last_success_ads_at }}</p>
                                                <p class="text-xs text-secondary mb-1"> last show error {{ p.last_failed_ads_at }}</p>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <a class="btn btn-link px-3 mb-0" @click="details(p)"
                                               :class="'text-info'"
                                               href="javascript:;">
                                                <i class="fas fa-pencil-alt ms-2 text-info"
                                                   aria-hidden="true"></i>
                                                تفاصيل اعلانات
                                            </a>
                                            <a class="btn btn-link px-3 mb-0" @click="block(p.id)"
                                               :class="(p.user && p.user.blocked === 1) || (p.device && p.device.blocked === 1) ? 'text-dark' : 'text-danger'"
                                               href="javascript:;"><i class="fas fa-pencil-alt ms-2"
                                                                      :class="(p.user && p.user.blocked === 1) || (p.device && p.device.blocked === 1) ? 'text-dark' : 'text-danger'"
                                                                      aria-hidden="true"></i>{{(p.user && p.user.blocked === 1) || (p.device && p.device.blocked === 1) ? 'الغاء الحظر' : 'حظر المستخدم'}}</a>
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                            <div class="paginate-div" v-if="Object.keys(trecks).length > 0 && trecks.total > 6">
                                <vue-awesome-paginate :total-items="trecks.total" :items-per-page="trecks.per_page"
                                                      :max-pages-shown="5" :current-page="trecks.current_page"
                                                      :on-click="onClickHandler" />
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else-if="show">
                    <div v-if="ads !== null" class="card-body p-0 pb-2">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder ">
                                        بيانات الاعلان
                                    </th>
                                    <th
                                        class=" text-uppercase text-secondary text-xs font-weight-bolder ">
                                        بيانات تطبيق
                                    </th>
                                    <th
                                        class=" text-uppercase text-secondary text-xs font-weight-bolder ">
                                        بيانات الاي بي
                                    </th>
                                    <th
                                        class=" text-uppercase text-secondary text-xs font-weight-bolder ">
                                        ظهور
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(p, index) in paginated2" :key="index">
                                    <td>
                                        <div class="col-sm-3 m-auto me-2" >
                                            <p class="text-xs text-secondary mb-0"> provider : {{ p.provider }}</p>
                                            <p class="text-xs text-secondary mb-0"> type : {{ p.ad_type }}</p>
                                            <p class="text-xs text-secondary mb-0"> ad_shown : {{ p.ad_shown}}</p>
                                            <p class="text-xs text-secondary mb-0"> shown_at : {{ p.shown_at}}</p>
                                            <p class="text-xs text-secondary mb-0"> info : {{ p.ad_info }} </p>
                                        </div>
                                    </td>

                                    <td class="align-middle text-center">
                                        <div class="col-sm-3 m-auto me-2" >
                                            <p class="text-xs font-weight-bold mb-1"> {{ p.app_info.app_name }} V{{ p.app_info.app_version }} / {{ p.app_info.app_build_number }} </p>
                                            <!--                                                <p class="text-xs text-secondary mb-1"> build signature : {{ p.app_info.app_build_signature }} </p>-->
                                            <p class="text-xs text-secondary mb-1"> package name : {{ p.app_info.app_package_name }} </p>
                                            <p class="text-xs text-secondary mb-1"> installer store : {{ p.app_info.app_installerStore }} </p>
                                            <p class="text-xs text-secondary mb-1"> is_guest : {{ p.app_info.is_guest }} / is_vip : {{ p.app_info.is_vip }} </p>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="col-sm-3 m-auto me-2" >
                                            <p class="text-xs font-weight-bold mb-1"> ip : {{ p.ip_info.ipAddress }} </p>
                                            <!--                                                <p class="text-xs text-secondary mb-1"> build signature : {{ p.app_info.app_build_signature }} </p>-->
                                            <p class="text-xs text-secondary mb-1"> {{ p.ip_info.countryName }}  / {{ p.ip_info.cityName }}</p>
                                            <p class="text-xs text-secondary mb-1"> {{ p.ip_info.regionName}}</p>
                                            <p class="text-xs text-secondary mb-1"> is vpn or proxy : {{ p.ip_info.isProxy }} </p>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="col-sm-3 m-auto me-2" >
                                        <span v-if="p.ad_shown == 0" class="badge badge-sm bg-gradient-danger">حدث خطأ</span>
                                        <span v-else-if="p.ad_shown == 1" class="badge badge-sm bg-gradient-success">ناجح</span>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>

                            </table>
                            <div class="paginate-div" v-if="Object.keys(ads).length > 0 && ads.total > 6">
                                <vue-awesome-paginate :total-items="ads.total" :items-per-page="ads.per_page"
                                                      :max-pages-shown="5" :current-page="ads.current_page"
                                                      :on-click="onClickHandler" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import {
    notifications
} from "../mixins/notifications";
import {
    settings
} from "../mixins/settings";
import Switch from '../core/Switch.vue';
import {track} from "@vue/reactivity";

export default {
    data() {
        return {
            count: 0,
            trecks: [],
            states: [],
            show: false,
            adDetails: {},
            ads: [],
            query: '',
            dataSearch: [],
            selectedApp: 'all',
            selectedFilter: '',
            paginate: ["trecks","ads"],

        };
    },
    async mounted() {
        this.url = url;
        let response = await axios.get(url + "/admin/ad/tracks/get");
        this.trecks = response.data;
        this.selectedApp = 'all';
        let responseState = await  axios.get(url + "/admin/state/ad/track");
        this.states = responseState.data;
    },
    computed: {
        paginated() {
            return this.trecks.data;
        },
        paginated2() {
            return this.ads.data;
        },
        onUrl() {
            var logo = this.form.category.logo;
            var category;
            if (this.form.category.id != null) {
                category = '&category=' + this.form.category.id;
            } else {
                category = '';
            }
            return url + '/admin/remove/categories/image?logo=' + logo + category;
        },

        exportUrl() {
            let url = '/admin/export-track-ads';
            const params = [];

            if (this.selectedApp && this.selectedApp !== 'all') {
                params.push(`app=${this.selectedApp}`);
            }
            if (this.selectedFilter) {
                params.push(`filter=${this.selectedFilter}`);
            }
            if (this.query) {
                params.push(`query=${this.query}`);
            }

            if (params.length > 0) {
                url += `?${params.join('&')}`;
            }

            return url;
        }
    

    },
    methods: {
        async details(treck) {
            this.url = url;
            this.adDetails = treck;
            let response = await axios.get(url + "/admin/ad/tracks/get/" + treck.id);
            this.ads = response.data;
            this.show = true;
        },
        async back() {
            this.show = false;
            this.adDetails = {};
            this.ads = [];
        },
        async getByType() {
            const params = {};
            if (this.selectedApp && this.selectedApp.length > 0) {
                params.app = this.selectedApp;
            }
            if (this.selectedFilter && this.selectedFilter.length > 0) {
                params.filter = this.selectedFilter;
            }
            if (this.query && this.query.length > 0) {
                params.query = this.query;
            }
            let response = await axios.get(url + "/admin/ad/tracks/get", {
                params: params
            });
            this.trecks = response.data;
        },
        async onClickHandler(page) {
            const params = {};

            if (this.selectedApp && this.selectedApp.length > 0) {
                params.app = this.selectedApp;
            }
            params.page = page;
            let response = await axios.get(url + "/admin/ad/tracks/get", {
                params: params
            });
            this.trecks = response.data;
        },

        // delete a genre from database
        block(id, index) {
            this.showConfirm(async () => {
                try {
                    let response = await axios.post(
                        url + "/admin/ad/tracks/blocked/" + id
                    );
                    let dataIndex = this.trecks.data.findIndex(data => data.id === id);
                    if (dataIndex >= 0) {
                        this.trecks.data[dataIndex] = response.data.body;
                    }
                    if(response.data.status === 200 ) {
                        this.showSuccess(response.data.message);
                    } else {
                        this.showError(response.data.message);
                    }
                } catch (error) {
                    this.showError();
                }
            });
        },

    },
    components: {
        Switch
    },
    mixins: [notifications, settings]
};
</script>

<style scoped>

</style>
