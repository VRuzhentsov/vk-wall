<template>
    <div class="row" >
        <div class="col-md-12">
            <div class="row" v-for="post in posts">
                <div class="col-md-12">
                    <div>Post</div>
                    <comment v-for="comment in post.comments" is="comment" v-bind:item="comment" :key="comment"></comment>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        data:function () {
            return {
                posts:[
                    {
                        name:'one',
                        comments:[
                            {
                                name:'comment1'
                            },{
                                name:'comment2'
                            }
                        ]
                    },{
                        name:'two',
                        comments:[

                        ]
                    }
                ]
            };
        }
    }
</script>