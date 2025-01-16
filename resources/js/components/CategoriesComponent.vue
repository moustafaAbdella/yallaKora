<template>
    <div class="row">
        <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                    عدد الاقسام
                                </p>
                                <h5 class="font-weight-bolder mb-0">{{ categories.total }}</h5>
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
    <div class="row my-3">
        <div class=" col-3 d-flex align-items-center">
            <div class="input-group"><span class="input-group-text text-body"><i class="fas fa-search"
                        aria-hidden="true"></i></span>
                <input type="text" class="form-control" placeholder="البحث" v-model="query" @keyup="SEARCH">
            </div>
        </div>
        <div class="col-3">
            <multiselect v-model="selectedType" @select="getByType" mode="single" :close-on-select="true" :options="[
                { value: 'all', label: 'جميع فئات' },
                { value: 'categories', label: 'فئات رئسية' },
                { value: 'subcategories', label: 'فئات فرعية' },
            ]" />
        </div>
    </div>
    <div class="row my-3">
        <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="row mb-3">
                        <div class="col-6">
                            <h6 v-if="index"> الاقسام </h6>
                            <h6 v-if="add"> اضافة قسم </h6>
                            <h6 v-if="edit && form.category != null">
                                <div class="d-flex px-2 py-1">
                                    <div>
                                        <img :src="url + '/storage/livetv/' + form.category.logo"
                                            class="avatar avatar-sm ms-3" />
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm ">
                                            {{ form.category.name }}
                                        </h6>
                                    </div>
                                </div>
                            </h6>
                        </div>
                        <div class="col-6 my-auto text-start">
                            <a class="btn bg-gradient-dark mb-0" v-if="index" @click="create()" href="javascript:;"><i
                                    class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;اضافة قسم جديد</a>
                            <a class="btn  bg-gradient-dark mb-0" v-if="!index" @click="back()" href="javascript:;"><i
                                    class="ni ni-bold-right btn-sm" aria-hidden="true"></i>&nbsp;&nbsp;رجوع </a>

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
                    <div v-if="(categories !== null && query === null) ||
                    Object.keys(query).length === 0" class="card-body p-0 pb-2">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>

                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder ">
                                            القسم
                                        </th>
                                        <th
                                            class="col-sm-2 text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                            نوع
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                            ترتيب قسم
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                            اظهار في تطبيق
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <draggable tag="tbody" :list="paginated" @change="log">
                                    <tr v-for="(p, index) in paginated" :key="index">
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img :src="url + '/storage/livetv/' + p.logo"
                                                        class="avatar avatar-sm ms-3" />
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm ">
                                                        {{ p.name }}
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class=" align-middle text-center ">
                                            <div class="d-flex justify-content-center">
                                            <h6 class="mb-0 text-sm " v-if="p.parent_id != null">
                                                {{ p.parent.name }}
                                            </h6>
                                            <span v-if="p.parent_id == null"
                                                class="badge badge-sm bg-gradient-success">قسم اساسي</span>
                                            <span v-else-if="p.parent_id != null"
                                                class="badge badge-sm bg-gradient-warning">قسم فرعي</span>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <h6 class="mb-0 text-sm ">
                                                {{ p.position }}
                                            </h6>
                                        </td>
                                        <td class="align-middle text-center">
                                            <!-- <toggle v-model="p.featured" @change="checked(p.id,index,p.featured)" class="toggle-blue" /> -->
                                            <Switch v-model:checked="p.active" labelclass="justify-content-center"
                                                @change="checked(p.id, index, p.active)" />

                                            <!-- <div style="justify-content: center;" class="form-check text-center form-switch">
                                            <input class="form-check-input" type="checkbox" @change="checked(p.id,index,p.featured)" :checked="p.featured">
                                        </div> -->
                                        </td>
                                        <td class="align-middle text-center">
                                            <a class="btn btn-link text-dark px-3 mb-0" @click="update(p)"
                                                href="javascript:;"><i class="fas fa-pencil-alt text-dark ms-2"
                                                    aria-hidden="true"></i>تعديل</a>

                                            <a class="btn btn-link text-danger text-gradient px-3 mb-0"
                                                @click="destroy(p.id, index)" href="javascript:;"><i
                                                    class="far fa-trash-alt ms-2" aria-hidden="true"></i>حذف</a>
                                        </td>
                                    </tr>
                                </draggable>

                            </table>
                            <div class="paginate-div" v-if="Object.keys(categories).length > 0 && categories.total > 6">
                                <vue-awesome-paginate :total-items="categories.total" :items-per-page="categories.per_page"
                                    :max-pages-shown="5" :current-page="categories.current_page"
                                    :on-click="onClickHandler" />
                            </div>
                        </div>
                    </div>

                    <div v-if="index && Object.keys(query).length > 0 && query !== null" class="card-body p-0 pb-2">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>

                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder ">
                                            القسم
                                        </th>
                                        <th
                                            class="col-sm-2 text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                            نوع
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                            ترتيب قسم
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                            اظهار في تطبيق
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
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img :src="url + '/storage/livetv/' + p.logo"
                                                        class="avatar avatar-sm ms-3" />
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm ">
                                                        {{ p.name }}
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class=" align-middle text-center ">
                                            <div class="d-flex justify-content-center">
                                            <h6 class="mb-0 text-sm " v-if="p.parent_id != null">
                                                {{ p.parent.name }}
                                            </h6>
                                            <span v-if="p.parent_id == null"
                                                class="badge badge-sm bg-gradient-success">قسم اساسي</span>
                                            <span v-else-if="p.parent_id != null"
                                                class="badge badge-sm bg-gradient-warning">قسم فرعي</span>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <h6 class="mb-0 text-sm ">
                                                {{ p.position }}
                                            </h6>
                                        </td>
                                        <td class="align-middle text-center">
                                            <!-- <toggle v-model="p.featured" @change="checked(p.id,index,p.featured)" class="toggle-blue" /> -->
                                            <Switch v-model:checked="p.active" labelclass="justify-content-center"
                                                @change="checked(p.id, index, p.active)" />

                                            <!-- <div style="justify-content: center;" class="form-check text-center form-switch">
                                            <input class="form-check-input" type="checkbox" @change="checked(p.id,index,p.featured)" :checked="p.featured">
                                        </div> -->
                                        </td>
                                        <td class="align-middle text-center">
                                            <a class="btn btn-link text-dark px-3 mb-0" @click="editing2(p)"
                                                href="javascript:;"><i class="fas fa-pencil-alt text-dark ms-2"
                                                    aria-hidden="true"></i>تعديل</a>

                                            <a class="btn btn-link text-danger text-gradient px-3 mb-0"
                                                @click="destroy(p.id, index)" href="javascript:;"><i
                                                    class="far fa-trash-alt ms-2" aria-hidden="true"></i>حذف</a>
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
                        <file-pond id="image" name="image" ref="pond" label-idle="Drop files here..."
                            v-bind:allow-multiple="false" v-bind:files="myFiles" @processfile="onProcessFile"
                            v-on:process="onremovefile" accepted-file-types="image/jpeg, image/png" :server="server" />
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"><label for="name" class="form-control-label">اسم</label><input
                                    v-model="form.category.name" class="form-control" name="name" type="text"></div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group"><label for="name" class="form-control-label">ترتيب
                                     قسم</label><input v-model="form.category.position" class="form-control" name="name"
                                    type="text"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group"><label for="name" class="form-control-label">اختر قسم</label>
                                <multiselect v-model="form.category.parent_id" mode="single"
                                :close-on-select="true" :options="parents" />

                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row">

                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="name" class="form-control-label">اظهار في تطبيق</label>
                                <Switch v-model:checked="form.category.active" />
                            </div>
                        </div>
                    </div>
                    <div class="col-6 my-auto text-end" v-if="!isLoading">
                        <a class="btn bg-gradient-info mb-0" v-if="add" @click="store()" href="javascript:;"><i
                                class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;اضافة </a>
                        <a class="btn  bg-gradient-info mb-0" v-if="edit" @click="updateSubmit()" href="javascript:;"><i
                                class="ni ni-bold-right btn-sm" aria-hidden="true"></i>&nbsp;&nbsp;حفظ </a>

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
import {
    settings
} from "../mixins/settings";
import Switch from '../core/Switch.vue';

