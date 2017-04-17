<template>
    <div class="row">
        <div class="col-md-12" v-for="comment in post.comments">
            {{ post.name }}
            <commentContainer v-bind:comment="comment"></commentContainer>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['post']
    }
</script>
