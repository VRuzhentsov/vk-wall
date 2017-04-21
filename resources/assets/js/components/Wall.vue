<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <actions></actions>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" v-for="comment in comments">
                <commentContainer :comment="comment"></commentContainer>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                comments: []
            };
        },
        created() {
            this.fetchComments();
        },
        mounted: function () {
            Echo.channel('boss-river-135')
                .listen('CommentPosted', (data) => {
                    console.log(data);
                    // Push ata to posts list.
                    this.comments.push({
                        message: data.chatMessage.message,
                        username: data.user.name
                    });
                });
        },
        methods: {
            fetchComments() {
                let that = this;
                this.$http.get('/api/comments')
                    .then(function (response) {
                        that.comments = response.data.data;
                    });
            },
        }
    }
</script>