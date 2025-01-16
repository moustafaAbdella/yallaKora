<template>
<div class="row">
    <!-- <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                عدد المواسم
                            </p>
                            <h5 class="font-weight-bolder mb-0">{{ seasons.total }}</h5>
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
    </div> -->
</div>
<div class="row my-3">
    <div class="col-sm-6">

    </div>
    <div class="col-sm-6">
        <div class=" col-4 d-flex align-items-center">
            <div class="input-group"><span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                <input type="text" class="form-control" placeholder="البحث" v-model="query" @keyup="SEARCH"></div>
        </div>
    </div>
</div>
<div class="row my-3">
    <div class="col-lg-6 col-md-6 mb-md-0 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="row mb-1 ">
                    <div class="col-6">
                        <h5> ارسال اشعار </h5>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title">عنوان</label>
                        <input type="text" id="title" v-model="title" class="form-control" placeholder=" " />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title">وصف</label>
                        <textarea id="title" v-model="body" class="form-control" placeholder=" " rows="8" cols="50" />
                        </div>
                </div>
                <div class="col-md-12">
                    <multiselect v-model="type" mode="single" :close-on-select="true" :options="[
                { value: 'main', label: 'تطبيق الاساسي' },
                { value: 'player', label: 'تطبيق البلاير' },
            ]" />
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="img">رابط صورة</label>
                        <input type="text" id="img" v-model="image" class="form-control" placeholder=" " />
                    </div>
                </div>
                <div class="col-sm-3">
                        <a class="btn bg-gradient-info mb-0" @click="send()" href="javascript:;"> ارسال اشعار </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 mb-md-0 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="row mb-1 ">
                    <div class="col-6">
                        <h5> رسائل اشعارات التلقائية </h5>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3" v-if="!edit">
                       <div class="form-group"><input v-model="key"  placeholder="مفتاح" class="form-control" name="name" type="text"></div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><input v-model="value" placeholder="رسالة" class="form-control" name="name" type="text"></div>
                    </div>
                                    <div class="col-3">
                        <a class="btn bg-gradient-info mb-0" v-if="!edit" @click="store()" href="javascript:;"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;اضافة رسالة </a>
                        <a class="btn bg-gradient-info mb-0" v-if="edit" @click="updateSubmit()" href="javascript:;"> حفظ </a>
                        <a class="btn bg-gradient-danger mb-0 me-2" v-if="edit" @click="cancel()" href="javascript:;"> الغاء </a>
                </div>
                </div>

            </div>
            <div class="card-body pt-0">
                <div v-if="( notifications !== null && query === null) ||
                Object.keys(query).length === 0" class="card-body p-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>

                                <tr>
                                    <th class="col-sm-2 text-uppercase text-secondary text-xs font-weight-bolder ">
                                        مفتاح
                                    </th>
                                    <th class="text-end text-uppercase text-secondary text-xs font-weight-bolder ">
                                        رسالة
                                    </th>
                                    <th class="text-end text-uppercase text-secondary text-xs font-weight-bolder ">
                                        Ac
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(p, index) in paginated" :key="index" class="py-0 my-0">
                                    <td>
                                        <div>
                                            <h6 class="mb-0 text-sm ">
                                                {{ p.key }}
                                            </h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0 text-sm ">
                                                {{ p.value }}
                                            </h6>
                                        </div>
                                    </td>
                                    <td>
                                        <a class="btn btn-link text-dark px-3 mb-0" @click="update(p,index)" href="javascript:;"><i class="fas fa-pencil-alt text-dark ms-2" aria-hidden="true"></i>تعديل</a>

                                        <a class="btn btn-link text-danger text-gradient px-3 mb-0" @click="destroy(p.id,index)" href="javascript:;"><i class="far fa-trash-alt ms-2" aria-hidden="true"></i>حذف</a>
                                    </td>

                                </tr>
                            </tbody>

                        </table>
                        <div class="paginate-div" v-if="Object.keys(notifications).length > 0">
                            <vue-awesome-paginate :total-items="notifications.total" :items-per-page="notifications.per_page" :max-pages-shown="5" :current-page="notifications.current_page" :on-click="onClickHandler" />
                        </div>
                    </div>
                </div>
                <div v-if="Object.keys(query).length > 0 && query !== null" class="card-body p-0 pb-2">

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>

                                <tr>
                                    <th class="col-sm-2 text-uppercase text-secondary text-xs font-weight-bolder ">
                                        مفتاح
                                    </th>
                                    <th class="text-end text-uppercase text-secondary text-xs font-weight-bolder ">
                                        رسالة
                                    </th>
                                    <th class="text-end text-uppercase text-secondary text-xs font-weight-bolder ">
                                        Ac
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(p, index) in dataSearch" :key="index" class="py-0 my-0">
                                    <td>
                                        <div>
                                            <h6 class="mb-0 text-sm ">
                                                {{ p.key }}
                                            </h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0 text-sm ">
                                                {{ p.value }}
                                            </h6>
                                        </div>
                                    </td>
                                    <td>
                                        <a class="btn btn-link text-dark px-3 mb-0" @click="update(p,index)" href="javascript:;"><i class="fas fa-pencil-alt text-dark ms-2" aria-hidden="true"></i>تعديل</a>

                                        <a class="btn btn-link text-danger text-gradient px-3 mb-0 " @click="destroy(p.id,index)" href="javascript:;"><i class="far fa-trash-alt ms-2" aria-hidden="true"></i>حذف</a>
                                    </td>

                                </tr>
                            </tbody>

                        </table>
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

