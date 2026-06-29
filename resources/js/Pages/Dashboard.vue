<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                What's new?
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <Card class="h-screen overflow-x-auto">
                        <div class="text-xl">
                            <strong>Posts</strong>
                        </div>
                        <div class="mt-6 mb-3">
                            <div class="flex flex-col mb-3">
                                <TextField v-model="postForm.body"></TextField>
                                <div class="self-end">
                                    <PrimaryButton
                                        :disabled="postForm.processing"
                                        @click="createPost()"
                                    >
                                        POST
                                    </PrimaryButton>
                                </div>
                            </div>
                            <div class="flex flex-col gap-4">
                                <Post v-for="(post, key) in postsData.posts"
                                    :key="key"
                                    :post="post"
                                />
                            </div>
                            <div v-if="postsData.nextCursor" class="flex w-full justify-center mt-5">
                                <SecondaryButton v-if="!postsData.isProcessing" @click="loadMorePost()">
                                    Load More...
                                </SecondaryButton>
                                <Loader v-if="postsData.isProcessing" />
                            </div>
                        </div>
                        <div v-if="postsData.posts.length < 1" class="flex justify-center text-lg text-gray-500 mt-6 mb-3">
                            <strong>
                                No available posts
                            </strong>
                        </div>
                    </Card>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Card from '@/components/Card.vue';
import Post from '@/components/Post.vue';
import TextField from '@/components/TextField.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Loader from '@/Components/Loader.vue';

export default {
    components: {
        AuthenticatedLayout, Head, Card, Post, TextField, PrimaryButton, SecondaryButton, Loader
    },
    data() {
        return {
            postForm: this.$inertia.form({
                body: ''
            }),
            postsData : {
                isProcessing: false,
                posts: [],
                nextCursor: null
            }
        }
    },
    mounted() {
        this.retrievePosts();
    },
    methods: {
        createPost() {
            this.postForm.post(this.route('post.create'), {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    this.retrievePosts();
                },
                onError: (response) => {
                    if (Object.keys(response).length) {
                        for (const key in response) {
                            if (!Object.hasOwn(response, key)) continue;
                            const element = response[key];
                            useToast().error(element);
                        }
                    }
                },
                onFinish: () => {
                    this.postForm.body = '';
                }
            });
        },
        retrievePosts() {
            if (!this.postsData.isProcessing) {
                this.postsData.isProcessing = true
                axios.get(this.route('post.get'))
                    .then((response) => {
                        this.postsData.posts = response.data.posts;
                        this.postsData.nextCursor = response.data.next_cursor;
                    })
                    .finally(() => {
                        this.postsData.isProcessing = false;
                    });
            }
        },
        loadMorePost() {
            if (!this.postsData.isProcessing && this.postsData.nextCursor) {
                this.postsData.isProcessing = true;
                axios.get(this.route('post.get', { cursor: this.postsData.nextCursor}))
                    .then((response) => {
                        this.postsData.posts = [
                            ...this.postsData.posts,
                            ...response.data.posts
                        ];

                        this.postsData.nextCursor = response.data.next_cursor;
                    })
                    .finally(() => {
                        this.postsData.isProcessing = false;
                    })
            }
        }
    }
}
</script>