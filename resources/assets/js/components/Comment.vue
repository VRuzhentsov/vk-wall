<template>
    <li class="comment">
        <div :id="comment.id" class="comment-body">
            <div class="info">
                <router-link :to="{ name: 'wall', params: { userId: comment.author.id } }">
                    <div class="author-name">{{ comment.author.name }}</div>
                </router-link>
                <div class="comment-updated_at">{{ comment.updated_at | moment }}</div>
                <div v-if="this.comment.author.id == this.$store.state.user.id">
                    <a href="" v-on:click.prevent="renderUpdate = true; renderInput = false">
                        <i class="fa fa-btn fa-fw  fa-edit"></i>
                    </a>
                    <a href="" v-on:click.prevent="deleteComment">
                        <i class="fa fa-btn fa-fw  fa-remove"></i>
                    </a>

                </div>
            </div>
            <div class="message" v-if="!renderUpdate">
                {{ comment.content }}
            </div>
            <div class="message" v-if="renderUpdate">
                <form class="form-group" v-if="renderUpdate" @keyup.enter="updateComment"
                      v-on:submit.prevent="updateComment">
                    <textarea class="form-control" name="commentUpdate" v-model="commentUpdate"
                              title="Reply"></textarea>
                    <input type="submit" class="btn btn-default">
                </form>
            </div>
            <a v-if="!renderInput" v-on:click.prevent="renderInput = true; renderUpdate = false">Reply</a>
            <form class="form-group" v-if="renderInput" @keyup.enter="commentPost" v-on:submit.prevent="commentPost">
                <textarea class="form-control" name="commentInput" v-model="commentInput" title="Reply"></textarea>
                <input type="submit" class="btn btn-default">
            </form>
        </div>
        <div class="comment-children">
            <ul class="col-md-12 list-unstyled">
                <commentContainer v-for="commentChild in comment.children" :comment="commentChild"></commentContainer>
            </ul>
        </div>
    </li>
</template>

<script>
    export default {
        props: ['comment'],
        data: function () {
            return {
                children: [],
                renderInput: false,
                renderUpdate: false,
                commentInput: '',
                commentUpdate: this.comment.content,
                authAutor: false
            };
        },
        mounted: function () {
            if (this.$store.state) {

//                this.authAutor = ;
            }
        },
        methods: {
            commentPost: function (event) {
                let that = this;
                let data = {
                    content: this.commentInput
                };
                this.$http.post('api/users/' + this.comment.owner_id + '/comments/' + this.comment.id + '/children', data)
                    .then(function (response) {
                        that.commentInput = '';
                        that.renderInput = false;
                    })
            },
            updateComment: function (event) {
                let that = this;
                let data = {
                    content: this.commentUpdate
                };
                this.$http.put('api/users/' + this.comment.owner_id + '/comments/' + this.comment.id, data)
                    .then(function (response) {
                        that.renderUpdate = false;
                    })
            },
            deleteComment: function (event) {
                let that = this;
                this.$http.delete('api/users/' + this.comment.owner_id + '/comments/' + this.comment.id);
            },
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