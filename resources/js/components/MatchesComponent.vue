<template>
<div class="row">
    <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                عدد المباريات
                            </p>
                            <h5 class="font-weight-bolder mb-0">{{ matches.total }}</h5>
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
                                مباريات اليوم
                            </p>
                            <h5 class="font-weight-bolder mb-0">{{ count.todaycount }}</h5>
                        </div>
                    </div>
                    <div class="col-4 text-start">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
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
                                مباريات قيد الانتظار
                            </p>
                            <h5 class="font-weight-bolder mb-0">{{ count.comingcount }}</h5>
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
                                مباريات الان
                            </p>
                            <h5 class="font-weight-bolder mb-0">{{ count.livecount }}</h5>
                        </div>
                    </div>
                    <div class="col-4 text-start">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row my-3" v-if="index">
    <div class=" col-3 d-flex align-items-center">
        <div class="input-group">
            <input type="date" class="form-control" placeholder="البحث" v-model="query" @change="SEARCH">
        </div>
    </div>
    <div class="col-3">
        <multiselect v-model="selectedStatus" @select="SEARCH" mode="single" :close-on-select="true" :options="[
    { value: 'coming', label: 'قريبا' },
    { value: 'live', label: 'لايف' },
    { value: 'done', label: 'انتهت' },
  ]" />
    </div>
