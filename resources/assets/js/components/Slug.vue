<template>
    <div class="container" v-show="showWrapperSlug">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <h4 v-show="comments">Film Details</h4>
                <div class="card" style="border: 1px solid #ccc;">
                    <div class="card-block text-center">
                        <img v-bind:src="film.photo" v-bind:alt="film.name" class="card-img img-rounded">

                        <div class="card-block">
                            <h4 class="card-title">{{ film.name }}</h4>
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Description:</strong> {{ film.description }}</li>
                            <li class="list-group-item"><strong>Rating:</strong> {{ film.rating }}</li>
                            <li class="list-group-item"><strong>Ticket Price:</strong> {{ film.ticket_price }}</li>
                            <li class="list-group-item"><strong>Release At:</strong> {{ film.releaseAt }}</li>
                            <li class="list-group-item"><strong>Country:</strong> {{ film.country }}</li>
                            <li class="list-group-item"><strong>Gender(s):</strong> {{ film.gender }}</li>
                        </ul>
                    </div>
                </div>

                <div v-show="isLoged">
                    <h4 v-show="comments">Add a Comment</h4>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form id="idFormSendComment" class="form-horizontal" @submit.prevent="addComment"
                                  method="post"
                                  v-bind:action="routeComment">
                                <div class="form-group">
                                    <label for="name" class="col-md-2 control-label">Name</label>
                                    <div class="col-md-10">
                                        <input id="name" type="text" class="form-control" name="name"
                                               v-validate="'required|max:255'" data-vv-as="Name"
                                               v-model="comment.name"/>
                                        <span class="text-danger"
                                              v-show="errors.has('name')">{{ errors.first('name') }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description" class="col-md-2 control-label">Comment</label>
                                    <div class="col-md-10">
                                    <textarea id="description" name="description" v-validate="'required'"
                                              class="form-control"
                                              rows="3" data-vv-as="Comment"
                                              v-model="comment.description">
                                    </textarea>

                                        <span class="text-danger"
                                              v-show="errors.has('description')">{{ errors.first('description') }}</span>
                                    </div>
                                </div>
                                <input type="hidden" name="film_id" v-bind:value="film.id"/>
                                <input type="hidden" name="_token" v-bind:value="token">
                                <input type="hidden" name="slug" v-bind:value="slug">

                                <div class="form-group text-right">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            Comment
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <h4 v-show="comments">Comments</h4>
                <div class="jumbotron" v-for="comment in comments">
                    <strong>Name:</strong>
                    <h4>{{ comment.name }}</h4>

                    <strong>Comment:</strong>
                    <p>{{ comment.comment }}</p>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                film: {
                    id: null,
                    photo: '',
                    name: '',
                    description: '',
                    rating: 0,
                    ticket_price: '',
                    releaseAt: '',
                    country: '',
                    gender: '',
                },
                comment: {
                    name: '',
                    description: ''
                },
                showWrapperSlug: false,
                routeComment: route('comment.store'),
                token: document.head.querySelector('meta[name="csrf-token"]').content,
                slug: '',
                comments: [],
                isLoged: localStorage.getItem('token') ? true : false
            }
        },
        methods: {
            getFilms(slug)
            {
                axios.get('/api/films/' + slug, {slug: slug})
                        .then(response => {
                            this.film.id = response.data.film.id;
                            this.film.name = response.data.film.name;
                            this.film.description = response.data.film.description;
                            this.film.rating = response.data.film.rating;
                            this.film.releaseAt = response.data.film.release_at;
                            this.film.ticket_price = response.data.film.ticket_price;
                            this.film.photo = '/storage/' + response.data.film.photo;

                            this.film.country = response.data.countryName;
                            this.film.gender = response.data.strGenders;
                            this.comments = response.data.comments;

                            this.showWrapperSlug = true;
                        })
                        .catch(error => {
                            this.errors_server = error.response.data;
                        });
            },
            addComment(){
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        $("#idFormSendComment").submit();
                    }
                });
            }
        },
        mounted() {
            this.slug = this.$route.params.slug;
            this.getFilms(this.$route.params.slug);
        }
    }
</script>
