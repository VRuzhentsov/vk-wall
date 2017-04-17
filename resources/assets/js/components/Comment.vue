<template>
    <div class="row">
        <div class="col-md-12">
            <p>
                {{ comment.name }}
            </p>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['comment']
    }
</script>