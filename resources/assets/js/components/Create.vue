<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-danger alert-dismissible" role="alert" v-show="showError">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    The server return a error: {{ errors_server.message }} Status Code: {{
                    errors_server.status_code
                    }}
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">Create Film</div>

                    <div class="panel-body">

                        <form class="form-horizontal" @submit.prevent="addFilm">

                            <div class="form-group">
                                <label for="name" class="control-label col-md-2">Name</label>
                                <div class="col-md-10">
                                    <input id="name" name="name" type="text" class="form-control"
                                           v-validate="'required|max:255'"
                                           v-model="film.name" data-vv-as="Name"/>
                                    <span class="text-danger"
                                          v-show="errors.has('name')">{{ errors.first('name') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description" class="control-label col-md-2">Description</label>
                                <div class="col-md-10">
                                <textarea id="description" name="description" v-validate="'required'"
                                          class="form-control"
                                          rows="3"
                                          v-model="film.description" data-vv-as="Description"></textarea>

                                    <span class="text-danger"
                                          v-show="errors.has('description')">{{ errors.first('description') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description" class="control-label col-md-2">Rating</label>
                                <div class="col-md-10">
                                    <star-rating name="rating" @rating-selected="film.rating = $event"
                                                 :rating="film.rating"
                                                 v-bind:star-size="30"></star-rating>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ticket_price" class="control-label col-md-2">Ticket Price</label>
                                <div class="col-md-10">
                                    <input name="ticket_price" id="ticket_price" type="text" class="form-control"
                                           v-validate="'required|numeric'"
                                           style="width:30%;"
                                           v-model="film.ticket_price" data-vv-as="Ticket Price"/>
                                    <span class="text-danger"
                                          v-show="errors.has('ticket_price')">{{ errors.first('ticket_price') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2">Release Date</label>
                                <div class="col-md-10">
                                    <date-picker id="release_at" name="release_at" v-model="film.release_at"
                                                 v-validate="'required'" :config="config" style="width:30%;"
                                                 data-vv-as="Release Date"></date-picker>

                                    <span class="text-danger"
                                          v-show="errors.has('release_at')">{{ errors.first('release_at') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2">Country</label>
                                <div class="col-md-10">
                                    <v-select name="country" v-validate="'required'" :options="countries"
                                              placeholder="Search Country..."
                                              label="name" style="width:40%;" v-model="film.country"
                                              data-vv-as="Country">
                                    </v-select>
                                    <span class="text-danger"
                                          v-show="errors.has('country')">{{ errors.first('country') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2">Genre</label>
                                <div class="col-md-10">
                                    <v-select name="genre" multiple v-validate="'required'" :options="genres"
                                              placeholder="Search Genres..."
                                              label="name" style="width:40%;" v-model="film.genre" data-vv-as="Genre">
                                    </v-select>
                                    <span class="text-danger"
                                          v-show="errors.has('genre')">{{ errors.first('genre') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <label class="control-label col-md-2">Photo</label>

                                    <div class="col-md-6">
                                        <input id="photo" type="file" name="photo" v-on:change="onFileChange"
                                               v-validate="'required|image'"
                                               class="form-control" data-vv-as="Photo">
                                    </div>

                                    <!--div class="col-md-2">
                                        <button class="btn btn-success btn-block" @click="upload">Upload</button>
                                    </div-->

                                    <div class="col-md-2 hide">
                                        <img :src="film.image" class="img-responsive">
                                    </div>

                                    <div class="clearfix"></div>

                                    <div class="col-md-12">
                                <span class="text-danger"
                                      v-show="errors.has('photo')">{{ errors.first('photo') }}</span>
                                    </div>

                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10 text-right">
                                    <button id="submit" class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <br/>
            <br/>
        </div>
    </div>
</template>

<script>
    import datePicker from 'vue-bootstrap-datetimepicker';
    import starRating from 'vue-star-rating';
    import vSelect from "vue-select"

    export default {
        data(){
            return {
                film: {
                    name: '',
                    description: '',
                    rating: 3,
                    ticket_price: '',
                    release_at: '',
                    country: '',
                    genre: '',
                    image: '',
                },
                config: {
                    format: 'DD/MM/YYYY h/m/s',
                    useCurrent: false,
                },
                countries: [],
                genres: [],
                image: '',
                errors_server: {
                    message: '',
                    status_code: null
                }
            }
        },
        methods: {
            addFilm()
            {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        var i, arrIdGenres = [];
                        axios.defaults.headers.common["X-CSRF-TOKEN"] = document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content");

                        let formData = new FormData();
                        formData.append('name', this.film.name);
                        formData.append('description', this.film.description);
                        formData.append('rating', this.film.rating);
                        formData.append('ticket_price', this.film.ticket_price);
                        formData.append('release_at', this.film.release_at);

                        formData.append('country', this.film.country.id);

                        for (i = 0; i < this.film.genre.length; i++) {
                            arrIdGenres.push(this.film.genre[i].id);
                        }
                        formData.append('genre', arrIdGenres);
                        formData.append('photo', document.getElementById('photo').files[0]);

                        axios.post("/api/films", formData)
                                .then(response => {
                                    if (response.data.success == true) {
                                        this.film = '';
                                        this.$router.push('/films');
                                    } else {
                                        this.errors_server.message = error.response.message;
                                        this.errors_server.status_code = error.response.status_code;
                                        setTimeout(function () {
                                            this.errors_server.message = '';
                                            this.errors_server.status_code = '';
                                        }, 10000)
                                    }
                                })
                                .catch(error => {
                                    this.errors_server = error.response.data;
                                })
                    }
                });

            },
            getCountries() {
                axios.get("/api/countries")
                        .then(response=> {
                            this.countries = response.data;
                        })
                        .catch(error=> {
                            if (error.data) {
                                this.errors_server = error.data;
                            }
                        });
            },
            getGenres() {
                axios.get("/api/genres")
                        .then(response=> {
                            this.genres = response.data;
                        })
                        .catch(error=> {
                            if (error.data) {
                                this.errors_server = error.data;
                            }
                        });
            },
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.film.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            upload(){
                axios.post('/api/upload', {image: this.film.image}).then(response => {

                });
            }
        },
        computed: {
            showError(){
                return this.errors_server.message ? true : false;
            }
        },
        mounted(){
            this.getCountries();
            this.getGenres();
        },
        components: {
            datePicker,
            starRating,
            vSelect,
        }
    }
</script>