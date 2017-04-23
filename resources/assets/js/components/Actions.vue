<template>
    <div class="row">
        <div class="col-md-12">
            <p>Actions block</p>
            <form class="form-group" @keyup.enter="comment" v-on:submit.prevent="comment">
                <textarea class="form-control" title="Comment" v-model="message"/>
                <input type="submit" class="btn btn-default">
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                message: ''
            }
        },
        methods: {
            comment: function (event) {
                let that = this;
                let data = {
                    content: this.message
                };
                let userId = undefined;
                if (that.$route.params.userId) {
                    userId = that.$route.params.userId;
                } else {
                    userId = that.$store.state.user.id
                }
                this.$http.post('api/users/' + userId + '/comments', data)
                    .then(function (response) {
                        that.message = '';
                    })
            }
        }
    }
</script>