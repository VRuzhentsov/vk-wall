<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <actions></actions>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" v-for="post in posts">
                <p>Wall Block</p>
                <postContainer :post="post"></postContainer>
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