</div>
<div class="row my-3">
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="row mb-3">
                    <div class="col-6">
                        <h6 v-if="index"> المباريات </h6>
                        <h6 v-if="add"> اضافة مباراة </h6>
                        <h6 v-if="edit && form.matche != null">
                            <div class="d-flex px-2 py-1">
                                <div>
                                    <img :src="url + '/api/image/team-logo/' +form.matche.team_a.img" class="avatar avatar-sm ms-3" />
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm d-flex">
                                        <span class="ms-1"> {{ form.matche.team_re_a }} </span> Vs <span class="me-1"> {{' ' + form.matche.team_re_b }} </span>
                                    </h6>
                                </div>
                                <div class="ms-2">
                                    <img :src="url +  '/api/image/team-logo/' + (form.matche.team_b.img ?form.matche.team_b.img:'placeholder.png')" class="avatar avatar-sm me-3" />
                                </div>

                                <div class="col-sm-3 m-auto me-2">
                                    <p class="text-xs font-weight-bold mb-1">{{ form.matche.team_a.name_ar? form.matche.team_a.name_ar : form.matche.team_a.name }}</p>
                                    <p class="text-xs text-secondary mb-0">{{ form.matche.team_b.name_ar? form.matche.team_b.name_ar : form.matche.team_b.name }}</p>
                                </div>

                            </div>

                        </h6>
                    </div>
                    <div class="col-6 my-auto text-start">
                        <a class="btn bg-gradient-dark mb-0" v-if="index" @click="create()" href="javascript:;"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;اضافة مباراة جديد</a>
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
                <div v-if="matches !== null" class="card-body p-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>

                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder ">
                                        المباراة
                                    </th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">
                                        اظهار في تطبيق
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        حالة مباراة
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        تاريخ بدا المبارة
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
                                                <img :src="url + '/api/image/team-logo/' +p.team_a.img" class="avatar avatar-sm ms-3" />
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm d-flex">
                                                    <span class="ms-1"> {{ p.team_re_a }} </span> Vs <span class="me-1"> {{' ' + p.team_re_b }} </span>
                                                </h6>
                                            </div>
                                            <div class="ms-2">
                                                <img :src="url +  '/api/image/team-logo/' + (p.team_b.img ?p.team_b.img:'placeholder.png')" class="avatar avatar-sm me-3" />
                                            </div>

                                            <div class="col-sm-3 m-auto me-2">
                                                <p class="text-xs font-weight-bold mb-1">{{ p.team_a.name_ar? p.team_a.name_ar : p.team_a.name }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ p.team_b.name_ar? p.team_b.name_ar : p.team_b.name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <Switch v-model:checked="p.active" labelclass="pe-4" @change="checked(p.id,index,p.active)" />
                                        <!-- <span class="text-xs font-weight-bold">
                                        {{ ' ضد ' }} {{ }}
                                    </span> -->
<!--                                        {ended:4,started:3,scheduled:2,anticipated:1}-->
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span v-if="p.statusText == 'Scheduled'" class="badge badge-sm bg-gradient-secondary">قريبا</span>
                                        <span v-else-if="p.statusGroup == 3" class="badge badge-sm bg-gradient-success">Live</span>
                                        <span v-else-if="p.statusText == 'Ended' || p.justEnded == 1" class="badge badge-sm bg-gradient-danger">انتهت</span>
                                        <span v-else-if="p.shortStatusText == 'FRO'" class="badge badge-sm bg-gradient-warning">لم تبدأ</span>
                                        <!--                                        <span v-else-if="p.statusText == 'SUSP'" class="badge badge-sm bg-gradient-warning">معلقة</span>-->
<!--                                        <span v-else-if="p.statusText == 'POSTP'" class="badge badge-sm bg-gradient-warning">تأجلت</span>-->
<!--                                        <span v-else-if="p.statusText == 'Inte'" class="badge badge-sm bg-gradient-warning">توقف</span>-->
                                        <span v-else-if="p.statusText == 'Cancelled'" class="badge badge-sm bg-gradient-warning">ألغيت</span>
<!--                                        <span v-else-if="p.statusText == 'ABAN'" class="badge badge-sm bg-gradient-warning">غير مأهولة</span>-->
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-1"> {{ getTime(p.startTime)}} </p>
                                        <p class="text-xs text-secondary mb-0">{{ getYear(p.startTime) }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a class="btn btn-link text-dark px-3 mb-0" @click="editing(p)" href="javascript:;"><i class="fas fa-pencil-alt text-dark ms-2" aria-hidden="true"></i>تعديل</a>
                                        <a class="btn btn-link text-danger text-gradient px-3 mb-0" @click="destroy(p.id,index)" href="javascript:;"><i class="far fa-trash-alt ms-2" aria-hidden="true"></i>حذف</a>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="paginate-div" v-if="Object.keys(matches).length > 0">
                            <vue-awesome-paginate :total-items="matches.total" :items-per-page="matches.per_page" :max-pages-shown="5" :current-page="matches.current_page" :on-click="onClickHandler" />
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="add || edit" class="card-body pt-0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label"> الفريق الاول </label>
                            <multiselect id="team_a" v-model="form.team_a" placeholder="Choose your stack" :close-on-select="false" :filter-results="false" :min-chars="0" :resolve-on-load="false" :infinite="true" :limit="10" :object="true" :clear-on-search="true" :delay="0" :searchable="true" :options="async (query) => {
    return await fetchTeams(query)
  }">
                                <template v-slot:singlelabel="{ value }">
                                    <div class="multiselect-single-label">
                                        <img class="character-label-icon ms-2" style="height:30px" :src="url +  '/api/image/team-logo/' +value.img"> {{ (value.name_ar ? value.name_ar + ' - ' : '') + value.name}}
                                    </div>
                                </template>

                                <template v-slot:option="{ option }">
                                    <img class="character-option-icon ms-2" style="height:30px" :src="url +  '/api/image/team-logo/' + option.img"> {{ (option.name_ar ? option.name_ar + ' - ' :"" )+option.name}}
                                </template>
                            </multiselect>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label"> الفريق الثاني </label>
                            <multiselect id="team_b" v-model="form.team_b" placeholder="Choose your stack" :close-on-select="false" :filter-results="false" :min-chars="0" :resolve-on-load="false" :infinite="true" :limit="10" :object="true" :clear-on-search="true" :delay="0" :searchable="true" :options="async (query) => {
    return await fetchTeams(query)
  }">
                                <template v-slot:singlelabel="{ value }">
                                    <div class="multiselect-single-label">
                                        <img class="character-label-icon ms-2" style="height:30px" :src="url +  '/api/image/team-logo/' +value.img"> {{ value.name_ar + ' - ' + value.name}}
                                    </div>
                                </template>

                                <template v-slot:option="{ option }">
                                    <img class="character-option-icon ms-2" style="height:30px" :src="url +  '/api/image/team-logo/' + option.img"> {{ option.name_ar + ' - ' +option.name}}
                                </template>
                            </multiselect>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label for="name" class="form-control-label">نتيجة الفريق الاول</label><input v-model="form.matche.team_re_a" class="form-control" type="number"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label for="name_ar" class="form-control-label"> نتيجة الفريق الثاني</label><input v-model="form.matche.team_re_b" class="form-control" type="number"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label"> البطولة </label>
                            <multiselect id="league" v-model="form.league" placeholder="Choose your stack" :close-on-select="false" :filter-results="false" :min-chars="0" :resolve-on-load="false" :infinite="true" :limit="10" :object="true" :clear-on-search="true" :delay="0" :searchable="true" :options="async (query) => {
    return await fetchLeagues(query)
  }">
                                <template v-slot:singlelabel="{ value }">
                                    <div class="multiselect-single-label">
                                        <img class="character-label-icon ms-2" style="height:30px" :src="url +  '/api/image/comp-logo/' +value.img"> {{ (value.name_ar ? value.name_ar + ' - ' : '') + value.name}}
                                    </div>
                                </template>

                                <template v-slot:option="{ option }">
                                    <img class="character-option-icon ms-2" style="height:30px" :src="url +  '/api/image/comp-logo/' + option.img"> {{ (option.name_ar ? option.name_ar + ' - ' : '' )+option.name}}
                                </template>
                            </multiselect>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label"> الموسم </label>
                            <multiselect id="season" v-model="form.season" placeholder="Choose your stack" :close-on-select="false" :filter-results="false" :min-chars="0" :resolve-on-load="false" :infinite="true" :limit="10" :object="true" :clear-on-search="true" :delay="0" :searchable="true" :options="async (query) => {
    return await fetchSeasons(query)
  }">
                                <template v-slot:singlelabel="{ value }">
                                    <div class="multiselect-single-label">
                                        {{ value.name}}
                                    </div>
                                </template>

                                <template v-slot:option="{ option }">
                                    {{ option.name}}
                                </template>
                            </multiselect>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">اظهار في تطبيق</label>
                            <div style="display: block; padding: 0.5rem 0.75rem;">
                                <Switch v-model:checked="form.matche.active" labelclass="pe-4" />

                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group"><label for="name" class="form-control-label">تاريخ بداية المباراة</label>
                            <!-- <input v-model="form.matche.start_time" class="form-control" type="datetime"> -->
                            <Datepicker v-model="form.matche.startTime" format="yyyy-MM-dd HH:mm:ss"></Datepicker>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group"><label for="name" class="form-control-label">الدقيقة</label><input v-model="form.matche.gameTime" class="form-control" type="number"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="link">رابط فيديو</label>
                            <input type="text" id="link" v-model="link" class="form-control" placeholder="ادخل رابط فيديو  HLS / M3U8 / MKV / MP4 / WEBM / OGV / FLV / EMBED / IFRAME " />
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="server">اختر سيرفر فيديو</label>
                            <multiselect
                                :options="servers"
                                v-model="selectedServer"
                                placeholder="Select one"
                                :searchable="true"
                                track-by="name"
                                label="name"
                                :object="true"
                            ></multiselect>
                        </div>
                    </div>

                    <div class="col-md-1 my-auto pt-2">
                        <button class="btn bg-gradient-primary mb-0" @click.prevent="addLink()">
                            <i class="material-icons md-16">اضافة</i>
                        </button>
                    </div>
                </div>
                <table class="table align-items-center p-4">
                    <thead>

                        <tr>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder ">
                                نوع
                            </th>
                            <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                                الرابط
                            </th>
                            <th class="text-uppercase  text-uppercase text-secondary text-xs font-weight-bolder ps-9">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in form.links" :key="index">
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <p class="text-xs font-weight-bold mb-1">{{ item.server }}</p>
                                </div>
                            </td>

                            <td class="align-middle text-end">
                                <p class="text-xs font-weight-bold mb-1"> {{ item.link }} </p>
                            </td>
                            <td class="align-middle text-end">
                                <a class="btn btn-link text-danger text-gradient px-3 mb-0" @click="destroyLink(item,index)" href="javascript:;"><i class="far fa-trash-alt ms-2" aria-hidden="true"></i>حذف</a>

                            </td>
                        </tr>
                    </tbody>

                </table>
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
import {
    notifications
} from "../mixins/notifications";
import moment from 'moment';
import momentTs from 'moment-timezone';
import Switch from '../core/Switch.vue';