export default {
    data() {
        return {
            isLoading: false,
            url: '',
            count: 0,
            categories: [],
            parents: [],
            parent: '',
            query: '',
            dataSearch: [],
            selectedType: [],
            genre: "",
            logo: null,
            index: true,
            add: false,
            edit: false,
            form: {
                category: {
                    parent:[],
                },
                links: [],
                // selectedSeason: [],
            },
            myFiles: [],
            tmdb: {
                movies: [],
                series: []
            },
            paginate: ["categories"],
            editing: {}
        };
    },
    async mounted() {
        this.url = url;
        let response = await axios.get(url + "/admin/get/categories/data");
        this.categories = response.data;
        response = await axios.get(url + "/admin/categories/data?type=categories"); //?type=categories
        this.parents = [];
        for (let i = 0; i < response.data.length; i++) {
            let parent = {};
            parent.value = response.data[i].id;
            parent.label = response.data[i].name;
            this.parents.push(parent);
        }
        this.selectedType = 'all';
    },
    computed: {
        paginated() {
            return this.categories.data;
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
                    url: url + '/admin/store/categories/image',
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
                //         //url: url + '/admin/store/categories/image',
                //         // process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                //         //     // fieldName is the name of the input field
                //         //     // file is the actual file object to send
                //         //     const formData = new FormData();
                //         //     formData.append(fieldName, file, file.name);

                //         //     const request = new XMLHttpRequest();

                //         //     request.open('POST', url + '/admin/store/categories/image');
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
                //             url: url + '/admin/remove/categories/image/' + this.logo,
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
                                load();
                            }
                        })
                        .catch(err => {
                            if (err.response.status == 402) {
                                this.showError(' لم يتم ايجاد ملف ');
                                this.form.category.logo == null;
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
                //         //     url: url + '/admin/store/categories/image',
                //         //     method: 'POST',
                //         //     headers: {
                //         //         "X-CSRF-Token": this.csrf,
                //         //     },
                //         //     onload: (response) => console.log(response),
                //         // },

                //         process: {
                //             url: url + '/admin/store/categories/image',
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
        onremovefile(error, file) {
            console.log('dsd');
            console.log(file);
        },
        onProcessFile(error, file) {
            this.form.category.logo = file.serverId.path;

            console.log(file.serverId);
            this.logo = file.serverId.path;
            console.log(this.logo);
        },
        onResponse(file) {

            console.log(JSON.parse(file));
            // this.logo = file.serverId.path;
            // console.log(this.logo);
        },
        // change the view to the index
        back() {
            // this.form.category = "";
            this.add = false;
            this.edit = false;
            this.myFiles = [],
                this.form = {
                    category: {
                        parent:[],
                    }
                    // selectedSeason
                };
            this.logo = null;
            this.index = true;
        },
        log(event) {
            var test = (this.categories.current_page -1) * 6;
            const newIndex = event.moved.newIndex;
            const oldIndex = event.moved.oldIndex;
            this.form.category = this.categories.data[newIndex];
            this.form.category.position = test + (newIndex + 1);
            axios
                .put(url + "/admin/categories/update/" + this.form.category.id, this.form.category)
                .then(response => {
                    this.categories.data[newIndex].position = test + (newIndex + 1);
                    this.form.category = {};
                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    this.form.category = {};
                    console.log(error);
                    this.showError(error.response);
                });
            this.form.category = this.categories.data[oldIndex];
            this.form.category.position = test + (oldIndex + 1);
            axios
                .put(url + "/admin/categories/update/" + this.form.category.id, this.form.category)
                .then(response => {
                    this.categories.data[oldIndex].position = test + (oldIndex + 1);
                    this.form.category = {};
                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    this.form.category = {};
                    console.log(error);
                    this.showError(error.response);
                });
        },
        // create a genre in database
        async store() {
            this.isLoading = true;
            try {
                let response = await axios.post(url + "/admin/categories/store", this.form.category)
                this.add = false;
                this.edit = false;
                this.index = true;
                this.form.category = {};
                this.logo = null;
                this.categories.data.unshift(response.data.body);
                this.isLoading = false;

                this.showSuccess(response.data.message);
            } catch (error) {
                this.isLoading = false;
                this.showError(error.response);
            }
        },

        // delete a genre from database
        destroy(id, index) {
            this.showConfirm(async () => {
                try {
                    let response = await axios.delete(
                        url + "/admin/categories/destroy/" + id
                    );
                    let dataIndex = this.categories.data.findIndex(data => data.id === id);
                    if (this.query.length > 0) {
                        console.log(this.dataSearch);
                        let dataSearchIndex = this.dataSearch.findIndex(data => data.id === id);
                        this.dataSearch.splice(dataSearchIndex, 1);
                    }
                    if (dataIndex >= 0) {
                        this.categories.data.splice(dataIndex, 1);
                    }
                    let parentsIndex = this.parents.findIndex(data => data.value === id);
                    if (dataIndex >= 0) {
                        this.parents.splice(parentsIndex, 1);
                    }
                    this.showSuccess(response.data.message);
                } catch (error) {
                    this.showError();
                }
            });
        },
        // get all themoviedb categories and store them in the database
        // change the view to the editing form
        // editing(livetv) {
        //     this.index = false;
        //     this.add = false;
        //     this.edit = true;
        //     this.form.livetv = livetv;
        //     if (this.form.livetv.logo != null && this.form.livetv.logo != '' && this.form.livetv.logo != 'placeholder.png') {
        //         this.myFiles = [{
        //             source: url + '/storage/livetv/' + this.form.livetv.logo,
        //             options: {
        //                 type: "local",
        //             },
        //         }, ];
        //     }
        // },
        update(genre, index) {
            this.add = false;
            this.index = false;
            this.edit = true;
            this.form.category = genre;
            if (this.form.category.logo != null && this.form.category.logo != '' && this.form.category.logo != 'placeholder.png') {
                this.myFiles = [{
                    source: url + '/storage/categories/' + this.form.category.logo,
                    options: {
                        type: "local",
                    },
                }, ];
            } else if (this.form.category.logo != null && this.form.category.logo != '') {
                this.form.category.logo = 'placeholder.png';
            }
            // this.form.category.name = genre.name;
            this.form.category.parent = genre.parent_id;
            // console.log(this.parent);
            this.editing = genre;
            this.editing.index = index;
        },
        checked(id, index, value, search) {
            axios
                .get(url + "/admin/update/categories/featured/" + id + '?featured=' + value)
                .then(response => {
                    let dataIndex = this.categories.data.findIndex(data => data.id === id);
                    if (this.query.length > 0) {
                        let dataSearchIndex = this.dataSearch.findIndex(data => data.id === id);
                        this.dataSearch[dataSearchIndex].featured = value;
                    }
                    if (dataIndex >= 0) {
                        this.categories.data[dataIndex].featured = value;
                        console.log(this.categories.data[dataIndex].featured);

                    }
                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    if (this.query.length > 0) {
                        let dataSearchIndex = this.dataSearch.findIndex(data => data.id === id);
                        this.dataSearch[dataSearchIndex].featured = !value;
                    }
                    let dataIndex = this.categories.data.findIndex(data => data.id === id);

                    if (dataIndex >= 0) {
                        this.categories.data[dataIndex].featured = !value;
                    }
                    this.showError();
                });

        },
        cancel() {
            this.edit = false;
                this.add = false;
                this.index = true;
            this.genre = "";
            this.editing = {};
        },
        // update a genre from database
        async updateSubmit() {
            this.isLoading = true;
            try {
                let response = await axios.put(
                    url + "/admin/categories/update/" + this.editing.id, this.form.category
                );
                let dataIndex = this.categories.data.findIndex(data => data.id === this.editing.id);
                if (this.query.length > 0) {
                    let dataSearchIndex = this.dataSearch.findIndex(data => data.id === this.editing.id);
                    this.dataSearch[dataSearchIndex] = response.data.body;
                }
                if (dataIndex >= 0) {

                    this.categories.data[dataIndex] = response.data.body;
                }
                this.edit = false;
                this.add = false;
                this.index = true;
                this.genre = "";
                this.editing = {};
                this.isLoading = false;

                this.showSuccess(response.data.message);
            } catch (error) {
                this.isLoading = false;
                this.showError(error.response);
            }
        },
        async SEARCH() {
            if (this.query.length > 0) {
                const params = {};
                if (this.query.length > 0) {
                    params.date = this.query;
                }
                if (this.selectedType.length > 0) {
                    params.type = this.selectedType;
                }
                let response = await axios.get(url + "/admin/get/categories/search", {
                    params: params
                });
                this.dataSearch = response.data;
                console.log(this.dataSearch);
            }
        },
        async getByType() {
            const params = {};
            if (this.query.length > 0) {
                params.date = this.query;
            }
            if (this.selectedType.length > 0) {
                params.type = this.selectedType;
            }
            let response = await axios.get(url + "/admin/get/categories/data", {
                params: params
            });
            this.categories = response.data;
        },
        async onClickHandler(page) {
            const params = {};

            if (this.selectedType.length > 0) {
                params.type = this.selectedType;
            }
            params.page = page;
            let response = await axios.get(url + "/admin/get/categories/data", {
                params: params
            });
            this.categories = response.data;
        }

    },
    components: {
        FilePond,
        Switch
    },
    mixins: [notifications, settings]
};
</script>

<style scoped>

</style>
