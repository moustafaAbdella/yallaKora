<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-sm-5">
                <h4 class="card-title mb-0">Genres</h4>
              </div>
            </div>
            <hr />

            <!-- Genre create -->
            <div class="row">
              <div class="col-12">
                <div class="input-group">
                  <input
                    v-model="genre"
                    type="text"
                    class="form-control input"
                    placeholder="Genre name"
                  />
                  <div v-if="!edit" class="input-group-btn">
                    <button @click="store()" :disabled="!genre" class="btn btn-primary ml-2">
                      <i class="material-icons md-16">add</i>
                    </button>
                  </div>
                  <div v-if="!edit" class="input-group-btn">
                    <button @click="fetch()" class="btn btn-success ml-2">
                      <i class="material-icons md-16">autorenew</i>Fetch from TMDB
                    </button>
                  </div>
                  <div v-if="edit" class="input-group-btn">
                    <button @click="updateSubmit()" :disabled="!genre" class="btn btn-primary ml-2">
                      <i class="material-icons md-16">update</i>
                    </button>
                  </div>
                  <div v-if="edit" class="input-group-btn">
                    <button @click="cancel()" class="btn btn-warning ml-2">
                      <i class="material-icons md-16">close</i>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <br />

            <!-- Genres Index-->

            <table class="table table-hover">
              <thead>
                <tr class="row">
                  <th class="col-md-2">Actions</th>
                  <th class="col-md-10">Name</th>
                </tr>
              </thead>
              <paginate
                name="genres"
                v-if="genres && genres.length"
                :list="genres"
                :per="10"
                tag="tbody"
              >
                <tr v-for="(genre, index) in paginated('genres')" :key="index" class="row">
                  <td class="col-md-2">
                    <button class="btn btn-sm btn-danger" @click="destroy(genre.id, index)">
                      <i class="material-icons md-16">delete</i>
                    </button>
                    <button class="btn btn-sm btn-warning">
                      <i class="material-icons md-16" @click="update(genre, index)">edit</i>
                    </button>
                  </td>
                  <td class="col-md-10">{{genre.name}}</td>
                </tr>
              </paginate>
              <paginate-links
                for="genres"
                :async="true"
                :hide-single-page="true"
                :limit="5"
                :show-step-links="true"
                :classes="{
                  'ul': 'pagination', 
                  'li': 'page-item', 
                  'a': 'page-link',
                  '.next > a': 'next-link',
                  '.prev > a': 'prev-link'}"
                class="float-right"
              ></paginate-links>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { notifications } from "../mixins/notifications";
import { settings } from "../mixins/settings";

export default {
  data() {
    return {
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
    let response = await axios.get(url + "/admin/genres/data");
    this.genres = response.data;
  },
  methods: {
    // create a genre in database
    async store() {
      try {
        let response = await axios.post(url + "/admin/genres/store", {
          name: this.genre
        });
        this.genres.unshift(response.data.body);
        this.genre = "";
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
            url + "/admin/genres/destroy/" + id
          );
          let genreIndex = this.genres.findIndex(genre => genre.id === id);
          this.genres.splice(genreIndex, 1);
          this.paginate.genres.list.splice(index, 1);
          this.showSuccess(response.data.message);
        } catch (error) {
          this.showError();
        }
      });
    },
    // get all themoviedb genres and store them in the database
    async fetch() {
      try {
        let response = await http.get(
          "https://api.themoviedb.org/3/genre/tv/list?api_key=" +
            this.settings.tmdb_api_key +
            "&language=" +
            this.settings.tmdb_lang.iso_639_1
        );
        this.tmdb.series = response.data;

        response = await http.get(
          "https://api.themoviedb.org/3/genre/movie/list?api_key=" +
            this.settings.tmdb_api_key +
            "&language=" +
            this.settings.tmdb_lang.iso_639_1
        );
        this.tmdb.movies = response.data;

        response = await axios.post(url + "/admin/genres/fetch", this.tmdb);

        this.genres = response.data.body;
        this.showSuccess(response.data.message);
      } catch (error) {
        this.showError();
      }
    },
    update(genre, index) {
      this.edit = true;
      this.genre = genre.name;
      this.editing = genre;
      this.editing.index = index;
    },
    cancel() {
      this.edit = false;
      this.genre = "";
      this.editing = {};
    },
    // update a genre from database
    async updateSubmit() {
      try {
        let response = await axios.put(
          url + "/admin/genres/update/" + this.editing.id,
          { name: this.genre, id: this.editing.id }
        );
        this.genres[this.editing.index] = response.data.body;
        this.paginate.genres.list[this.editing.index] = response.data.body;
        this.edit = false;
        this.genre = "";
        this.editing = {};
        this.showSuccess(response.data.message);
      } catch (error) {
        this.showError(error.response);
      }
    }
  },
  mixins: [notifications, settings]
};
</script>

<style scoped>
.table {
  min-height: 600px;
}
</style>