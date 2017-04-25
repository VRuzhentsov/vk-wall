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
                    if (data.comment.parent_id) {
                        let parent = this.pickDeep(this.comments, data.comment.parent_id);
                        parent.children.push(data.comment);
                    } else {
                        this.comments.unshift(data.comment);
                    }
                })
                .listen('CommentUpdated', (data) => {
                    let comment = this.pickDeep(this.comments, data.comment.id);
                    comment.content = data.comment.content;
                })
                .listen('CommentDeleted', (data) => {
                    console.log(data);

                    if (data.parent) {
                        let parent = this.pickDeep(this.comments, data.parent);
                        let comment = this.pickDeep(this.comments, parseInt(data.id));
                        parent.children = _.filter(parent.children, function (n) {
                            return n.id !== comment.id;
                        });
                    } else {
                        let comment = this.pickDeep(this.comments, parseInt(data.id));
                        this.comments = _.filter(this.comments, function (n) {
                            return n.id !== comment.id;
                        });
                    }
                });
        },
        methods: {
            fetchComments: function () {
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
            },
            pickDeep: function (data, term) {
                let parent;
                data.some(function iter(a) {
                    if (a['id'] === term) {
                        parent = a;
                        return true;
                    }
                    return Array.isArray(a.children) && a.children.some(iter);
                });
                return parent;
            }
        },
        watch: {
            '$route': 'fetchComments'
        },
    }
</script>