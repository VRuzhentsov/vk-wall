<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" v-on:submit.prevent="login">
                            <!--{{ csrf_field() }}-->

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" v-model="user.email" required
                                           autofocus>

                                    <!--@if ($errors->has('email'))-->
                                    <span class="help-block">
                                        <!--<strong>{{ $errors->first('email') }}</strong>-->
                                    </span>
                                    <!--@endif-->
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-model="user.password"
                                           required>
                                    <!--@if ($errors->has('password'))-->
                                    <!--<span class="help-block">-->
                                    <!--<strong>{{ $errors->first('password') }}</strong>-->
                                    <!--</span>-->
                                    <!--@endif-->
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

                                    <a class="btn btn-link" v-on:click.prevent="getUserData" href="">
                                        Forgot Your Password?
                                    </a>
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
                that.loggingIn = true;
                HTTP.post('/login', this.user).then(
                    function (response) {
                        that.$store.dispatch('userHasLoggedIn', {user: response.data.user});
                        that.$router.push('wall');
                    },
                    function (response) {
                        that.messages = [];
                        if (response.status && response.status.code === 401) that.messages.push({
                            type: 'danger',
                            message: 'Sorry, you provided invalid credentials'
                        });
                        that.loggingIn = false
                    }
                )
            },
            getUserData: function () {
                let that = this;
                HTTP.get('/user').then(
                    function (response) {
                        console.log(that);
                        that.$dispatch('userHasLoggedIn', response.user);
                        that.$route.router.go('/auth/profile')
                    },
                    function (response) {
                        console.log(response)
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