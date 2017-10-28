<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <div class="alert alert-danger alert-dismissible text-center" role="alert" v-show="showError">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    {{ errors_server.message }}
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>

                    <div class="panel-body">
                        <form class="form-horizontal" @submit.prevent="loginUser">

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email"
                                           v-validate="'required|email|max:255'" data-vv-as="E-Mail Address"
                                           v-model="login.email" autofocus/>
                                    <span class="text-danger"
                                          v-show="errors.has('email')">{{ errors.first('email') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password"
                                           v-model="login.password"
                                           v-validate="'required|alpha_num|min:6'" data-vv-as="Password">
                                    <span class="text-danger" v-show="errors.has('password')">{{ errors.first('password') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { EventBus } from '../event-bus.js';

    export default {
        data(){
            return {
                login: {
                    email: '',
                    password: ''
                },
                errors_server: {
                    message: '',
                    status_code: null
                },
                loginSuccess : false
            }
        },
        methods: {
            loginUser(){
                {
                    this.$validator.validateAll().then((result) => {
                        if (result) {
                            axios.post('/api/login', this.login)
                                    .then(response => {
                                        if (response.data.success == true) {
                                            let token = response.data.token;
                                            if (token) {
                                                localStorage.setItem('token', token);
                                                localStorage.setItem('user', response.data.user);
                                                EventBus.$emit('loginSuccess', response.data.user);
                                            }
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
                                    });
                        }
                    });
                }

            }
        },
        computed: {
            showError(){
                return this.errors_server.message ? true : false;
            }
        }
    }
</script>
