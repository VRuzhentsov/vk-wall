<template>
    <div class="row">
        <div class="col-md-12" v-for="comment in post.comments">
            <p>
                {{ post.name }}
            </p>
            <comment-container v-bind:comment="comment"></comment-container>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['post']
    }
</script>
