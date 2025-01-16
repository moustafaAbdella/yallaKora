<template>
  <div class="row">
    <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">عدد القنوات</p>
                <h5 class="font-weight-bolder mb-0">{{ livetvs.total }}</h5>
              </div>
            </div>
            <div class="col-4 text-start">
              <div
                class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md"
              >
                <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="my-3" v-if="index">
    <div class="col-3 d-flex align-items-center">
      <div class="input-group">
        <span class="input-group-text text-body"
          ><i class="fas fa-search" aria-hidden="true"></i
        ></span>
        <input
          type="text"
          class="form-control"
          placeholder="البحث"
          v-model="query"
          @keyup="SEARCH"
        />
      </div>
    </div>
  </div>
  <div class="row my-3">
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
      <div class="card">
        <div class="card-header pb-0">
          <div class="row mb-3">
            <div class="col-6">
              <h6 v-if="index">القنوات</h6>
              <h6 v-if="add">اضافة قناة</h6>
              <h6 v-if="edit && form.livetv != null">
                <div class="d-flex px-2 py-1">
                  <div>
                    <img
                      :src="url + '/storage/livetv/' + form.livetv.logo"
                      class="avatar avatar-sm ms-3"
                    />
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="mb-0 text-sm">
                      {{ form.livetv.name }}
                    </h6>
                  </div>
                </div>
              </h6>
            </div>
            <div class="col-6 my-auto text-start">
              <a
                class="btn bg-gradient-dark mb-0"
                v-if="index"
                @click="create()"
                href="javascript:;"
                ><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;اضافة قناة
                جديد</a
              >
              <a
                class="btn bg-gradient-dark mb-0"
                v-if="!index"
                @click="back()"
                href="javascript:;"
                ><i class="ni ni-bold-right btn-sm" aria-hidden="true"></i
                >&nbsp;&nbsp;رجوع
              </a>

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
          <div
            v-if="(livetvs !== null && query === null) || Object.keys(query).length === 0"
            class="card-body p-0 pb-2"
          >
            <div class="table-responsive">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                      القناة
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xs font-weight-bolder"
                    >
                      ترتيب قناة
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xs font-weight-bolder"
                    >
                      اظهار في تطبيق
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xs font-weight-bolder"
                    >
                      Action
                    </th>
                  </tr>
                </thead>
                <draggable tag="tbody" :list="paginated" @change="log">
                  <tr v-for="(p, index) in paginated" :key="index">
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <img
                            :src="url + '/storage/livetv/' + p.logo"
                            class="avatar avatar-sm ms-3"
                          />
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">
                            {{ p.name }}
                          </h6>
                        </div>
                      </div>
                    </td>
                    <td class="align-middle text-center">
                      <h6 class="mb-0 text-sm">
                        {{ p.position }}
                      </h6>
                    </td>
                    <td class="align-middle text-center">
                      <!-- <toggle v-model="p.featured" @change="checked(p.id,index,p.featured)" class="toggle-blue" /> -->
                      <Switch
                        v-model:checked="p.active"
                        labelclass="justify-content-center"
                        @change="checked(p.id, index, p.active)"
                      />

                      <!-- <div style="justify-content: center;" class="form-check text-center form-switch">
                                            <input class="form-check-input" type="checkbox" @change="checked(p.id,index,p.featured)" :checked="p.featured">
                                        </div> -->
                    </td>
                    <td class="align-middle text-center">
                      <a
                        class="btn btn-link text-dark px-3 mb-0"
                        @click="editing(p)"
                        href="javascript:;"
                        ><i
                          class="fas fa-pencil-alt text-dark ms-2"
                          aria-hidden="true"
                        ></i
                        >تعديل</a
                      >

                      <a
                        class="btn btn-link text-danger text-gradient px-3 mb-0"
                        @click="destroy(p.id, index)"
                        href="javascript:;"
                        ><i class="far fa-trash-alt ms-2" aria-hidden="true"></i>حذف</a
                      >
                    </td>
                  </tr>
                </draggable>
              </table>
              <div
                class="paginate-div"
                v-if="Object.keys(livetvs).length > 0 && livetvs.total > 6"
              >
                <vue-awesome-paginate
                  :total-items="livetvs.total"
                  :items-per-page="livetvs.per_page"
                  :max-pages-shown="5"
                  :current-page="livetvs.current_page"
                  :on-click="onClickHandler"
                />
              </div>
            </div>
          </div>

          <div
            v-if="index && Object.keys(query).length > 0 && query !== null"
            class="card-body p-0 pb-2"
          >
            <div class="table-responsive">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                      القناة
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xs font-weight-bolder"
                    >
                      اظهار في تطبيق
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xs font-weight-bolder"
                    >
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(p, index) in dataSearch" :key="index">
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <img
                            :src="url + '/storage/livetv/' + p.logo"
                            class="avatar avatar-sm ms-3"
                          />
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">
                            {{ p.name }}
                          </h6>
                        </div>
                      </div>
                    </td>
                    <td class="align-middle text-center">
                      <Switch
                        v-model:checked="p.active"
                        labelclass="justify-content-center"
                        @change="checked(p.id, index, p.active)"
                      />
                    </td>
                    <td class="align-middle text-center">
                      <a
                        class="btn btn-link text-dark px-3 mb-0"
                        @click="editing(p)"
                        href="javascript:;"
                        ><i
                          class="fas fa-pencil-alt text-dark ms-2"
                          aria-hidden="true"
                        ></i
                        >تعديل</a
                      >
                      <a
                        class="btn btn-link text-danger text-gradient px-3 mb-0"
                        @click="destroy(p.id, index)"
                        href="javascript:;"
                        ><i class="far fa-trash-alt ms-2" aria-hidden="true"></i>حذف</a
                      >
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
            <file-pond
              id="image"
              name="image"
              ref="pond"
              label-idle="Drop files here..."
              v-bind:allow-multiple="false"
              v-bind:files="myFiles"
              @processfile="onProcessFile"
              v-on:process="onremovefile"
              accepted-file-types="image/jpeg, image/png"
              :server="server"
            />
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="name" class="form-control-label">اسم</label
                ><input
                  v-model="form.livetv.name"
                  class="form-control"
                  name="name"
                  type="text"
                />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="name" class="form-control-label">ترتيب قناة</label
                ><input
                  v-model="form.livetv.position"
                  class="form-control"
                  name="name"
                  type="text"
                />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="name" class="form-control-label">اختر قسم</label>
                <multiselect
                  v-model="form.livetv.genres"
                  :options="options"
                  placeholder="اختر قسم"
                  mode="tags"
                  :close-on-select="false"
                  :searchable="true"
                  track-by="name"
                  label="name"
                  :object="true"
                  @deselect="destroyCategorie"
                  :hideSelected="true"
                  :create-option="true"
                />
              </div>
            </div>
          </div>
          <hr />
          <h3>Videos</h3>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="link">رابط فيديو</label>
                <input
                  type="text"
                  id="link"
                  v-model="link"
                  class="form-control"
                  placeholder="ادخل رابط فيديو  HLS / M3U8 / MKV / MP4 / WEBM / OGV / FLV / EMBED / IFRAME "
                />
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
            <div class="col-md-1">
              <div class="form-group">
                <label for="hd">HD</label>
                <div class="custom-control custom-switch">
                  <input
                    v-model="hd"
                    type="checkbox"
                    class="custom-control-input"
                    id="hd"
                  />
                  <label class="custom-control-label" for="hd"></label>
                </div>
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
                <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                  سيرفر
                </th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                  الرابط
                </th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder">
                  HD
                </th>
                <th
                  class="text-uppercase text-uppercase text-secondary text-xs font-weight-bolder ps-9"
                >
                  Action
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in form.livetv.videos" :key="index">
                <td>
                  <div class="d-flex px-2 py-1">
                    <p class="text-xs font-weight-bold mb-1">{{ item.server }}</p>
                  </div>
                </td>
                <td class="align-middle text-end">
                  <p class="text-xs font-weight-bold mb-1">{{ item.link }}</p>
                </td>
                <td>
                  <div class="d-flex px-2 py-1">
                    <p class="text-xs font-weight-bold mb-1">
                      {{
                        item.hd == 1 || item.hd == 0
                          ? item.hd == 1
                            ? true
                            : false
                          : item.hd
                      }}
                    </p>
                  </div>
                </td>
                <td class="align-middle text-end">
                  <a
                    class="btn btn-link text-danger text-gradient px-3 mb-0"
                    @click="destroyLink(item, index)"
                    href="javascript:;"
                    ><i class="far fa-trash-alt ms-2" aria-hidden="true"></i>حذف</a
                  >
                </td>
              </tr>
            </tbody>
          </table>
          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label for="name" class="form-control-label">اظهار في تطبيق</label>
                <Switch v-model:checked="form.livetv.active" />
              </div>
            </div>
          </div>
          <div class="col-6 my-auto text-end" v-if="!loading">
            <a
              class="btn bg-gradient-info mb-0"
              v-if="add"
              @click="store()"
              href="javascript:;"
              ><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;اضافة
            </a>
            <a
              class="btn bg-gradient-info mb-0"
              v-if="edit"
              @click="update()"
              href="javascript:;"
              ><i class="ni ni-bold-right btn-sm" aria-hidden="true"></i>&nbsp;&nbsp;حفظ
            </a>

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
import vueFilePond from "vue-filepond";

