<template>
    <div class="container">
        <actions></actions>
        <div class="row ">
            <ul class="col-md-9 list-unstyled comments-block">
                <commentContainer v-for="comment in comments" :comment="comment"></commentContainer>
            </ul>
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