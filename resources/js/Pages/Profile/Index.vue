<template>
    <Head title="Profile" />
    <AuthenticatedLayout>
        <div class="flex flex-col md:flex-row gap-2 py-5 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col justify-center items-center w-full md:w-1/2 self-start bg-slate-50 rounded-md">
                <Card class="h-screen">
                    <div class="flex flex-col items-center">
                        <div v-if="type === 'profile'" class="flex justify-between items-center w-full">
                            <div class="text-sm text-gray-700">
                                Views: <strong>{{ data.views ?? 0 }}</strong>
                            </div>
                            <div class="self-end">
                                <SecondaryButton v-if="!isEditEnabled"
                                    @click="toggleEdit(false)"
                                >
                                    Edit
                                </SecondaryButton>
                                <PrimaryButton v-if="isEditEnabled"
                                    :disabled="informations.processing"
                                    @click="toggleEdit(true)"
                                >
                                    <span v-if="!informations.processing">
                                        Save
                                    </span>
                                    <Loader v-else />
                                </PrimaryButton>
                            </div>
                        </div>
                        <div v-if="type === 'visit'" class="flex justify-end items-center w-full">
                            <SecondaryButton @click="message()">
                                SLIDE A DM!
                            </SecondaryButton>
                        </div>
                        <div class="flex justify-center">
                            <img src="//static.vecteezy.com/system/resources/thumbnails/005/544/718/small/profile-icon-design-free-vector.jpg"
                                alt="Profile Picture"
                                class="w-32 h-32 rounded-full object-cover"
                            />
                        </div>
                        <div class="flex flex-col gap-1 items-center">
                            <div v-if="isEditEnabled" class="space-y-2 my-2">
                                <div class="flex gap-6">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input v-model.trim="informations.gender"
                                            type="radio"
                                            value="M"
                                            class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500"
                                        />
                                        <span class="text-sm text-gray-700">Male</span>
                                    </label>

                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input v-model.trim="informations.gender"
                                            type="radio"
                                            value="F"
                                            class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500"
                                        />
                                        <span class="text-sm text-gray-700">Female</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex gap-2 items-center">
                                <div class="text-base">
                                    {{ data.informations.name }}<span v-if="age && age > 0">, {{ age }}</span>
                                </div>
                                <div v-if="data.informations.gender && !isEditEnabled">
                                    <i v-if="data.informations.gender === 'M'" class="fa-solid fa-mars"></i>
                                    <i v-if="data.informations.gender === 'F'" class="fa-solid fa-venus"></i>
                                </div>
                            </div>
                            <span>{{ data.informations.email }}</span>
                        </div>
                        <div class="flex w-50 mt-6 max-w-xl text-wrap w-full">
                            <div class="rounded-md shadow-md p-4 w-full">
                                <span class="block mb-2">
                                    <small>Bio</small>
                                </span>
                                <div v-if="data.informations.bio && !isEditEnabled" class="text-wrap">
                                    {{ data.informations.bio }}
                                </div>
                                <div v-if="!data.informations.bio && !isEditEnabled" class="text-wrap">
                                    ....
                                </div>
                                <TextField v-if="isEditEnabled" v-model.trim="informations.bio" />
                            </div>
                        </div>
                        <div class="border-2 border-t-indigo-500 my-5 w-full rounder-lg">
                        </div>
                        <div class="self-start text-xl">
                            <strong>Personal Details</strong>
                        </div>
                        <div class="mt-2 flex flex-col self-start gap-2 w-full">
                            <div v-if="data.informations.address && !isEditEnabled" class="flex gap-2 items-center">
                                <span class="text-base">
                                    <strong>Address</strong>
                                </span>
                                <span class="text-sm">
                                    {{ informations.address }}
                                </span>
                            </div>
                            <div v-if="isEditEnabled" class="flex flex-col gap-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Address
                                </label>
                                <TextInput v-model.trim="informations.address" />
                            </div>
                            <div v-if="data.informations.contact_number && !isEditEnabled" class="flex gap-2 items-center">
                                <span class="text-base">
                                    <strong>Contact Number</strong>
                                </span>
                                <span class="text-sm">
                                    {{ informations.contact_number }}
                                </span>
                            </div>
                            <div v-if="isEditEnabled" class="flex flex-col gap-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Contact Number
                                </label>
                                <TextInput v-model.trim="informations.contact_number" />
                            </div>
                            <div v-if="isEditEnabled" class="flex flex-col gap-2">
                                <span class="text-base">
                                    <strong>Birthdate</strong>
                                </span>
                                <Calendar v-model="informations.birth_date" />
                            </div>
                        </div>
                        <div class="mt-5" v-if="!isPersonalDataSet && type === 'profile' && !isEditEnabled">
                            Set your personal details...
                        </div>
                    </div>
                </Card>
            </div>
            <div class="flex flex-col justify-center items-center w-full md:w-1/2 self-end bg-slate-50 rounded-md">
                <Card class="h-screen overflow-y-auto">
                    <div class="text-xl">
                        <strong>Posts</strong>
                    </div>
                    <div v-if="postsData.posts" class="mt-6 mb-3">
                        <div v-if="type === 'profile'" class="flex flex-col">
                            <TextField v-model.trim="postForm.body"></TextField>
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
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Card from '@/components/Card.vue';
import Post from '@/components/Post.vue';
import TextField from '@/components/TextField.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Loader from '@/components/Loader.vue';
import Calendar from '@/components/Calendar.vue';
import dayjs from 'dayjs'
import axios from 'axios';
import { useToast } from 'vue-toastification'

export default {
    components: {
        AuthenticatedLayout, Head, Card, Post, TextField, TextInput, PrimaryButton, SecondaryButton, Loader, Calendar
    },
    props: [
        'type', 'user', 'data', 'views'
    ],
    data() {
        return {
            isEditEnabled: false,
            informations: this.$inertia.form({
                bio: this.data.informations.bio ?? '',
                address: this.data.informations.address ?? '',
                contact_number: this.data.informations.contact_number ?? '',
                gender: this.data.informations.gender ?? null,
                birth_date: this.data.informations.birth_date ?? null
            }),
            postForm: this.$inertia.form({
                body: ''
            }),
            postsData: {
                isProcessing: false,
                posts: [],
                nextCursor: null
            }
        }
    },
    mounted() {
        if (this.type === 'visit') {
            axios.post(route('profile.visit'), { user_id: this.data.informations.id });
        }

        this.retrievePosts();
    },
    computed: {
        isPersonalDataSet() {
            return (this.data.informations.address || this.data.informations.contact_number)
        },
        age() {
            if (this.data.informations.birth_date) {
                return dayjs().diff(this.data.informations.birth_date, 'year');
            }

            return null
        }
    },
    methods: { 
        toggleEdit(save = false) {
            if (save) {
                this.saveChanges();
                return;
            }

            this.isEditEnabled = !this.isEditEnabled;
        },
        saveChanges() {
            this.informations.post(this.route('profile.update'), {
                onError: () => {
                    useToast().error('Something went wrong updating informations.');
                },
                onFinish: () => {
                    this.isEditEnabled = false;
                }
            })
        },
        message() {
            this.$inertia.visit(this.route('conversation.check', { user_id: this.data.informations.id }));
        },
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
                axios.get(this.route('post.get', { user_id: this.data.informations.id }))
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
                axios.get(this.route('post.get', {
                    user_id: this.data.informations.id,
                    cursor: this.postsData.nextCursor
                }))
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