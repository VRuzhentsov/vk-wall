<template>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <router-link class="navbar-brand" to="/">Home</router-link>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <li v-if="! $store.state.authenticated">
                        <router-link :to="{ path: '/login' }">
                            <span><i class="fa fa-btn fa-fw fa-sign-in"></i>&nbsp;Sign in</span>
                        </router-link>
                    </li>
                    <li v-if="! $store.state.authenticated">
                        <router-link :to="{ path: '/register' }">
                            <span><i class="fa fa-btn fa-fw fa-chevron-circle-up"></i>&nbsp;Register</span>
                        </router-link>
                    </li>
                    <li v-if="$store.state.authenticated">
                        <router-link :to="{ path: '/wall' }">
                            <span><i class="fa fa-btn fa-fw fa-user"></i>Wall</span>
                        </router-link>
                    </li>
                    <li v-if="$store.state.authenticated">
                        <a href="#/" v-on:click="logout">
                            <span><i class="fa fa-btn fa-fw fa-sign-out"></i>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        data: function () {
            return {
            }
        },
        mounted: function () {
        },
        methods: {
            logout: function () {
                this.$http.post('/api/logout');
                this.$store.dispatch('userHasLoggedOut');
            },
        }
    }
</script>