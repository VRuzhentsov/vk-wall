<template>
    <div>
        <div v-for="user in users">
            <router-link :to="{ name: 'wall', params: { userId: user.id } }">
                <div class="author-name">{{ user.name }}</div>
            </router-link>
            <div class="comment-updated_at">Registered {{ user.created_at | moment }}</div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                users: []
            }
        },
        mounted: function () {
            let that = this;
            this.$http.get('/api/users')
                .then(function (response) {
                    that.users = response.data.data;
                });
        },
        methods: {
            moment: function () {
                return moment();
            }
        },
        filters: {
            moment: function (date) {
                return moment(date).fromNow();
            }
        }
    }
</script>
