<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" v-on:submit.prevent="register">
                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" v-model="user.name"
                                           required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email"
                                           v-model="user.email" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password"
                                           v-model="user.password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" v-model="user.password_confirmation" required>
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
    </div>
</template>


<script>
    export default {
        data: function () {
            return {
                user: {
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null
                },
                messages: [],
                loggingIn: false
            }
        },
        methods: {
            register: function (event) {
                let that = this;

                let data = {
                    name: this.user.name,
                    email: this.user.email,
                    password: this.user.password,
                    password_confirmation: this.user.password_confirmation
                };

                this.$http.post('/api/register', data)
                    .then(response => {
                            this.$auth.setToken(response.data.access_token, response.data.expires_in + Date.now());
                            that.$store.dispatch('userHasLoggedIn', {token: response.data.access_token});
                            that.$store.dispatch('userUpdating', {user: response.data.data});

                            that.$router.push('wall');

                            that.getUser();
                        }
                    )
            },
            getUser: function () {
                let that = this;
                this.$http.get('/api/user').then(
                    function (response) {
                        that.$store.dispatch('userUpdating', {user: response.data.data})
                    },
                    function (response) {
                        that.$store.dispatch('userHasLoggedOut');
                        that.$auth.destroyToken();
                    }
                )
            }
        }
    }
</script>