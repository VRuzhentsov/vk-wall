<template>
    <li class="comment">
        <div :id="comment.id" class="comment-body">
            <div class="info">
                <div class="author-name">{{ comment.author.name }}</div>
                <div class="comment-updated_at">{{ comment.updated_at | moment }}</div>
            </div>
            <div class="message">
                {{ comment.content }}
            </div>
            <a v-if="!renderInput" v-on:click="renderInput = true">Reply</a>
            <form class="form-group" v-if="renderInput" @keyup.enter="commentChild" v-on:submit.prevent="commentChild">
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
                commentInput: ''
            };
        },
        methods: {
            commentChild: function (event) {
                let that = this;
                let data = {
                    content: this.commentInput
                };
                this.$http.post('api/comments/' + this.comment.id + '/children', data)
                    .then(function (response) {
                        that.commentInput = '';
                    })
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