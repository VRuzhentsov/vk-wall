<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" v-on:submit.prevent="login">
                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" v-model="user.email" required
                                           autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-model="user.password"
                                           required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> Remember Me
                                        </label>
                                    </div>
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
    export default {
        data: function () {
            return {
                user: {
                    email: null,
                    password: null
                },
                messages: [],
                loggingIn: false
            }
        },
        methods: {
            login: function (event) {
                let that = this;

                let data = {
                    client_id: 2,
                    client_secret: 'KFhGbclXHPDcMyglktER2FJYVxVY40HZWwT0AM4r',
                    grant_type: 'password',
                    username: this.user.email,
                    password: this.user.password
                };

                this.$http.post('/oauth/token', data)
                    .then(response => {
                            this.$auth.setToken(response.data.access_token, response.data.expires_in + Date.now());
                            that.$store.dispatch('userHasLoggedIn', response.data.access_token);
                            that.$router.push('wall');
                        }
                    )
            },
            getUserData: function () {
                let that = this;
                this.$http.get('/api/user').then(
                    function (response) {
                        that.$dispatch('userHasLoggedIn', response.user);
                    },
                    function (response) {

                    }
                )
            }
        },
        route: {
            activate: function (transition) {
                this.$dispatch('userHasLoggedOut');
                transition.next()
            }
        }
    }
</script>