export default {
    data() {
        return {
            title: '',
            image: '',
            body: '',
            type: "main",

            notifications: [],
            key: '',
            value: '',

            season: '',
            query: '',
            dataSearch: [],

            genre: "",

            tmdb: {
                movies: [],
                series: []
            },
            genres: [],
            paginate: ["genres"],
            edit: false,
            editing: {}
        };
    },
    async mounted() {
        let response = await axios.get(url + "/admin/get/notifications/data");
        this.notifications = response.data;

    },
    computed: {
        paginated() {
            return this.notifications.data;
        },
    },
    methods: {
        async send() {

            try {

                await axios.post(url + "/admin/notifications", {
                    "title": this.title,
                    "body" : this.body,
                    "type" : this.type,
                    "image": this.image,
                });

                this.showSuccess();

                (this.title = ""), (this.body = ""), (this.image = "");
            } catch (error) {
                this.showError();
            }
        },
        // create a genre in database
        async store() {
            try {
                let response = await axios.post(url + "/admin/store/notifications", {
                    key: this.key,
                    value: this.value,
                });
                this.notifications.data.unshift(response.data.body);
                this.key = "";
                this.value = "";

                this.showSuccess(response.data.message);
            } catch (error) {
                this.showError(error.response);
            }
        },
        // delete a genre from database
        destroy(id, index) {
            this.showConfirm(async () => {
                try {

                    let response = await axios.delete(
                        url + "/admin/remove/notifications/" + id
                    );
                    let dataIndex = this.notifications.data.findIndex(data => data.id === id);
                    if (this.query.length > 0) {
                        console.log(this.dataSearch);
                        let dataSearchIndex = this.dataSearch.findIndex(data => data.id === id);
                        this.dataSearch.splice(dataSearchIndex, 1);
                    }
                    if (dataIndex >= 0) {
                        this.notifications.data.splice(dataIndex, 1);
                    }
                    this.showSuccess(response.data.message);
                } catch (error) {
                    this.showError();
                }
            });
        },
        // get all themoviedb genres and store them in the database

        update(notification, index) {
            this.edit = true;
            this.key = notification.key;
            this.value = notification.value;
            this.editing = notification;
            this.editing.index = index;
        },
        cancel() {
            this.edit = false;
            this.key = "";
            this.value = "";
            this.editing = {};
        },
        // update a genre from database
        async updateSubmit() {
            try {
                let response = await axios.put(
                    url + "/admin/update/notifications/" + this.editing.id, {
                        key: this.key,
                        value: this.value,
                        id: this.editing.id
                    }
                );
                let dataIndex = this.notifications.data.findIndex(data => data.id === this.editing.id);
                if (this.query.length > 0) {
                    let dataSearchIndex = this.dataSearch.findIndex(data => data.id === this.editing.id);
                    this.dataSearch[dataSearchIndex] = response.data.body;
                }
                if (dataIndex >= 0) {

                    this.notifications.data[dataIndex] = response.data.body;
                }
                this.edit = false;
                this.key = "";
                this.value = "";
                this.editing = {};
                this.showSuccess(response.data.message);
            } catch (error) {
                this.showError(error.response);
            }
        },
        async SEARCH() {
            if (this.query.length > 0) {
                let response = await axios.get(url + "/admin/get/notifications/search?q=" + this.query);
                this.dataSearch = response.data;
                console.log(this.dataSearch);
            }
        },
        async onClickHandler(page) {
            let response = await axios.get(url + "/admin/get/notifications/data?page=" + page);
            this.notifications = response.data;
        }

    },
    mixins: [notifications, settings]
};
</script>

<style scoped>

</style>
