<template>
<div class="row">
    <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                مباريات اليوم
                            </p>
                            <h5 class="font-weight-bolder mb-0">{{ analysis.countMatches }}</h5>
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
                            <h5 class="font-weight-bolder mb-0">{{ analysis.countMatchesComing }}</h5>
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
                            <h5 class="font-weight-bolder mb-0">{{ analysis.countMatchesLive }}</h5>
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
    <div class="col-lg-3 col-sm-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                مستخدمين
                            </p>
                            <h5 class="font-weight-bolder mb-0">{{ analysis.countUsers }}</h5>
                        </div>
                    </div>
                    <div class="col-4 text-start">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row my-4">
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="row mb-3">
                    <div class="col-6">
                        <h6>مباريات الان</h6>
                    </div>
                    <div class="col-6 my-auto text-start">
                        <div class="dropdown float-start ps-4">
                            <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-v text-secondary"></i>
                            </a>
                            <!-- <ul class="dropdown-menu px-2 py-3 me-n4" aria-labelledby="dropdownTable">
                                <li>
                                    <a class="dropdown-item border-radius-md" href="javascript:;">عمل</a>
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md" href="javascript:;">عمل آخر</a>
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md" href="javascript:;">شيء آخر هنا</a>
                                </li>
                            </ul> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>

                            <tr>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder ">
                                    الفريق
                                </th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2">
                                    النتيجة
                                </th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                    الدقيقة
                                </th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                    تاريخ بدا المباراة
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
                                    <span class="text-xs font-weight-bold">
                                        {{ p.team_re_a + ' ضد ' + p.team_re_b}} {{ }}
                                    </span>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">
                                        {{p.gameTime}}
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    <p class="text-xs font-weight-bold mb-1"> {{ getTime(p.startTime)}} </p>
                                    <p class="text-xs text-secondary mb-0">{{ getYear(p.startTime) }}</p>

                                    <!-- <div style="justify-content: center;" class="form-check text-center form-switch">
                                            <input class="form-check-input" type="checkbox" @click="checked(p.id,index,p.featured)" v-model="p.featured">
                                        </div> -->
                                </td>
                            </tr>
                        </tbody>

                    </table>
                    <div class="paginate-div">
                        <vue-awesome-paginate :total-items="matches.length" :items-per-page="pageSize" :max-pages-shown="5" :current-page="current" :on-click="onClickHandler" />
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
import moment from 'moment';
import momentTs from 'moment-timezone';
export default {
    data() {
        return {
            analysis: [],
            matches: [],
            edit: false,
            current: 1,
            pageSize: 7,
            url: '',
        };
    },
    async mounted() {
        this.url = url;
        let response = await axios.get(url + "/admin/get/dashboard/analysis");
        this.analysis = response.data;
        this.matches = response.data.lastMatchesComing;
        console.log(this.matches.length);
    },
    computed: {
        indexStart() {
            return (this.current - 1) * this.pageSize;
        },
        indexEnd() {
            return this.indexStart + this.pageSize;
        },
        paginated() {
            return this.matches.slice(this.indexStart, this.indexEnd);
        }

    },
    methods: {
        onClickHandler(page) {
            this.current = page;
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

            console.log(currentzone);
            //Apply zone changes
            var newtime = moment.tz(date, 'Asia/Riyadh').clone().tz(currentzone).format(time_format).toString();
            console.log(d + '  ' + newtime);
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
        }
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
    width: 40px;
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
