<template>
  <div class="row">
    <div class="col-lg-3 col-sm-6 mb-lg-0 mb-4">
      <div class="card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">عدد سيرفرات</p>
                <h5 class="font-weight-bolder mb-0">{{ servers.total }}</h5>
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
  <div class="my-3">
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
          <div class="row mb-1">
            <div class="col-6">
              <h5>سيرفرات</h5>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3">
              <div class="form-group">
                <input
                  v-model="server"
                  placeholder="اسم سيرفر"
                  class="form-control"
                  name="name"
                  type="text"
                />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <input
                  v-model="domain"
                  placeholder="domain"
                  class="form-control"
                  name="name"
                  type="text"
                />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <input
                  v-model="useragent"
                  placeholder="useragent"
                  class="form-control"
                  name="name"
                  type="text"
                />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <!-- <label for="server">اختر سيرفر فيديو</label> -->
                <multiselect
                  :options="server_typs"
                  v-model="type"
                  placeholder="Select one"
                  :searchable="true"
                  :object="false"
                ></multiselect>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <!-- <label for="server">اختر سيرفر فيديو</label> -->
                <multiselect
                  :options="players"
                  v-model="player"
                  placeholder="Select one"
                  :searchable="true"
                  :object="false"
                ></multiselect>
              </div>
            </div>
            <div class="col-6">
              <a
                class="btn bg-gradient-info mb-0"
                v-if="!edit"
                @click="store()"
                href="javascript:;"
                ><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;اضافة سيرفر
              </a>
              <a
                class="btn bg-gradient-info mb-0"
                v-if="edit"
                @click="updateSubmit()"
                href="javascript:;"
              >
                حفظ
              </a>
              <a
                class="btn bg-gradient-danger mb-0 me-2"
                v-if="edit"
                @click="cancel()"
                href="javascript:;"
              >
                الغاء
              </a>
            </div>
          </div>
        </div>
        <div class="card-body pt-0">
          <div
            v-if="(servers !== null && query === null) || Object.keys(query).length === 0"
            class="card-body p-0 pb-2"
          >
            <div class="table-responsive">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th
                      class="text-center text-uppercase text-secondary text-xs font-weight-bolder"
                    >
                      سيرفر
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xs font-weight-bolder"
                    >
                      النطاق
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xs font-weight-bolder"
                    >
                      UserAgent
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xs font-weight-bolder"
                    >
                      type
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xs font-weight-bolder"
                    >
                      Action
                    </th>
                    <!-- <th class="col-sm-2 text-uppercase text-secondary text-xs font-weight-bolder ">
                                        Play in Player
                                    </th> -->
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(p, index) in paginated" :key="index" class="py-0 my-0">
                    <td class="align-middle text-center">
                      <div>
                        <h6 class="mb-0 text-sm">
                          {{ p.name }}
                        </h6>
                      </div>
                    </td>
                    <td class="align-middle text-center">
                      <div>
                        <h6 class="mb-0 text-sm">
                          {{ p.domain }}

                        </h6>
                      </div>
                    </td>
                    <td class="align-middle text-center">
                      <div>
                        <h6 class="mb-0 text-sm">
                          {{ p.useragent }}
                        </h6>
                      </div>
                    </td>
                    <td class="align-middle text-center">
                      {{p.type}} {{p.type != 'embed' ? ('- ' + p.player) :""}}
                      <!-- <Switch
                        v-model:checked="p.embed"
                        labelclass="justify-content-center"
                        @change="checked(p.id, index, p.embed)"
                      /> -->
                    </td>
                    <td class="align-middle text-center">
                      <a
                        class="btn btn-link text-dark px-3 mb-0"
                        @click="update(p, index)"
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
              <div class="paginate-div" v-if="Object.keys(servers).length > 0">
                <vue-awesome-paginate
                  :total-items="servers.total"
                  :items-per-page="servers.per_page"
                  :max-pages-shown="5"
                  :current-page="servers.current_page"
                  :on-click="onClickHandler"
                />
              </div>
            </div>
          </div>
          <div
            v-if="Object.keys(query).length > 0 && query !== null"
            class="card-body p-0 pb-2"
          >
            <div class="table-responsive">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th
                      class="text-center text-uppercase text-secondary text-xs font-weight-bolder"
                    >
                      سيرفر
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xs font-weight-bolder"
                    >
                      النطاق
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xs font-weight-bolder"
                    >
                      UserAgent
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xs font-weight-bolder"
                    >
                      type
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xs font-weight-bolder"
                    >
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(p, index) in dataSearch" :key="index" class="py-0 my-0">
                    <td class="align-middle text-center">
                      <div>
                        <h6 class="mb-0 text-sm">
                          {{ p.name }}
                        </h6>
                      </div>
                    </td>
                    <td class="align-middle text-center">
                      <div>
                        <h6 class="mb-0 text-sm">
                          {{ p.domain }}

                        </h6>
                      </div>
                    </td>
                    <td class="align-middle text-center">
                      <div>
                        <h6 class="mb-0 text-sm">
                          {{ p.useragent }}
                        </h6>
                      </div>
                    </td>
                    <td class="align-middle text-center">
                      {{ p.type }}
                      <!-- <Switch
                        v-model:checked="p.embed"
                        labelclass="justify-content-center"
                        @change="checked(p.id, index, p.embed)"
                      /> -->
                    </td>
                    <td>
                      <a
                        class="btn btn-link text-dark px-3 mb-0"
                        @click="update(p, index)"
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
      </div>
    </div>
  </div>
