<template>
    <div>
        <div class="container">
            <div class="row ">
                <ul class="col-md-9 list-unstyled comments-block">
                    <actions></actions>
                    <commentContainer v-for="comment in comments" :comment="comment"></commentContainer>
                </ul>
                <div class="col-md-3">
                    <sidebar></sidebar>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                showSidebar: false,
                comments: []
            };
        },
        created() {
            this.fetchComments();
        },
        mounted: function () {
            Echo.channel('boss-river-135')
                .listen('CommentPosted', (data) => {
                    console.log('CommentPosted');
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
                let userId = undefined;
                if (that.$route.params.userId !== undefined) {
                    userId = that.$route.params.userId;
                } else {
                    userId = that.$store.state.user.id
                }
                this.$http.get('/api/users/' + userId + '/comments')
                    .then(function (response) {
                        that.comments = response.data.data;
                    });
            },
            toggleSidebar: function () {
                // Privet kostil. Have no idea, why "vue-strap" so trash ...
                this.showSidebar = !this.showSidebar;
            }
        }
    }
</script>