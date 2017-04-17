<template>
    <div class="row" >
        <div class="col-md-12" v-for="post in posts">
            <p>Wall Block</p>
            <post-container :post="post"></post-container>
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