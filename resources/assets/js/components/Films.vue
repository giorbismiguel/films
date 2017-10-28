<template>
    <div class="container">
        <div class="row pull-right">
            <router-link to="/create">
                <a href="" class="btn btn-primary">Create</a>
            </router-link>
        </div>
        <div class="row">

            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-default" v-if="showFilms">
                    <div class="panel-heading">{{ film.name }}</div>

                    <div class="panel-body">
                        {{ film.description }}
                    </div>

                    <div class="panel-footer text-right">
                        <a href="#" v-on:click.prevent="deleteFilm(film.id)" class="btn btn-sm btn-primary"
                           title="Delete">
                            <i class="fa fa-trash-o"></i>
                        </a>
                        <router-link :to="urlSlug"><a class="btn btn-sm btn-primary" title="Details"><i class="fa fa-info"></i></a></router-link>
                    </div>
                </div>

                <h3 v-else="!showFilms" class="text-center">
                    No films to show
                </h3>

                <nav>
                    <ul class="pager">
                        <li class="previous" v-if="pagination.current_page > 1">
                            <a href="#" v-on:click.prevent="getFilms(pagination.current_page - 1)">
                                <span aria-hidden="true">&larr;</span> Older
                            </a>
                        </li>

                        <li class="next" v-if="pagination.current_page < pagination.last_page">
                            <a href="#" v-on:click.prevent="getFilms(pagination.current_page + 1)">
                                Newer <span aria-hidden="true">&rarr;</span>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>

        </div>

        <notifications group="delete-films"
                       :max="3"
                       :width="400"
                       position="top center"/>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                film: {
                    id: null,
                    name: 'Loanding name...',
                    description: 'Loanding description...'
                },
                pagination: {
                    current_page: 0,
                    last_page: 0,
                },
                errors_server: {
                    message: '',
                    status_code: null
                },
                showPanel: false,
                urlSlug : '',
                token: localStorage ? localStorage.getItem('token') : '',
            }
        },
        methods: {
            getFilms(current_page)
            {
                axios.get('/api/films?page=' + current_page)
                        .then(response => {
                            this.pagination.current_page = response.data.current_page;
                            this.pagination.last_page = response.data.last_page;
                            if (response.data.data[0]) {
                                var film = response.data.data[0];
                                this.film = film;
                                this.showPanel = true;
                                this.urlSlug = '/films/' + film.slug;
                            }
                        })
                        .catch(error => {
                            this.errors_server = error.response.data;
                        });
            },
            deleteFilm(id)
            {
                axios.delete('/api/films/' + id)
                        .then(response => {
                            if (response.data.success == true) {
                                this.showPanel = false;
                                this.getFilms(1);
                                this.$notify({
                                    group: 'delete-films',
                                    title: 'Delete a Film',
                                    text: response.data.message,
                                    type: 'success',
                                });
                            } else {
                                this.$notify({
                                    group: 'delete-films',
                                    title: 'Delete a Film',
                                    text: response.data.message,
                                    type: 'success',
                                });
                            }
                        })
                        .catch(error => {
                            this.errors_server = error.response.data;
                        });
            }
        },
        computed: {
            showFilms(){
                return this.showPanel ? true : false
            },
            setUrlSlug(){
                return this.urlSlug;
            }
        },
        mounted() {
            this.getFilms(1);
        }
    }
</script>