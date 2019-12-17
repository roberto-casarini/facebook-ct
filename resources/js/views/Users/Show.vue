<template>
    <div class="flex flex-col items-center">
        <div class="relative mb-8">
            <div class="w-100 h-64 overflow-hidden z-10">
                <img src="https://cdn.pixabay.com/photo/2016/01/29/16/57/prague-1168302_960_720.jpg" alt="user background image" class="object-cover w-full">
            </div>

            <div class="flex items-center absolute bottom-0 left-0 -mb-8 z-20 ml-12">
                <div class="w-32">
                    <img src="https://assetsds.cdnedge.bluemix.net/sites/default/files/styles/big_6/public/news/images/barguna_rifak_sharif.jpg?itok=XJFX5EBT" alt="profile image for user" class="object-cover w-32 h-32 border-4 border-gray-200 rounded-full shadow-lg">
                </div>
                <p class="text-2xl text-gray-100 ml-4">{{ user.data.attributes.name }}</p>
            </div>
        </div>

        <p v-if="postLoading">Loading posts...</p>

        <Post v-else v-for="post in posts.data" :key="post.data.post_id" :post="post" />

        <p v-if="! postLoading && posts.data.length < 1">No posts found. Get started...</p>
    </div>
</template>

<script>
import Post from '../../components/Post';

export default {
    name: "Show",

    data: () => {
        return {
            user: null,
            posts: null,
            userLoading: true,
            postLoading: true
        }
    },

    components: {
        Post
    },

    mounted() {
        axios.get('/api/users/' + this.$route.params.userId)
            .then(response => {
                this.user = response.data;
            })
            .catch(error => {
                console.log('Unable to loading the user from the server');
            })
            .finally(() => {
                this.userLoading = false;
            });

        axios.get('/api/users/' + this.$route.params.userId + '/posts')
            .then(response => {
                this.posts = response.data;
            })
            .catch(error => {
                console.log('Unable to fetch the user posts from the server');
            })
            .finally(() => {
                this.postLoading = false;
            });

    }
}
</script>

<style>

</style>
