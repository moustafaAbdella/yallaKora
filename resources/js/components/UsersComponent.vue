<template>
<div class="row">
    <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                عدد المستخدمين
                            </p>
                            <h5 class="font-weight-bolder mb-0">{{ count }}</h5>
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
<div class="my-3" v-if="index">
    <div class=" col-3 d-flex align-items-center">
        <div class="input-group"><span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
            <input type="text" class="form-control" placeholder="البحث" v-model="query" @keyup="SEARCH"></div>
    </div>
</div>
<div class="row my-3">
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="row mb-3">
                    <div class="col-6">
                        <h6 v-if="index"> حسابات </h6>
                        <h6 v-if="add"> اضافة حساب </h6>
                        <h6 v-if="edit && form.user != null">
                            <div class="d-flex px-2 py-1">
                                <div>
                                    <img :src="url + '/assets/img/user.png'" height="40" />
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm me-2">
                                        {{ form.user.name }}
                                    </h6>
                                </div>
                            </div>
                        </h6>
                    </div>
                    <div class="col-6 my-auto text-start">
                        <a class="btn bg-gradient-dark mb-0" v-if="index" @click="create()" href="javascript:;"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;اضافة حساب جديد</a>
                        <a class="btn  bg-gradient-dark mb-0" v-if="!index" @click="back()" href="javascript:;"><i class="ni ni-bold-right btn-sm" aria-hidden="true"></i>&nbsp;&nbsp;رجوع </a>

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
            <div v-if="index">
                <div v-if="( users !== null && query === null) ||
                Object.keys(query).length === 0" class="card-body p-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>

                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder ">
                                        المستخدمين
                                    </th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder ps-2">
                                        البريد الاكتروني
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        برميوم
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        تاريخ انشاء حساب
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(p, index) in paginated" :key="index">
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img :src="url + '/assets/img/user.png'" height="40" />
                                            </div>
                                            <div class="d-flex flex-column justify-content-center me-3">
                                                <h6 class="mb-0 text-sm ">
                                                    {{ p.name }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <h6 class="mb-0 text-sm ">
                                            {{ p.email }}
                                        </h6>
                                        <!-- <span class="text-xs font-weight-bold">
                                        {{ ' ضد ' }} {{ }}
                                    </span> -->
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span v-if="p.premuim == 0" class="badge badge-sm bg-gradient-secondary">غير مشترك</span>
                                        <span v-else-if="p.premuim == 1" class="badge badge-sm bg-gradient-success">مشترك</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-1"> {{ getTime(p.created_at)}} </p>
                                        <p class="text-xs text-secondary mb-0">{{ getYear(p.created_at) }}</p>

                                        <!-- <div style="justify-content: center;" class="form-check text-center form-switch">
                                            <input class="form-check-input" type="checkbox" @click="checked(p.id,index,p.featured)" v-model="p.featured">
                                        </div> -->
                                    </td>
                                    <td class="align-middle text-center">
                                        <a class="btn btn-link text-dark px-3 mb-0" @click="editing(p)" href="javascript:;"><i class="fas fa-pencil-alt text-dark ms-2" aria-hidden="true"></i>تعديل</a>

                                        <a class="btn btn-link text-danger text-gradient px-3 mb-0" @click="destroy(p.id,index)" href="javascript:;"><i class="far fa-trash-alt ms-2" aria-hidden="true"></i>حذف</a>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="paginate-div" v-if="Object.keys(users).length > 0">
                            <vue-awesome-paginate :total-items="users.total" :items-per-page="users.per_page" :max-pages-shown="5" :current-page="users.current_page" :on-click="onClickHandler" />
                        </div>
                    </div>
                </div>

                <div v-if="index && Object.keys(query).length > 0 && query !== null" class="card-body p-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>

                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder ">
                                        المستخدمين
                                    </th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder ps-2">
                                        البريد الاكتروني
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        برميوم
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        تاريخ انشاء حساب
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(p, index) in dataSearch" :key="index">
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img :src="url + '/assets/img/user.png'" height="40" />
                                            </div>
                                            <div class="d-flex flex-column justify-content-center me-3">
                                                <h6 class="mb-0 text-sm ">
                                                    {{ p.name }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <h6 class="mb-0 text-sm ">
                                            {{ p.email }}
                                        </h6>
                                        <!-- <span class="text-xs font-weight-bold">
                                        {{ ' ضد ' }} {{ }}
                                    </span> -->
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span v-if="p.premuim == 0" class="badge badge-sm bg-gradient-secondary">غير مشترك</span>
                                        <span v-else-if="p.premuim == 1" class="badge badge-sm bg-gradient-success">مشترك</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-1"> {{ getTime(p.created_at)}} </p>
                                        <p class="text-xs text-secondary mb-0">{{ getYear(p.created_at) }}</p>

                                        <!-- <div style="justify-content: center;" class="form-check text-center form-switch">
                                            <input class="form-check-input" type="checkbox" @click="checked(p.id,index,p.featured)" v-model="p.featured">
                                        </div> -->
                                    </td>
                                    <td class="align-middle text-center">
                                        <a class="btn btn-link text-dark px-3 mb-0" @click="editing(p)" href="javascript:;"><i class="fas fa-pencil-alt text-dark ms-2" aria-hidden="true"></i>تعديل</a>

                                        <a class="btn btn-link text-danger text-gradient px-3 mb-0" @click="destroy(p.id,index)" href="javascript:;"><i class="far fa-trash-alt ms-2" aria-hidden="true"></i>حذف</a>
                                    </td>
                                </tr>
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>

            <div v-if="add || edit" class="card-body pt-0">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label for="name" class="form-control-label">اسم كامل</label><input v-model="form.user.name" class="form-control" name="name" type="text"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label for="email" class="form-control-label">البريد الاكتروني </label><input v-model="form.user.email" class="form-control" name="email" type="text"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group"><label for="example-text-input" class="form-control-label">  باسورد حساب  </label><input class="form-control" v-model="form.user.password" name="name" type="password"></div>

                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">برميوم</label>
                            <div style="display: block; padding: 0.5rem 0.75rem;">
                                <Switch v-model:checked="form.user.premuim" labelclass="pe-4" />

                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">اشتراك مفتوح لا ينتهي</label>
                            <div style="display: block; padding: 0.5rem 0.75rem;">
                                <Switch v-model:checked="form.user.never_ends" labelclass="pe-4" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group"><label for="example-text-input" class="form-control-label"> تاريخ انتهاء اشتراك </label><input class="form-control" :disabled="form.user.never_ends" v-model="form.user.ends_at" name="name" type="date"></div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <!-- <div class="form-group">
                            <label for="example-text-input" class="form-control-label"> مواسم </label>
                            <multiselect mode="tags" 
                            v-model="form.selectedSeason"
                             placeholder="Select options" 
                             :close-on-select="false"
                              :searchable="true" 
                              :object="true" 
                              :resolve-on-load="false" 
                              :delay="0" :min-chars="1" 
                              label="name" 
                                track-by="name"
                        :multiple="true"

                            :options="(query) => {
                                     return fetchSeasons(query)
                                         }" />
                        </div> -->
                    </div>
                </div>
                <div class="col-6 my-auto text-end">
                    <a class="btn bg-gradient-info mb-0" v-if="add" @click="store()" href="javascript:;"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;اضافة </a>
                    <a class="btn  bg-gradient-info mb-0" v-if="edit" @click="update()" href="javascript:;"><i class="ni ni-bold-right btn-sm" aria-hidden="true"></i>&nbsp;&nbsp;حفظ </a>

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
    </div>
</div>
</template>

<script>
import moment from 'moment';
import Switch from '../core/Switch.vue';

import {
    notifications
} from "../mixins/notifications";

export default {

    data() {
        return {
            users: [],
            count: 0,
            query: "",
            dataSearch: [],
            form: {
                user: {},
                // selectedSeason: [],
            },

            seasons: [],
            //isLoadingSeasons: false,
            logo: null,
            index: true,
            add: false,
            edit: false,
            url: '',
            csrf: document.head.querySelector('meta[name="csrf-token"]') ? document.head.querySelector('meta[name="csrf-token"]').content : '',
        };
    },
    async mounted() {
        this.url = url;
        let response = await axios.get(url + "/admin/get/users/data");
        this.users = response.data;
        let responseCount = await axios.get(url + "/admin/get/users/count");
        this.count = responseCount.data.count;
        console.log(this.csrf);
    },
    computed: {
        paginated() {
            return this.users.data;
        },
    },
    methods: {
        create() {
            this.index = false;
            console.log(this.index);
            this.edit = false;
            this.add = true;
        },
        // change the view to the index
        back() {
            console.log(this.myFiles);
            // this.form.user = "";
            this.add = false;
            this.edit = false;
            this.form = {
                user: {},
                // selectedSeason: [],
            };
            this.index = true;
        },
        // update a record (team) in the database
        update() {
            axios
                .put(url + "/admin/update/users/" + this.form.user.id, this.form)
                .then(response => {
                    debugger;

                    this.add = false;
                    this.edit = false;
                    this.index = true;
                    this.form.user = {};
                    let dataIndex = this.users.data.findIndex(data => data.id === this.form.user.id);
                    if (this.query.length > 0) {
                        let dataSearchIndex = this.dataSearch.findIndex(data => data.id === this.form.user.id);
                        this.dataSearch[dataSearchIndex] = response.data.body;
                    }
                    console.log(dataIndex);
                    this.users.data[dataIndex] = response.data.body;
                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    console.log(error);
                    this.showError(error.response);
                });
        },
        // delete a record (team) in the databse
        destroy(id, index) {
            this.showConfirm(async () => {
                try {
                    let response = await axios.delete(url + "/admin/remove/users/" + id);

                    let dataIndex = this.users.data.findIndex(data => data.id === id);
                    if (this.query.length > 0) {
                        console.log(this.dataSearch);
                        let dataSearchIndex = this.dataSearch.findIndex(data => data.id === id);
                        this.dataSearch.splice(dataSearchIndex, 1);
                    }
                    this.users.data.splice(dataIndex, 1);

                    //  this.paginate.filteredMovies.list.splice(index, 1);
                    this.showSuccess(response.data.message);
                } catch (error) {
                    this.showError();
                }
            });
        },
        // change the view to the editing form
        editing(user) {
            this.index = false;
            this.add = false;
            this.edit = true;
            this.form.user = user;

        },
        store() {
            axios
                .post(url + "/admin/store/users", this.form)
                .then(response => {
                    this.add = false;
                    this.edit = false;
                    this.index = true;
                    this.form.user = {};
                    this.logo = null;
                    this.users.data.unshift(response.data.body);

                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    console.log(error);
                    this.showError(error.response);
                });
        },
        onremovefile(error, file) {
            console.log('dsd');
            console.log(file);
        },
        onProcessFile(error, file) {
            this.form.user.img = file.serverId.path;

            console.log(file.serverId);
            this.logo = file.serverId.path;
            console.log(this.logo);
        },
        onResponse(file) {

            console.log(JSON.parse(file));
            // this.logo = file.serverId.path;
            // console.log(this.logo);
        },
        async onClickHandler(page) {

            let response = await axios.get(url + "/admin/get/users/data?page=" + page);
            this.users = response.data;
        },
        async fetchSeasons(query) {
            if (query.length > 0) {
                let response = await axios.get(url + "/admin/get/seasons/search?q=" + query);
                console.log(response.data);
                return response.data;

                //console.log(this.seasons);
            }
        },
        getTime(date) {
            var time_format = 'h:mm a';
            var datetime_format = 'dddd، YYYY/MM/DD';

            //get time
            var thetime = moment().format();
            //get timezone
            var currentzone = this.getTimezone();
            moment.tz.setDefault("Asia/Riyadh");

            const d = new Date(date);

            //var matchtime = ;
            //Apply zone changes
            var newtime = moment.tz(date, 'Asia/Riyadh').clone().tz(currentzone).format(time_format).toString();
            //Translate
            newtime = newtime.replace('pm', 'م');
            newtime = newtime.replace('am', 'ص');

            return newtime;
        },
        getTimezone() {
            var getTimezoneDB = window.localStorage.getItem('jd_timezone');
            var currentzone;
            //Case: we already have a chosenzone
            if (getTimezoneDB) {
                currentzone = getTimezoneDB;
            } else { //Case: get zone from momemnt
                currentzone = moment.tz.guess();
            }
            return currentzone;
        },
        getYear(date) {

            const d = new Date(date);
            return moment(d).format("MMM DD, YYYY");
        },
        nameWithLang(name) {
            console.log(name);
            return `${name}`
        },
        async SEARCH() {
            if (this.query.length > 0) {
                let response = await axios.get(url + "/admin/get/users/search?q=" + this.query);
                this.dataSearch = response.data;
                console.log(this.dataSearch);
            }
        }
    },
    components: {
        Switch,
    },
    mixins: [notifications],
};
</script>

<style>
.paginate-div {
    padding: 10px;
    text-align: center;
}

.pagination-container {
    display: flex;
    column-gap: 10px;
}

.paginate-buttons {
    height: 40px;
    width: 60px;
    border-radius: 20px;
    cursor: pointer;
    background-color: rgb(242, 242, 242);
    border: 1px solid rgb(217, 217, 217);
    color: black;
}

.paginate-buttons:hover {
    background-color: #d8d8d8;
}

.active-page {
    background-color: #3498db;
    border: 1px solid #3498db;
    color: white;
}

.active-page:hover {
    background-color: #2988c8;
}
</style>
