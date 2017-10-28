<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>

                    <div class="panel-body">
                        <form class="form-horizontal" @submit.prevent="registerUser">
                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name"
                                           v-model="register.name" autofocus
                                           v-validate="'required|max:255'" data-vv-as="Name" />
                                    <span class="text-danger"
                                          v-show="errors.has('name')">{{ errors.first('name') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email"
                                           v-model="register.email"
                                           v-validate="'required|email|max:255'" data-vv-as="E-Mail Address"/>
                                    <span class="text-danger"
                                          v-show="errors.has('email')">{{ errors.first('email') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password"
                                           v-model="register.password"
                                           v-validate="'required|alpha_num|min:6'" data-vv-as="Password"/>
                                    <span class="text-danger"
                                          v-show="errors.has('password')">{{ errors.first('password') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" v-model="register.password_confirmation"
                                           v-validate="'required|confirmed:password|alpha_num|min:6'" data-vv-as="Confirm Password"/>
                                    <span class="text-danger"
                                          v-show="errors.has('password_confirmation')">{{ errors.first('password_confirmation') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <notifications group="register-users"
                       :max="3"
                       :width="400"
                       position="top center"/>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                register: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                errors_server: {
                    message: '',
                    status_code: null
                }
            }
        },
        methods: {
            registerUser(){
                {
                    this.$validator.validateAll().then((result) => {
                        if (result) {
                            axios.post('/api/register', this.register)
                                    .then(response => {
                                        if (response.data.success == true) {
                                            let that = this;
                                            this.$notify({
                                                group: 'register-users',
                                                title: 'User Register',
                                                text: response.data.message,
                                                type: 'success'
                                            });
                                            setTimeout(function(){
                                                that.register = '';
                                                that.$router.push('/');
                                            }, 10000)

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
        mounted() {
            console.log('Component Register mounted.')
        }
    }
</script>