// Import FilePond styles
import "filepond/dist/filepond.min.css";

// Import FilePond plugins
// Please note that you need to install these plugins separately

// Import image preview plugin styles
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

// Import image preview and file type validation plugins
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";

// Create component
const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview);

import { notifications } from "../mixins/notifications";
import Switch from "../core/Switch.vue";

export default {
  data() {
    return {
      livetvs: [],
      count: 0,
      query: "",
      options: [],
      servers: [],
      selectedServer: "",
      link: "",
      loading: false,
      hd: false,
      dataSearch: [],
      team_type: ["club", "national"],
      form: {
        livetv: {
          videos: [],
        },
        links: [],
        // selectedSeason: [],
      },
      myFiles: [],
      // myFiles: [{
      //     source: "http://localhost/example-app/public/storage/livetv/placeholder.png",
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
      url: "",
      csrf: document.head.querySelector('meta[name="csrf-token"]')
        ? document.head.querySelector('meta[name="csrf-token"]').content
        : "",
    };
  },
  async mounted() {
    this.url = url;
    let response = await axios.get(url + "/admin/get/livetvs/data");
    this.livetvs = response.data;
    response = await axios.get(url + "/admin/categories/data"); //?type=subcategories
    this.options = response.data;
    for (let i = 0; i < this.options.length; i++) {
      this.options[i].value = this.options[i].id;
    }
    response = await axios.get(url + "/admin/servers/data");
    this.servers = response.data;
    for (let i = 0; i < this.servers.length; i++) {
      this.servers[i].value = this.servers[i].id;
    }
    // let responseCount = await axios.get(url + "/admin/get/livetvs/count");
    // this.count = responseCount.data.count;
    console.log(this.csrf);
  },
  computed: {
    paginated() {
      return this.livetvs.data;
    },

    onUrl() {
      var logo = this.form.livetv.logo;
      var livetv;
      if (this.form.livetv.id != null) {
        livetv = "&livetv=" + this.form.livetv.id;
      } else {
        livetv = "";
      }
      return url + "/admin/remove/livetvs/image?logo=" + logo + livetv;
    },
    server() {
      return {
        url: "",
        revert: {
          url: this.onUrl,
          method: "DELETE",
          headers: {
            "X-CSRF-Token": csrf,
          },
          onload: this.onResponse,
        },

        remove: {
          //url: this.onUrl,
          method: "DELETE",
          headers: {
            "X-CSRF-Token": csrf,
          },
          onload: this.onResponse,
        },
        process: {
          url: url + "/admin/store/livetvs/image",
          method: "POST",
          headers: {
            "X-CSRF-Token": document.head.querySelector('meta[name="csrf-token"]')
              ? document.head.querySelector('meta[name="csrf-token"]').content
              : "",
          },
          onload: function (x) {
            // X - empty, why????
            console.log(x);
            return JSON.parse(x);
          },
        },
        //         //url: url + '/admin/store/livetvs/image',
        //         // process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
        //         //     // fieldName is the name of the input field
        //         //     // file is the actual file object to send
        //         //     const formData = new FormData();
        //         //     formData.append(fieldName, file, file.name);

        //         //     const request = new XMLHttpRequest();

        //         //     request.open('POST', url + '/admin/store/livetvs/image');
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
        //             url: url + '/admin/remove/livetvs/image/' + this.logo,
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
            .then((response) => {
              if (response.data.status == 200) {
                console.log(response.data);
                this.showSuccess(response.data.message);
                // the load method accepts either a string (id) or an object
                load();
              }
            })
            .catch((err) => {
              if (err.response.status == 402) {
                this.showError(" لم يتم ايجاد ملف ");
                this.form.livetv.logo == null;
                console.log(err);

                // Can call the error method if something is wrong, should exit after
                error("oh no");
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
        //         //     url: url + '/admin/store/livetvs/image',
        //         //     method: 'POST',
        //         //     headers: {
        //         //         "X-CSRF-Token": this.csrf,
        //         //     },
        //         //     onload: (response) => console.log(response),
        //         // },

        //         process: {
        //             url: url + '/admin/store/livetvs/image',
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
      // this.form.livetv = "";
      this.add = false;
      this.edit = false;
      (this.myFiles = []),
        (this.form = {
          livetv: {
            videos: [],
            genres: [],
          },
          // selectedSeason: [],
        });
      this.logo = null;
      this.index = true;
    },
    log(event) {
      var test = (this.livetvs.current_page - 1) * 6;
      const newIndex = event.moved.newIndex;
      const oldIndex = event.moved.oldIndex;
      this.form.livetv = this.livetvs.data[newIndex];
      this.form.livetv.position = test + (newIndex + 1);
      axios
        .put(url + "/admin/update/livetvs/" + this.form.livetv.id, this.form)
        .then((response) => {
          this.livetvs.data[newIndex].position = test + (newIndex + 1);
          this.form.livetv = {};
          this.showSuccess(response.data.message);
        })
        .catch((error) => {
          this.form.livetv = {};
          console.log(error);
          this.showError(error.response);
        });
      this.form.livetv = this.livetvs.data[oldIndex];
      this.form.livetv.position = test + (oldIndex + 1);
      axios
        .put(url + "/admin/update/livetvs/" + this.form.livetv.id, this.form)
        .then((response) => {
          this.livetvs.data[oldIndex].position = test + (oldIndex + 1);
          this.form.livetv = {};
          this.showSuccess(response.data.message);
        })
        .catch((error) => {
          this.form.livetv = {};
          console.log(error);
          this.showError(error.response);
        });
    },
    // update a record (livetv) in the database
    update() {
      this.loading = true;
      delete this.form.livetv.change_enable;

      axios
        .put(url + "/admin/update/livetvs/" + this.form.livetv.id, this.form)
        .then((response) => {
          this.add = false;
          this.edit = false;
          this.index = true;
          this.form = {
            livetv: {
              videos: [],
              genres: [],
            },
            // selectedSeason: [],
          };
          let dataIndex = this.livetvs.data.findIndex(
            (data) => data.id === this.form.livetv.id
          );
          if (this.query.length > 0) {
            let dataSearchIndex = this.dataSearch.findIndex(
              (data) => data.id === this.form.livetv.id
            );
            this.dataSearch[dataSearchIndex] = response.data.body;
          }
          if (dataIndex >= 0) {
            this.livetvs.data[dataIndex] = response.data.body;
          }
          this.loading = false;
          this.showSuccess(response.data.message);
        })
        .catch((error) => {
          console.log(error);
          this.showError(error.response);
          this.loading = false;
        });
    },
    checked(id, index, value, search) {
      axios
        .get(url + "/admin/update/livetvs/featured/" + id + "?featured=" + value)
        .then((response) => {
          let dataIndex = this.livetvs.data.findIndex((data) => data.id === id);
          if (this.query.length > 0) {
            let dataSearchIndex = this.dataSearch.findIndex((data) => data.id === id);
            this.dataSearch[dataSearchIndex].featured = value;
          }
          if (dataIndex >= 0) {
            this.livetvs.data[dataIndex].featured = value;
            console.log(this.livetvs.data[dataIndex].featured);
          }
          this.showSuccess(response.data.message);
        })
        .catch((error) => {
          if (this.query.length > 0) {
            let dataSearchIndex = this.dataSearch.findIndex((data) => data.id === id);
            this.dataSearch[dataSearchIndex].featured = !value;
          }
          let dataIndex = this.livetvs.data.findIndex((data) => data.id === id);

          if (dataIndex >= 0) {
            this.livetvs.data[dataIndex].featured = !value;
          }
          this.showError();
        });
    },
    // add a new link to the movie
    addLink() {
      console.log(this.link + " " + this.selectedServer.name + " " + this.form.livetv);
      if (this.selectedServer === "" || this.link === "" || this.form.livetv === "")
        return;
      this.form.livetv.videos.unshift({
        server: this.selectedServer.name,
        link: this.link,
        lang: "ar",
        hd: this.hd,
      });
      this.link = "";
      this.selectedServer = "";
      this.video = null;
    },
    // delete a link of the movie
    destroyLink(link, index) {
      this.showConfirm(() => {
        if (link.id) {
          axios
            .delete(url + "/admin/livetv/videos/destroy/" + link.id)
            .then((response) => {
              this.showSuccess(response.data.message);
            })
            .catch((error) => {
              this.showError();
            });
        }
        this.form.livetv.videos.splice(index, 1);
      });
    },
    // delete a genre of the movie
    destroyCategorie(event) {
      console.log(event);
      if (event.pivot !== undefined && event.pivot != null) {
        axios
          .delete(url + "/admin/livetv/categories/destroy/" + event.pivot.id)
          .then((response) => this.showSuccess(response.data.message))
          .catch((error) => this.showError());
      }
    },

    // delete a record (livetv) in the databse
    destroy(id, index) {
      this.showConfirm(async () => {
        try {
          let response = await axios.delete(url + "/admin/remove/livetvs/" + id);

          let dataIndex = this.livetvs.data.findIndex((data) => data.id === id);
          if (this.query.length > 0) {
            console.log(this.dataSearch);
            let dataSearchIndex = this.dataSearch.findIndex((data) => data.id === id);
            this.dataSearch.splice(dataSearchIndex, 1);
          }
          if (dataIndex >= 0) {
            this.livetvs.data.splice(dataIndex, 1);
          }
          //  this.paginate.filteredMovies.list.splice(index, 1);
          this.showSuccess(response.data.message);
        } catch (error) {
          this.showError();
        }
      });
    },
    // change the view to the editing form
    editing(livetv) {
      this.index = false;
      this.add = false;
      this.edit = true;
      livetv.genres.forEach((genre) => {
        genre.value = genre.id;
      });
      console.log(livetv.genres);
      this.form.livetv = livetv;
      if (
        this.form.livetv.logo != null &&
        this.form.livetv.logo != "" &&
        this.form.livetv.logo != "placeholder.png"
      ) {
        this.myFiles = [
          {
            source: url + "/storage/livetv/" + this.form.livetv.logo,
            options: {
              type: "local",
            },
          },
        ];
      }
    },
    store() {
      this.loading = true;
      axios
        .post(url + "/admin/store/livetvs", this.form)
        .then((response) => {
          this.add = false;
          this.edit = false;
          this.index = true;
          this.form = {
            livetv: {
              videos: [],
              genres: [],
            },
            // selectedSeason: [],
          };
          this.logo = null;
          this.livetvs.data.unshift(response.data.body);
          this.loading = false;
          this.showSuccess(response.data.message);
        })
        .catch((error) => {
          console.log(error);
          this.loading = false;
          this.showError(error.response);
        });
    },
    onremovefile(error, file) {
      console.log("dsd");
      console.log(file);
    },
    onProcessFile(error, file) {
      this.form.livetv.logo = file.serverId.path;

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
      let response = await axios.get(url + "/admin/get/livetvs/data?page=" + page);
      this.livetvs = response.data;
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
      return `${name}`;
    },
    async SEARCH() {
      if (this.query.length > 0) {
        let response = await axios.get(url + "/admin/get/livetvs/search?q=" + this.query);
        this.dataSearch = response.data;
        console.log(this.dataSearch);
      }
    },
  },
  components: {
    FilePond,
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