export default {

    data() {
        return {
            matches: [],
            count: [],
            servers: [],
            selectedServer: "",
            query: "",
            selectedStatus: [],
            form: {
                matche: {},
                league: {},
                season: {},
                team_a: {},
                team_b: {},
                links: [],
                // selectedSeason: [],
            },
            link: '',
            type: '',
            type_video: ['embed', 'hls', 'youtube'],

            dataSearch: [],

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
        let response = await axios.get(url + "/admin/get/matches/data");
        this.matches = response.data;
        let responseCount = await axios.get(url + "/admin/get/matches/count");
        this.count = responseCount.data;
        console.log(this.csrf);
        response = await axios.get(url + "/admin/servers/data");
        this.servers = response.data;
        for (let i = 0; i < this.servers.length; i++) {
            this.servers[i].value = this.servers[i].id;
        }
    },
    computed: {
        paginated() {
            return this.matches.data;
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
            this.form = {
                matche: {},
                league: {},
                season: {},
                team_a: {},
                team_b: {},
                links: {}
                // selectedSeason: [],
            };
            this.logo = null;
            this.index = true;
        },
        // update a record (league) in the database
        update() {
            delete this.form.matche.team_a;
            delete this.form.matche.team_b;
            delete this.form.matche.comp;
            delete this.form.matche.season;
            delete this.form.matche.videos;

            axios
                .put(url + "/admin/update/matches/" + this.form.matche.id, this.form)
                .then(response => {

                    this.add = false;
                    this.edit = false;
                    this.index = true;
                    let dataIndex = this.matches.data.findIndex(data => data.id === this.form.matche.id);

                    if (dataIndex >= 0) {

                        this.matches.data[dataIndex] = response.data.body;
                    }
                    this.form.matche = {};
                    this.form.team_a = {};
                    this.form.team_b = {};
                    this.form.league = {};
                    this.form.links = {};
                    this.form.season = {};

                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    console.log(error);
                    this.showError(error.response);
                });
        },
        // add a new link to the movie
        addLink() {
            if (this.selectedServer === "" || this.link === "" || this.form.movie === "")
                return;
            this.form.links.unshift({
                server: this.selectedServer,
                link: this.link,
            });
            this.link = "";
            this.server = "";
        },
        test(value) {
            return value;
        },
        checked(id, index, value) {
            console.log('dsdadas' + value);
            axios
                .get(url + "/admin/update/matches/active/" + id + '?active=' + value)
                .then(response => {
                    this.matches.data[index].active = value;
                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    this.matches.data[index].active = !value;

                    this.showError();
                });

        },
        // delete a link of the matche
        destroyLink(link, index) {
            this.showConfirm(async () => {
                if (link.id) {
                    await axios
                        .delete(url + "/admin/remove/matches/videos/" + link.id)
                        .then(response => {
                            this.form.links.splice(index, 1);

                            this.showSuccess(response.data.message);
                        })
                        .catch(error => {
                            this.showError();
                        });
                } else {
                    this.form.links.splice(index, 1);

                }
            });
        },
        // delete a record (league) in the databse
        destroy(id, index) {
            this.showConfirm(async () => {
                try {
                    let response = await axios.delete(url + "/admin/remove/matches/" + id);

                    let dataIndex = this.matches.data.findIndex(data => data.id === id);
                    if (this.query.length > 0) {
                        console.log(this.dataSearch);
                        let dataSearchIndex = this.dataSearch.findIndex(data => data.id === id);
                        this.dataSearch.splice(dataSearchIndex, 1);
                    }
                    if (dataIndex >= 0) {
                        this.matches.data.splice(dataIndex, 1);
                    }
                    //  this.paginate.filteredMovies.list.splice(index, 1);
                    this.showSuccess(response.data.message);
                } catch (error) {
                    this.showError();
                }
            });
        },
        // change the view to the editing form
        async editing(matche) {
            this.index = false;
            this.add = false;
            this.edit = true;
            this.form.matche = matche;

            // set links
            let response = await axios.get(url + "/admin/get/matches/videos/" + this.form.matche.id);
            this.form.links = response.data;

            //get select team
            this.form.team_a.value = matche.team_a.id;
            this.form.team_a.img = matche.team_a.img;
            this.form.team_a.name = matche.team_a.name;
            this.form.team_a.name_ar = matche.team_a.name_ar;
            this.form.team_b.value = matche.team_b.id;
            this.form.team_b.img = matche.team_b.img;
            this.form.team_b.name = matche.team_b.name;
            this.form.team_b.name_ar = matche.team_b.name_ar;
            //get select season
            if (matche.season_id != null) {
                this.form.season.value = matche.season.id;
                this.form.season.name = matche.season.name;
            }
            if (matche.com_id != null) {
                this.form.league.value = matche.comp.id;
                this.form.league.name = matche.comp.name;
                this.form.league.name_ar = matche.comp.name_ar;
                this.form.league.img = matche.comp.img;
            }
        },
        store() {
            var responseDates = moment(this.form.matche.start_time)
                .format('yyyy/MM/DD hh:mm:ss');
            console.log(responseDates)
            // console.log(this.form.matche.start_time.toISOString());)
            console.log(
                this.form.matche.start_time);
            this.form.matche.start_time = responseDates;

            console.log(this.getTimezone());
            axios
                .post(url + "/admin/store/matches", this.form)
                .then(response => {
                    this.add = false;
                    this.edit = false;
                    this.index = true;
                    this.form.matche = {};
                    this.form.team_a = {};
                    this.form.team_b = {};
                    this.form.league = {};
                    this.form.links = {};
                    this.form.season = {};
                    this.matches.data.unshift(response.data.body);

                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    console.log(error);
                    this.showError(error.response);
                });
        },

        async onClickHandler(page) {
            const params = {};
            if (this.query.length > 0) {
                params.date = this.query;
            }
            if (this.selectedStatus.length > 0) {
                params.status = this.selectedStatus;
            }
            params.page = page;
            let response = await axios.get(url + "/admin/get/matches/data", {
                params: params
            });
            this.matches = response.data;
        },
        nameWithLang(name) {
            console.log(name);
            return `${name}`
        },
        async SEARCH() {
            const params = {};

            if (this.query.length > 0) {
                params.date = this.query;
            }
            if (this.selectedStatus.length > 0) {
                params.status = this.selectedStatus;
            }

            //console.log(params);
            let response = await axios.get(url + "/admin/get/matches/data", {
                params: params
            });
            this.matches = response.data;

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
            console.log(currentzone)
            return currentzone;
        },
        getYear(date) {

            const d = new Date(date);
            return moment(d).format("MMM DD, YYYY");
        },
        async fetchTeams(query) {
            this.isLoading = true;
            var array = [];
            let response = await axios.get(url + "/admin/get/teams/search?q=" + query);
            for (let index = 0; index < response.data.length; index++) {
                array.push({
                    value: response.data[index].id,
                    img: response.data[index].img,
                    name: response.data[index].name,
                    name_ar: response.data[index].name_ar,

                });

            }
            return array;
        },
        async fetchLeagues(query) {

            this.isLoading = true;

            var array = [];
            let response = await axios.get(url + "/admin/get/leagues/search?q=" + query);
            for (let index = 0; index < response.data.length; index++) {
                array.push({
                    value: response.data[index].id,
                    img: response.data[index].img,
                    name: response.data[index].name,
                    name_ar: response.data[index].name_ar,

                });

            }
            return array;
        },
        async fetchSeasons(query) {
            if (query.length > 0) {
                let response = await axios.get(url + "/admin/get/seasons/search?q=" + query);
                console.log(response.data);

                var array = [];
                for (let index = 0; index < response.data.length; index++) {
                    array.push({
                        value: response.data[index].id,
                        name: response.data[index].name,
                    });

                }
                return array;

                //console.log(this.seasons);
            }
        },
    },
    components: {
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