</template>

<script>
import { notifications } from "../mixins/notifications";
import { settings } from "../mixins/settings";
import Switch from "../core/Switch.vue";

export default {
  data() {
    return {
      count: 0,
      servers: [],
      query: "",
      dataSearch: [],
      domain: "",
      useragent: "",
      server: "",
      server_typs: [
        'player',
        'embed',
        // 'p2p',
        // 'supportHost',
        // 'recaptcha'
      ],
      players: [
        'mainPlayer',
        // 'vlcPlayer',
        'externalPlayerVlc',
        'externalPlayer',
      ],
      player:'',
      type: '',
      tmdb: {
        movies: [],
        series: [],
      }, paginate: ["servers"],
      edit: false,
      editing: {},
    };
  },
  async mounted() {
    let response = await axios.get(url + "/admin/get/servers/data");
    this.servers = response.data;
  },
  computed: {
    paginated() {
      return this.servers.data;
    },
  },
  methods: {
    // create a server in database
    async store() {
      try {
        let response = await axios.post(url + "/admin/servers/store", {
          name: this.server,
          domain: this.domain,
          useragent: this.useragent,
          type:this.type,
          player: this.player,
        });
        this.servers.data.unshift(response.data.body);
        this.server = "";
        this.domain = "";
        this.useragent = "";
        this.type = "";
        this.player = "";

        this.showSuccess(response.data.message);
      } catch (error) {
        this.showError(error.response);
      }
    },

    checked(id, index, value, search) {
      axios
        .get(url + "/admin/update/servers/" + id + "?embed=" + value)
        .then((response) => {
          let dataIndex = this.servers.data.findIndex((data) => data.id === id);
          if (this.query.length > 0) {
            let dataSearchIndex = this.dataSearch.findIndex((data) => data.id === id);
            this.dataSearch[dataSearchIndex].embed = value;
          }
          if (dataIndex >= 0) {
            this.servers.data[dataIndex].embed = value;
            console.log(this.servers.data[dataIndex].embed);
          }
          this.showSuccess(response.data.message);
        })
        .catch((error) => {
          if (this.query.length > 0) {
            let dataSearchIndex = this.dataSearch.findIndex((data) => data.id === id);
            this.dataSearch[dataSearchIndex].embed = !value;
          }
          let dataIndex = this.servers.data.findIndex((data) => data.id === id);

          if (dataIndex >= 0) {
            this.servers.data[dataIndex].embed = !value;
          }
          this.showError();
        });
    },

    // delete a server from database
    destroy(id, index) {
      this.showConfirm(async () => {
        try {
          let response = await axios.delete(url + "/admin/servers/destroy/" + id);
          let dataIndex = this.servers.data.findIndex((data) => data.id === id);
          if (this.query.length > 0) {
            console.log(this.dataSearch);
            let dataSearchIndex = this.dataSearch.findIndex((data) => data.id === id);
            this.dataSearch.splice(dataSearchIndex, 1);
          }
          if (dataIndex >= 0) {
            this.servers.data.splice(dataIndex, 1);
          }
          this.showSuccess(response.data.message);
        } catch (error) {
          this.showError();
        }
      });
    },
    // get all themoviedb servers and store them in the database

    update(server, index) {
      this.edit = true;
      this.server = server.name;
      this.domain = server.domain;
      this.useragent = server.useragent;
      this.type = server.type;
      this.player =  server.player;
      this.editing = server;
      this.editing.index = index;
    },
    cancel() {
      this.edit = false;
      this.server = "";
      this.editing = {};
    },
    // update a server from database
    async updateSubmit() {
      try {
        let response = await axios.put(url + "/admin/servers/update/" + this.editing.id, {
          name: this.server,
          domain: this.domain,
          useragent: this.useragent,
          type:this.type,
          player: this.player,
          id: this.editing.id,
        });
        let dataIndex = this.servers.data.findIndex(
          (data) => data.id === this.editing.id
        );
        if (this.query.length > 0) {
          let dataSearchIndex = this.dataSearch.findIndex(
            (data) => data.id === this.editing.id
          );
          this.dataSearch[dataSearchIndex] = response.data.body;
        }
        if (dataIndex >= 0) {
          this.servers.data[dataIndex] = response.data.body;
        }
        this.edit = false;
        this.server = "";
        this.type = '';
        this.player = '';
        this.editing = {};
        this.showSuccess(response.data.message);
      } catch (error) {
        this.showError(error.response);
      }
    },
    async SEARCH() {
      if (this.query.length > 0) {
        let response = await axios.get(url + "/admin/get/servers/search?q=" + this.query);
        this.dataSearch = response.data;
        console.log(this.dataSearch);
      }
    },
    async onClickHandler(page) {
      let response = await axios.get(url + "/admin/get/servers/data?page=" + page);
      this.servers = response.data;
    },
  },
  components: {
    Switch,
  },
  mixins: [notifications, settings],
};
</script>

<style scoped></style>
