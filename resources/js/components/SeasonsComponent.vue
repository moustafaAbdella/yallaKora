<template>
<div class="row">
    <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
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
    </div>
</div>
<div class="my-3">
    <div class=" col-3 d-flex align-items-center">
        <div class="input-group"><span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
            <input type="text" class="form-control" placeholder="البحث" v-model="query" @keyup="SEARCH"></div>
    </div>
</div>
<div class="row my-3">
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="row mb-1 ">
                    <div class="col-6">
                        <h5> المواسم </h5>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group"><input v-model="season" placeholder="اسم او رقم موسم" class="form-control" name="name" type="text"></div>
                    </div>
                    <div class="col-6">
                        <a class="btn bg-gradient-info mb-0" v-if="!edit" @click="store()" href="javascript:;"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;اضافة موسم </a>
                        <a class="btn bg-gradient-info mb-0" v-if="edit" @click="updateSubmit()" href="javascript:;"> حفظ </a>
                        <a class="btn bg-gradient-danger mb-0 me-2" v-if="edit" @click="cancel()" href="javascript:;"> الغاء </a>

                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div v-if="( seasons !== null && query === null) ||
                Object.keys(query).length === 0" class="card-body p-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>

                                <tr>
                                    <th class="col-sm-2 text-uppercase text-secondary text-xs font-weight-bolder ">
                                        اسم او رقم الموسم
                                    </th>
                                    <th class="text-end text-uppercase text-secondary text-xs font-weight-bolder ">
                                        الاسم الفرعي بالعربي
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(p, index) in paginated" :key="index" class="py-0 my-0">
                                    <td>
                                        <div>
                                            <h6 class="mb-0 text-sm ">
                                                {{ p.name }}
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
                        <div class="paginate-div" v-if="Object.keys(seasons).length > 0">
                            <vue-awesome-paginate :total-items="seasons.total" :items-per-page="seasons.per_page" :max-pages-shown="5" :current-page="seasons.current_page" :on-click="onClickHandler" />
                        </div>
                    </div>
                </div>
                <div v-if="Object.keys(query).length > 0 && query !== null" class="card-body p-0 pb-2">

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>

                                <tr>
                                    <th class="col-sm-2 text-uppercase text-secondary text-xs font-weight-bolder ">
                                        اسم او رقم الموسم
                                    </th>
                                    <th class="text-end text-uppercase text-secondary text-xs font-weight-bolder ">
                                        الاسم الفرعي بالعربي
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(p, index) in dataSearch" :key="index" class="py-0 my-0">
                                    <td>
                                        <div>
                                            <h6 class="mb-0 text-sm ">
                                                {{ p.name }}
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
            count: 0,
            seasons: [],
            season: '',
            query: '',
            dataSearch: [],

            genre: "",

            tmdb: {
                movies: [],
                series: []
            },
            genres: [],
            genre: "",
            paginate: ["genres"],
            edit: false,
            editing: {}
        };
    },
    async mounted() {
        let response = await axios.get(url + "/admin/get/seasons/data");
        this.seasons = response.data;

        let responseCount = await axios.get(url + "/admin/get/seasons/count");
        this.count = responseCount.data.count;
    },
    computed: {
        paginated() {
            return this.seasons.data;
        },
    },
    methods: {
        // create a genre in database
        async store() {
            try {
                let response = await axios.post(url + "/admin/store/seasons", {
                    name: this.season
                });
                this.seasons.data.unshift(response.data.body);
                this.season = "";
               
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
                        url + "/admin/remove/seasons/" + id
                    );
                    let dataIndex = this.seasons.data.findIndex(data => data.id === id);
                    if (this.query.length > 0) {
                        console.log(this.dataSearch);
                        let dataSearchIndex = this.dataSearch.findIndex(data => data.id === id);
                        this.dataSearch.splice(dataSearchIndex, 1);
                    }
                    if (dataIndex >= 0) {
                        this.seasons.data.splice(dataIndex, 1);
                    }
                    this.showSuccess(response.data.message);
                } catch (error) {
                    this.showError();
                }
            });
        },
        // get all themoviedb genres and store them in the database

        update(season, index) {
            this.edit = true;
            this.season = season.name;
            this.editing = season;
            this.editing.index = index;
        },
        cancel() {
            this.edit = false;
            this.season = "";
            this.editing = {};
        },
        // update a genre from database
        async updateSubmit() {
            try {
                let response = await axios.put(
                    url + "/admin/update/seasons/" + this.editing.id, {
                        name: this.season,
                        id: this.editing.id
                    }
                );
                let dataIndex = this.seasons.data.findIndex(data => data.id === this.editing.id);
                if (this.query.length > 0) {
                    let dataSearchIndex = this.dataSearch.findIndex(data => data.id === this.editing.id);
                    this.dataSearch[dataSearchIndex] = response.data.body;
                }
                if (dataIndex >= 0) {

                    this.seasons.data[dataIndex] = response.data.body;
                }
                this.edit = false;
                this.season = "";
                this.editing = {};
                this.showSuccess(response.data.message);
            } catch (error) {
                this.showError(error.response);
            }
        },
        async SEARCH() {
            if (this.query.length > 0) {
                let response = await axios.get(url + "/admin/get/seasons/search?q=" + this.query);
                this.dataSearch = response.data;
                console.log(this.dataSearch);
            }
        },
        async onClickHandler(page) {
            let response = await axios.get(url + "/admin/get/seasons/data?page=" + page);
            this.seasons = response.data;
        }

    },
    mixins: [notifications, settings]
};
</script>

<style scoped>

</style>
