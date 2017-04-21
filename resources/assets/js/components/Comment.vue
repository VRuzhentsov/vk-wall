<template>
    <div class="row">
        <div class="col-md-12">
            <div :id="comment.id">
                <p>{{ comment.content }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['comment']
    }
</script>