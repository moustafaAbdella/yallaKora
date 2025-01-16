<template>
<div class="row">
    <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                عدد المشتركين اخر 30 يوم
                            </p>
                            <h5 class="font-weight-bolder mb-0">{{ count.user_count }}</h5>
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
                                ارباح اخر 30 يوم
                            </p>
                            <h5 class="font-weight-bolder mb-0">${{ count.total }}</h5>
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
    <div class="col-lg-6 col-md-6 mb-md-0 mb-4 pb-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="row mb-3">
                    <div class="col-6">
                        <h6 v-if="index_plan"> الخطط </h6>
                        <h6 v-if="add_plan"> اضافة خطة </h6>
                        <h6 v-if="edit_plan && form.plan != null">
                            <div class="d-flex px-2 py-1">
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm me-2">
                                        تعديل خطة
                                    </h6>
                                </div>
                            </div>
                        </h6>
                    </div>
                    <div class="col-6 my-auto text-start">
                        <a class="btn bg-gradient-dark mb-0" v-if="index_plan" @click="create_plan()" href="javascript:;"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;اضافة خطة جديد</a>
                        <a class="btn  bg-gradient-dark mb-0" v-if="!index_plan" @click="back_plan()" href="javascript:;"><i class="ni ni-bold-right btn-sm" aria-hidden="true"></i>&nbsp;&nbsp;رجوع </a>

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
            <div v-if="index_plan">
                <div v-if=" plans !== null" class="card-body p-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>

                                <tr>
                                    <th class="text-center text-secondary text-xs font-weight-bolder ">
                                        المدة
                                    </th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder">
                                        السعر
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        حالة
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(p, index) in plans" :key="index">
                                    <td class="align-middle text-center text-sm">

                                        <h6 class="mb-0 text-sm ">
                                            {{ p.duration }} يوم
                                        </h6>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <h6 class="mb-0 text-sm ">
                                            ${{ p.price }}
                                        </h6>
                                        <!-- <span class="text-xs font-weight-bold">
                                        {{ ' ضد ' }} {{ }}
                                    </span> -->
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span v-if="p.active == 0" class="badge badge-sm bg-gradient-danger">معطل</span>
                                        <span v-else-if="p.active == 1" class="badge badge-sm bg-gradient-success">مفعل</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a class="btn btn-link text-dark px-3 mb-0" @click="editing_plan(p)" href="javascript:;"><i class="fas fa-pencil-alt text-dark ms-2" aria-hidden="true"></i>تعديل</a>

                                        <a v-if="p.active == 0" class="btn btn-link text-success text-gradient px-3 mb-0" @click="change_active_plan(p.active,p.id,index)" href="javascript:;"><i class="ni ni-key-25 ms-2" aria-hidden="true"></i>تفعيل</a>
                                        <a v-else-if="p.active == 1" class="btn btn-link text-danger text-gradient px-3 mb-0" @click="change_active_plan(p.active,p.id,index)" href="javascript:;"><i class="ni ni-lock-circle-open ms-2" aria-hidden="true"></i>تعطيل </a>

                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="paginate-div" v-if="Object.keys(plans).length > 3">
                            <vue-awesome-paginate :total-items="plans.length" :items-per-page="3" :max-pages-shown="5" :current-page="1" />
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="add_plan || edit_plan" class="card-body pt-0">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group"><label for="name" class="form-control-label"> مدة باليوم</label><input v-model="form.plan.duration" class="form-control" type="number"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group"><label for="email" class="form-control-label"> سعر </label><input v-model="form.plan.price" class="form-control" type="number"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group"><label for="email" class="form-control-label"> عملة </label><input v-model="form.plan.currency" class="form-control" disabled type="text"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label">حالة</label>
                            <Switch v-model:checked="form.plan.active" labelclass="" />

                        </div>
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
                    <a class="btn bg-gradient-info mb-0" v-if="add_plan" @click="store_plan()" href="javascript:;"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;اضافة </a>
                    <a class="btn  bg-gradient-info mb-0" v-if="edit_plan" @click="update_plan()" href="javascript:;"><i class="ni ni-bold-right btn-sm" aria-hidden="true"></i>&nbsp;&nbsp;حفظ </a>

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
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4 pb-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="row mb-3">
                    <div class="col-6">
                        <h6 v-if="index"> المشتركين </h6>
                        <h6 v-if="add"> اضافة اشتراك يدوي </h6>
                        <h6 v-if="edit && form.subscription != null">
                            <div class="d-flex px-2 py-1">
                                <div>
                                    <img :src="url + '/assets/img/user.png'" height="40" />
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm me-2">
                                        {{ form.subscription.user.name }}
                                    </h6>
                                </div>
                            </div>
                        </h6>
                    </div>
                    <div class="col-6 my-auto text-start">
                        <a class="btn bg-gradient-dark mb-0" v-if="index" @click="create()" href="javascript:;"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;اضافة اشتراك جديد</a>
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
                <div v-if="( subscriptions !== null && query === null) ||
                Object.keys(query).length === 0" class="card-body p-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>

                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder ">
                                        المشتركين
                                    </th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder">
                                        المدة
                                    </th>
                                    <th class="text-center text-secondary text-xs font-weight-bolder">
                                        السعر
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        طريقة الدفع
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        حالة الاشتراك
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        تاريخ بداية الاشتراك
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder ">
                                        تاريخ نهاية الاشتراك
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
                                                    <p class="text-xs font-weight-bold mb-1"> {{ p.user.name}} </p>
                                                    <p class="text-xs text-secondary mb-0">{{ p.user.email }}</p>

                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <h6 class="mb-0 text-sm ">
                                             {{ p.duration }} يوم
                                        </h6>
                                        <!-- <span class="text-xs font-weight-bold">
                                        {{ ' ضد ' }} {{ }}
                                    </span> -->
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <h6 class="mb-0 text-sm ">
                                            ${{ p.price }}
                                        </h6>
                                        <!-- <span class="text-xs font-weight-bold">
                                        {{ ' ضد ' }} {{ }}
                                    </span> -->
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <h6 class="mb-0 text-sm ">
                                            {{ p.type }}
                                        </h6>
                                        <!-- <span class="text-xs font-weight-bold">
                                        {{ ' ضد ' }} {{ }}
                                    </span> -->
                                    </td>
                                    <td class="align-middle text-center">
                                        <span v-if="p.active == 0" class="badge badge-sm bg-gradient-danger">معطل</span>
                                        <span v-else-if="p.active == 1" class="badge badge-sm bg-gradient-success">مفعل</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-1"> {{ getTime(p.start_at)}} </p>
                                        <p class="text-xs text-secondary mb-0">{{ getYear(p.start_at) }}</p>

                                        <!-- <div style="justify-content: center;" class="form-check text-center form-switch">
                                            <input class="form-check-input" type="checkbox" @click="checked(p.id,index,p.featured)" v-model="p.featured">
                                        </div> -->
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-1"> {{ getTime(p.ends_at)}} </p>
                                        <p class="text-xs text-secondary mb-0">{{ getYear(p.ends_at) }}</p>

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
                        <div class="paginate-div" v-if="Object.keys(subscriptions).length > 0">
                            <vue-awesome-paginate v-if="subscriptions.total > 6" :total-items="subscriptions.total" :items-per-page="subscriptions.per_page" :max-pages-shown="5" :current-page="subscriptions.current_page" :on-click="onClickHandler" />
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
                        <div class="form-group">
                            <label for="example-text-input" class="form-control-label"> حساب المشترك </label>
                            <multiselect :disabled="edit" id="user" v-model="form.user" placeholder="Choose your stack" :close-on-select="false" :filter-results="false" :min-chars="0" :resolve-on-load="false" :infinite="true" :limit="10" :object="true" :clear-on-search="true" :delay="0" :searchable="true" :options="async (query) => {
    return await fetchUsers(query)
  }">
                                <template v-slot:singlelabel="{ value }">
                                    <div class="multiselect-single-label">
                                        <img class="character-label-icon ms-2" style="height:30px" :src="url + '/assets/img/user.png'"> {{ value.name + ' - ' + value.email}}
                                    </div>
                                </template>

                                <template v-slot:option="{ option }">
                                    <img class="character-option-icon ms-2" style="height:30px" :src="url + '/assets/img/user.png'"> {{ option.name + ' - '+option.email}}
                                </template>
                            </multiselect>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group"><label for="name" class="form-control-label"> مدة باليوم</label><input v-model="form.subscription.duration" class="form-control" type="number"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group"><label for="email" class="form-control-label"> سعر </label><input v-model="form.subscription.price" class="form-control" type="number"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group"><label for="email" class="form-control-label"> عملة </label><input v-model="form.subscription.currency" class="form-control" disabled type="text"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group"><label for="example-text-input" class="form-control-label"> طريقة الدفع </label><input class="form-control" v-model="form.subscription.type" name="name" type="text"></div>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group"><label for="name" class="form-control-label">تاريخ بداية الاشتراك</label>
                            <!-- <input v-model="form.matche.start_time" class="form-control" type="datetime"> -->
                            <Datepicker v-model="form.subscription.start_at" format="yyyy-MM-dd HH:mm:ss"></Datepicker>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group"><label for="name" class="form-control-label">تاريخ نهاية الاشتراك </label>
                            <!-- <input v-model="form.matche.start_time" class="form-control" type="datetime"> -->
                            <Datepicker v-model="form.subscription.ends_at" format="yyyy-MM-dd HH:mm:ss"></Datepicker>

                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">حالة اشتراك</label>
                        <div style="display: block; padding: 0.5rem 0.75rem;">
                            <Switch v-model:checked="form.subscription.active" labelclass="pe-4" />

                        </div>
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
            plans: [],
            subscriptions: [],
            url: '',
            form: {
                plan: {
                    currency: 'USD',
                },
                subscription: {
                    currency: 'USD',
                },
                user: {},
                // selectedSeason: [],
            },
            index_plan: true,
            add_plan: false,
            edit_plan: false,
            count: [],

            users: [],
            query: "",
            dataSearch: [],

            seasons: [],
            //isLoadingSeasons: false,
            logo: null,
            index: true,
            add: false,
            edit: false,
            csrf: document.head.querySelector('meta[name="csrf-token"]') ? document.head.querySelector('meta[name="csrf-token"]').content : '',
        };
    },
    async mounted() {
        let responsePlans = await axios.get(url + "/admin/get/subscriptions/data");
        this.plans = responsePlans.data;
        let responseCount = await axios.get(url + "/admin/get/subscriptions/count");
        this.count = responseCount.data;

        this.url = url;

        let response = await axios.get(url + "/admin/get/subscriptions/history/data");
        this.subscriptions = response.data;
    },
    computed: {
        paginated() {
            return this.subscriptions.data;
        },
    },
    methods: {
        create_plan() {
            this.index_plan = false;
            this.edit_plan = false;
            this.add_plan = true;
        },
        back_plan() {
            this.add_plan = false;
            this.edit_plan = false;
            this.form.plan = {
                currency: 'USD',
            };
            this.index_plan = true;
        },
        // update a record (team) in the database
        update_plan() {
            axios
                .put(url + "/admin/update/subscriptions/" + this.form.plan.id, this.form)
                .then(response => {
                    this.add_plan = false;
                    this.edit_plan = false;
                    this.index_plan = true;
                    this.form.plan = {
                        currency: 'USD',
                    };
                    let dataIndex = this.plans.findIndex(data => data.id === this.form.user.id);
                    this.plans[dataIndex] = response.data.body;
                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    console.log(error);
                    this.showError(error.response);
                });
        },
        // delete a record (team) in the databse
        async change_active_plan(active, id, index) {
            await axios
                .get(url + "/admin/update/subscriptions/active/" + id + '?active=' + !active)
                .then(response => {
                    this.plans[index].active = !active;
                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    this.plans[index].active = active;

                    this.showError();
                });

        },
        // change the view to the editing form
        editing_plan(plan) {
            this.index_plan = false;
            this.add_plan = false;
            this.edit_plan = true;
            this.form.plan = plan;
        },
        store_plan() {
            axios
                .post(url + "/admin/store/subscriptions", this.form)
                .then(response => {
                    this.add_plan = false;
                    this.edit_plan = false;
                    this.index_plan = true;
                    this.form.plan = {
                        currency: 'USD',
                    };
                    this.logo = null;
                    this.plans.unshift(response.data.body);

                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    console.log(error);
                    this.showError(error.response);
                });
        },
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
                plan: {
                    currency: 'USD',
                },
                subscription: {
                    currency: 'USD',
                },
                user: {},
                // selectedSeason: [],
            };
            this.index = true;
        },
        // update a record (team) in the database
        update() {
            this.form.subscription.start_at = moment(this.form.subscription.start_at)
                .format('yyyy/MM/DD hh:mm:ss');
            this.form.subscription.ends_at = moment(this.form.subscription.ends_at)
                .format('yyyy/MM/DD hh:mm:ss');
            axios
                .put(url + "/admin/update/subscriptions/history/" + this.form.subscription.id, this.form)
                .then(response => {
                    debugger;

                    this.add = false;
                    this.edit = false;
                    this.index = true;
                    this.form.subscription = {
                        currency: 'USD',
                    };

                    let dataIndex = this.subscriptions.data.findIndex(data => data.id === this.form.user.id);
                    if (this.query.length > 0) {
                        let dataSearchIndex = this.dataSearch.findIndex(data => data.id === this.form.user.id);
                        this.dataSearch[dataSearchIndex] = response.data.body;
                    }
                    console.log(dataIndex);
                    this.subscriptions.data[dataIndex] = response.data.body;
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
                    let response = await axios.delete(url + "/admin/remove/subscriptions/history/" + id);

                    let dataIndex = this.subscriptions.data.findIndex(data => data.id === id);
                    if (this.query.length > 0) {
                        console.log(this.dataSearch);
                        let dataSearchIndex = this.dataSearch.findIndex(data => data.id === id);
                        this.dataSearch.splice(dataSearchIndex, 1);
                    }
                    this.subscriptions.data.splice(dataIndex, 1);

                    //  this.paginate.filteredMovies.list.splice(index, 1);
                    this.showSuccess(response.data.message);
                } catch (error) {
                    this.showError();
                }
            });
        },
        // change the view to the editing form
        editing(subscription) {
            this.index = false;
            this.add = false;
            this.edit = true;
            this.form.subscription = subscription;

        },
        store() {
            this.form.subscription.start_at = moment(this.form.subscription.start_at)
                .format('yyyy/MM/DD hh:mm:ss');
            this.form.subscription.ends_at = moment(this.form.subscription.ends_at)
                .format('yyyy/MM/DD hh:mm:ss');
            axios
                .post(url + "/admin/store/subscriptions/history", this.form)
                .then(response => {
                    this.add = false;
                    this.edit = false;
                    this.index = true;
                    this.form.subscription = {
                        currency: 'USD',
                    };
                    this.logo = null;
                    this.subscriptions.data.unshift(response.data.body);

                    this.showSuccess(response.data.message);
                })
                .catch(error => {
                    console.log(error);
                    this.showError(error.response);
                });
        },
        async onClickHandler(page) {

            let response = await axios.get(url + "/admin/get/subscriptions/history/data?page=" + page);
            this.subscriptions = response.data;
        },
        async fetchUsers(query) {
            if (query.length > 0) {
                let response = await axios.get(url + "/admin/get/users/search?q=" + query);
                console.log(response.data);
                let array = [];
                for (let index = 0; index < response.data.length; index++) {
                    array.push({
                        value: response.data[index].id,
                        name: response.data[index].name,
                        email: response.data[index].email,
                    });

                }

                return array;

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
                let response = await axios.get(url + "/admin/get/subscriptions/h/search?q=" + this.query);
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
