<template>
<div class="row">
    <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                عدد البطولات
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
                        <h6 v-if="index"> البطولات </h6>
                        <h6 v-if="add"> اضافة بطولة </h6>
                        <h6 v-if="edit && form.league != null">
                            <div class="d-flex px-2 py-1">
                                <div>
                                    <img :src="url + '/api/image/comp-logo/' +form.league.img" class="avatar avatar-sm ms-3" />
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm ">
                                        {{ form.league.name_ar ? form.league.name_ar : form.league.name }}
                                    </h6>
                                </div>
                            </div>
                        </h6>
                    </div>
                    <div class="col-6 my-auto text-start">
                        <a class="btn bg-gradient-dark mb-0" v-if="index" @click="create()" href="javascript:;"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;اضافة بطولة جديد</a>
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
                <div v-if="( leagues !== null && query === null) ||
                Object.keys(query).length === 0" class="card-body p-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>

                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder ">
                                        البطولة
                                    </th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">
                                        الاسم بالعربية
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        اسم الفرعي بالعربي
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        ترتيب البطولة
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        متميز
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <draggable tag="tbody" :list="paginated" @change="log">
                                <tr v-for="(p, index) in paginated" :key="index">
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img :src="url + '/api/image/comp-logo/' +p.img" class="avatar avatar-sm ms-3" />
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm ">
                                                    {{ p.name }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 text-sm ">
                                            {{ p.name_ar }}
                                        </h6>
                                        <!-- <span class="text-xs font-weight-bold">
                                        {{ ' ضد ' }} {{ }}
                                    </span> -->
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-sm font-weight-bold">
                                            {{ p.name_ar_sub }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <!-- <toggle v-model="p.featured" @change="checked(p.id,index,p.featured)" class="toggle-blue" /> -->
                                        <Switch v-model:checked="p.featured" labelclass="justify-content-center" @change="checked(p.id,index,p.featured)" />

                                        <!-- <div style="justify-content: center;" class="form-check text-center form-switch">
                                            <input class="form-check-input" type="checkbox" @change="checked(p.id,index,p.featured)" :checked="p.featured">
                                        </div> -->
                                    </td>
                                    <td class="align-middle text-center">
                                        <h6 class="mb-0 text-sm ">
                                            {{ p.position }}
                                        </h6>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a class="btn btn-link text-dark px-3 mb-0" @click="editing(p)" href="javascript:;"><i class="fas fa-pencil-alt text-dark ms-2" aria-hidden="true"></i>تعديل</a>

                                        <a class="btn btn-link text-danger text-gradient px-3 mb-0" @click="destroy(p.id,index)" href="javascript:;"><i class="far fa-trash-alt ms-2" aria-hidden="true"></i>حذف</a>
                                    </td>
                                </tr>
                            </draggable>
                        </table>
                        <div class="paginate-div" v-if="Object.keys(leagues).length > 0">
                            <vue-awesome-paginate :total-items="leagues.total" :items-per-page="leagues.per_page" :max-pages-shown="5" :current-page="leagues.current_page" :on-click="onClickHandler" />
                        </div>
                    </div>
                </div>

                <div v-if="index && Object.keys(query).length > 0 && query !== null" class="card-body p-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>

                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder ">
                                        البطولة
                                    </th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">
                                        الاسم بالعربية
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        الاسم الفرعي بالعربي
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        متميز
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
                                                <img :src="url + '/api/image/comp-logo/' +p.img" class="avatar avatar-sm ms-3" />
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm ">
                                                    {{ p.name }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 text-sm ">
                                            {{ p.name_ar }}
                                        </h6>
                                        <!-- <span class="text-xs font-weight-bold">
                                        {{ ' ضد ' }} {{ }}
                                    </span> -->
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-sm font-weight-bold">
                                            {{ p.name_ar_sub }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center ">
                                        <Switch v-model:checked="p.featured" labelclass="justify-content-center" @change="checked(p.id,index,p.featured)" />

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
                <div class="col-md-4">
                    <label for="image">لوجو</label>
                    <!-- <input id="poster_path" name="poster_path" type="text" class="form-control" required /> -->
                    <file-pond id="image" name="image" ref="pond" label-idle="Drop files here..." v-bind:allow-multiple="false" v-bind:files="myFiles" @processfile="onProcessFile" v-on:process="onremovefile" accepted-file-types="image/jpeg, image/png" :server="server" />
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label for="name" class="form-control-label">اسم بالانجليزي</label><input v-model="form.league.name" class="form-control" name="name" type="text"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group"><label for="name_ar" class="form-control-label">اسم بالعربي</label><input v-model="form.league.name_ar" class="form-control" name="name_ar" type="text"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group"><label for="name_ar_sub" class="form-control-label">اسم الفرعي بالعربي</label><input v-model="form.league.name_ar_sub" class="form-control" name="name_ar" type="text"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label"> نوع الفرق </label>
                            <multiselect v-model="form.league.teams_type" placeholder="اختر نوع الفرق" :searchable="false" :close-on-select="false" :show-labels="false" :options="team_type" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">متميز</label>
                            <div style="display: block; padding: 0.5rem 0.75rem;">
                                <toggle class="toggle-blue" v-model="form.league.featured" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label for="name" class="form-control-label">ترتيب البطولة</label><input v-model="form.league.position" class="form-control" name="name" type="text"></div>
                    </div>
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
// Import Vue FilePond
import vueFilePond from 'vue-filepond';

// Import FilePond styles
import 'filepond/dist/filepond.min.css';

// Import FilePond plugins
// Please note that you need to install these plugins separately

// Import image preview plugin styles
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

// Import image preview and file type validation plugins
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

// Create component
const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview);

import {
    notifications
} from "../mixins/notifications";
import Switch from '../core/Switch.vue';

export default {

    data() {
        return {
            leagues: [],
            count: 0,
            query: "",
            dataSearch: [],
            team_type: ['club', 'national'],
            form: {
                league: {},
                // selectedSeason: [],
            },
            myFiles: [],
            // myFiles: [{
            //     source: "http://localhost/example-app/public/api/image/comp-logo/placeholder.png",
            //     options: {
            //         type: "local",
            //     },
            // }, 
            //],
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
        let response = await axios.get(url + "/admin/get/leagues/data");
        this.leagues = response.data;
        let responseCount = await axios.get(url + "/admin/get/leagues/count");
        this.count = responseCount.data.count;
        console.log(this.csrf);

    },
    computed: {
        paginated() {
            return this.leagues.data;
        },

        onUrl() {
            var img = this.form.league.img;
            var league;
            if (this.form.league.id != null) {
                league = '&league=' + this.form.league.id;
            } else {
                league = '';
            }
            return url + '/admin/remove/leagues/image?img=' + img + league;
        },
        server() {
            return {
                url: '',
                revert: {
                    url: this.onUrl,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-Token': csrf,
                    },
                    onload: this.onResponse,

                },

                remove: {
                    //url: this.onUrl,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-Token': csrf,
                    },
                    onload: this.onResponse,
                },
                process: {
                    url: url + '/admin/store/leagues/image',
                    method: 'POST',
                    headers: {
                        "X-CSRF-Token": document.head.querySelector('meta[name="csrf-token"]') ? document.head.querySelector('meta[name="csrf-token"]').content : '',
                    },
                    onload: function (x) {

                        // X - empty, why????
                        console.log(x);
                        return JSON.parse(x);

                    },
                },
                //         //url: url + '/admin/store/leagues/image',
                //         // process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                //         //     // fieldName is the name of the input field
                //         //     // file is the actual file object to send
                //         //     const formData = new FormData();
                //         //     formData.append(fieldName, file, file.name);

                //         //     const request = new XMLHttpRequest();

                //         //     request.open('POST', url + '/admin/store/leagues/image');
                //         //     request.setRequestHeader("X-CSRF-Token", this.csrf);

                //         //     // Should call the progress method to update the progress to 100% before calling load
                //         //     // Setting computable to false switches the loading indicator to infinite mode
                //         //     request.upload.onprogress = (e) => {
                //         //         progress(e.lengthComputable, e.loaded, e.total);
                //         //     };

                //         //     // Should call the load method when done and pass the returned server file id
                //         //     // this server file id is then used later on when reverting or restoring a file
                //         //     // so your server knows which file to return without exposing that info to the client
                //         //     request.onload = function () {
                //         //         if (request.status >= 200 && request.status < 300) {
                //         //             this.logo = JSON.parse(request.responseText);
                //         //             this.logo = this.logo.path;
                //         //             console.log(this.logo);
                //         //             // the load method accepts either a string (id) or an object
                //         //             load(request.responseText);
                //         //         } else {
                //         //             // Can call the error method if something is wrong, should exit after
                //         //             error('oh no');
                //         //         }
                //         //     };

                //         //     request.send(formData);

                //         //     // Should expose an abort method so the request can be cancelled
                //         //     return {
                //         //         abort: () => {
                //         //             // This function is entered if the user has tapped the cancel button
                //         //             request.abort();

                //         //             // Let FilePond know the request has been cancelled
                //         //             abort();
                //         //         },
                //         //     };
                //         // },

                //         revert: {
                //             url: url + '/admin/remove/leagues/image/' + this.logo,
                //             method: 'DELETE',
                //             headers: {
                //                 'X-CSRF-Token': this.csrf,
                //             },
                //             onload: function (x) {

                //                 // X - empty, why????
                //                 console.log(x)

                //             },
                //         },
                //         // revert: './revert',
                //         // restore: './restore/',
                //         // load: './load/',
                //         // fetch: './fetch/',
                remove: (source, load, error) => {

                    axios
                        .delete(this.onUrl)
                        .then(response => {
                            if (response.data.status == 200) {
                                console.log(response.data);
                                this.showSuccess(response.data.message);
                                // the load method accepts either a string (id) or an object
                                load(test);
                            }
                        })
                        .catch(err => {
                            if (err.response.status == 402) {
                                this.showError(' لم يتم ايجاد ملف ');
                                this.form.league.img == null;
                                console.log(err);

                                // Can call the error method if something is wrong, should exit after
                                error('oh no');
                                setTimeout(() => {
                                    load();
                                }, "2000");
                            }
                        });

                    //const request = new XMLHttpRequest();

                    // request.open('DELETE', this.onUrl);
                    // request.setRequestHeader("X-CSRF-Token", this.csrf);

                    // // Should somehow send `source` to server so server can remove the file with this source
                    // request.onload = function () {
                    //     if (request.status == 200) {
                    //         var data = JSON.parse(request.responseText);
                    //         console.log(data);

                    //         // the load method accepts either a string (id) or an object
                    //         load(request.responseText);
                    //         return data;
                    //     } else {
                    //         var data = JSON.parse(request.responseText);

                    //         console.log(data);

                    //         // Can call the error method if something is wrong, should exit after
                    //         error('oh no');
                    //         setTimeout(() => {
                    //             load();
                    //         }, "2000");
                    //         return data;
                    //     }
                    // };

                    // request.send();

                    console.log(this.onUrl);
                    console.log(load);
                    console.log(error);

                    // // Can call the error method if something is wrong, should exit after
                    // error('oh my goodness');

                    // // Should call the load method when done, no parameters required
                    // load();
                },
                //         // process: {
                //         //     url: url + '/admin/store/leagues/image',
                //         //     method: 'POST',
                //         //     headers: {
                //         //         "X-CSRF-Token": this.csrf,
                //         //     },
                //         //     onload: (response) => console.log(response),
                //         // },

                //         process: {
                //             url: url + '/admin/store/leagues/image',
                //             method: 'POST',
                //             headers: {
                //                 "X-CSRF-Token": this.csrf,
                //             },
                //             onload: function (x) {

                //                 // X - empty, why????
                //                 console.log(x);
                //                 this.logo = JSON.parse(x);
                //                 this.logo = this.logo.path;

                //             },
                //         },

            };
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
            // this.form.league = "";
            this.add = false;
            this.edit = false;
            this.myFiles = [],
                this.form = {
                    league: {},
                    // selectedSeason: [],
                };
            this.logo = null;
            this.index = true;
        },
        log(event) {
            var test = (this.leagues.current_page -1) * 6;
            const newIndex = event.moved.newIndex;
            const oldIndex = event.moved.oldIndex;
            this.form.league = this.leagues.data[newIndex];
            this.form.league.position = test + (newIndex + 1);
            axios
                .put(url + "/admin/update/leagues/" + this.form.league.id, this.form)
                .then(response => {
                    this.leagues.data[newIndex].position = test + (newIndex + 1);
                    this.form.league = {};
                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    this.form.league = {};
                    console.log(error);
                    this.showError(error.response);
                });
            this.form.league = this.leagues.data[oldIndex];
            this.form.league.position = test + (oldIndex + 1);
            axios
                .put(url + "/admin/update/leagues/" + this.form.league.id, this.form)
                .then(response => {
                    this.leagues.data[oldIndex].position = test + (oldIndex + 1);
                    this.form.league = {};
                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    this.form.league = {};
                    console.log(error);
                    this.showError(error.response);
                });
        },
        // update a record (league) in the database
        update() {
            delete this.form.league.change_enable;

            axios
                .put(url + "/admin/update/leagues/" + this.form.league.id, this.form)
                .then(response => {

                    this.add = false;
                    this.edit = false;
                    this.index = true;
                    this.form.league = {};
                    let dataIndex = this.leagues.data.findIndex(data => data.id === this.form.league.id);
                    if (this.query.length > 0) {
                        let dataSearchIndex = this.dataSearch.findIndex(data => data.id === this.form.league.id);
                        this.dataSearch[dataSearchIndex] = response.data.body;
                    }
                    if (dataIndex >= 0) {

                        this.leagues.data[dataIndex] = response.data.body;
                    }
                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    console.log(error);
                    this.showError(error.response);
                });
        },
        checked(id, index, value, search) {
            axios
                .get(url + "/admin/update/leagues/featured/" + id + '?featured=' + value)
                .then(response => {
                    let dataIndex = this.leagues.data.findIndex(data => data.id === id);
                    if (this.query.length > 0) {
                        let dataSearchIndex = this.dataSearch.findIndex(data => data.id === id);
                        this.dataSearch[dataSearchIndex].featured = value;
                    }
                    if (dataIndex >= 0) {
                        this.leagues.data[dataIndex].featured = value;
                        console.log(this.leagues.data[dataIndex].featured);

                    }
                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    if (this.query.length > 0) {
                        let dataSearchIndex = this.dataSearch.findIndex(data => data.id === id);
                        this.dataSearch[dataSearchIndex].featured = !value;
                    }
                    let dataIndex = this.leagues.data.findIndex(data => data.id === id);

                    if (dataIndex >= 0) {
                        this.leagues.data[dataIndex].featured = !value;
                    }
                    this.showError();
                });

        },
        // delete a record (league) in the databse
        destroy(id, index) {
            this.showConfirm(async () => {
                try {
                    let response = await axios.delete(url + "/admin/remove/leagues/" + id);

                    let dataIndex = this.leagues.data.findIndex(data => data.id === id);
                    if (this.query.length > 0) {
                        console.log(this.dataSearch);
                        let dataSearchIndex = this.dataSearch.findIndex(data => data.id === id);
                        this.dataSearch.splice(dataSearchIndex, 1);
                    }
                    if (dataIndex >= 0) {
                        this.leagues.data.splice(dataIndex, 1);
                    }
                    //  this.paginate.filteredMovies.list.splice(index, 1);
                    this.showSuccess(response.data.message);
                } catch (error) {
                    this.showError();
                }
            });
        },
        // change the view to the editing form
        editing(league) {
            this.index = false;
            this.add = false;
            this.edit = true;
            this.form.league = league;
            if (this.form.league.img != null && this.form.league.img != '' && this.form.league.img != 'placeholder.png') {
                this.myFiles = [{
                    source: url + '/api/image/comp-logo/' + this.form.league.img,
                    options: {
                        type: "local",
                    },
                }, ];
            }
        },
        store() {
            axios
                .post(url + "/admin/store/leagues", this.form)
                .then(response => {
                    this.add = false;
                    this.edit = false;
                    this.index = true;
                    this.form.league = {};
                    this.logo = null;
                    this.leagues.data.unshift(response.data.body);

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
            this.form.league.img = file.serverId.path;

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
            let response = await axios.get(url + "/admin/get/leagues/data?page=" + page);
            this.leagues = response.data;
        },
        async fetchSeasons(query) {
            if (query.length > 0) {
                let response = await axios.get(url + "/admin/get/seasons/search?q=" + query);
                console.log(response.data);
                return response.data;

                //console.log(this.seasons);
            }
        },
        nameWithLang(name) {
            console.log(name);
            return `${name}`
        },
        async SEARCH() {
            if (this.query.length > 0) {
                let response = await axios.get(url + "/admin/get/leagues/search?q=" + this.query);
                this.dataSearch = response.data;
                console.log(this.dataSearch);
            }
        }
    },
    components: {
        FilePond,
        Switch
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
