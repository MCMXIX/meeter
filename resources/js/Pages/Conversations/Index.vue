<template>
    <Head title="Profile" />
    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Messages
            </h2>
        </template>
        <div class="flex flex-col md:flex-row gap-2 py-5 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <Card class="bg-white shadow-sm">
                <div class="flex flex-row gap-5">
                    <div class="w-2/5 flex flex-col bg-gray-100 gap-2 rounded-md shadow-md p-4 h-screen">
                        <div class="text-lg">
                            <strong>
                                Conversations
                            </strong>
                        </div>
                        <div class="border-2 border-t-indigo-500 w-3/4">
                        </div>
                        <div class="flex flex-col gap-3 mt-5 overflow-y-auto">
                            <div v-for="(conversation, key) in conversations"
                                :key="key"
                                class="flex gap-2 cursor-pointer"
                                @click="changeActiveConversation(conversation.id)"
                            >
                                <div class="w-1/6">
                                    <img src="//static.vecteezy.com/system/resources/thumbnails/005/544/718/small/profile-icon-design-free-vector.jpg"
                                        alt="Profile Picture"
                                        class="rounded-full object-cover w-16 h-16"
                                    />
                                </div>
                                <div class="flex flex-col w-5/6 pl-2 gap-1 justify-center">
                                    <div class="text-baseline flex justify-between items-center">
                                        <strong>{{ conversation.participant.name }}</strong>
                                        <div class="text-sm">
                                            <small>
                                                {{ timeAgo(conversation.created_at) }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="text-sm truncate text-gray-500">
                                        {{ conversation.last_message }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-3/5 bg-gray-200 rounded-md shadow-md max-h-screen">
                        <div v-if="selectedConversation" class="pt-4 flex flex-col h-5/6">
                            <div class="flex gap-2 items-center px-12">
                                <img src="//static.vecteezy.com/system/resources/thumbnails/005/544/718/small/profile-icon-design-free-vector.jpg"
                                    alt="Profile Picture"
                                    class="rounded-full object-cover w-16 h-16"
                                />
                                <div class="flex flex-col gap-1 cursor-pointer">
                                    <div class="text-baseline flex items-center">
                                        <strong>
                                            {{ selectedConversation.participant.name }}
                                            <span v-if="getAge(selectedConversation.participant.birth_date)">
                                                , {{ getAge(selectedConversation.participant.birth_date) }}
                                            </span>
                                        </strong>
                                        <div v-if="selectedConversation.participant.gender" class="ml-2">
                                            <i v-if="selectedConversation.participant.gender === 'M'" class="fa-solid fa-mars"></i>
                                            <i v-if="selectedConversation.participant.gender === 'F'" class="fa-solid fa-venus"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="border-2 border-t-indigo-500 w-full mt-2">
                            </div>
                            <div ref="messagesContainer" class="h-5/6 overflow-y-auto" @scroll="onScroll">
                                <div class="flex flex-col w-full gap-4 pt-8 px-4">
                                    <div v-for="(message, key) in activeConversation.data"
                                        :key="key"
                                        class="flex w-full items-center gap-4"
                                    >
                                        <img v-if="message.sender_id != user.id" src="//static.vecteezy.com/system/resources/thumbnails/005/544/718/small/profile-icon-design-free-vector.jpg"
                                            alt="Profile Picture"
                                            class="rounded-full object-cover w-10 h-10"
                                        />
                                        <div class="rounded-lg bg-black text-white text-sm p-2 max-w-md text-wrap" :class="{'ml-auto': (message.sender_id === user.id)}">
                                            {{ message.body }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="selectedConversation" class="flex gap-2 px-4 mb-4 h-1/6">
                            <TextField class="w-5/6"
                                v-model.trim="form.message"
                                @keyup.enter="sendMessage()"
                            />
                            <PrimaryButton class="w-1/6 h-full flex justify-center"
                                :disabled="form.processing"
                                @click="sendMessage()"
                            >
                                Send
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import Card from '@/components/Card.vue';
import axios from 'axios';
import Loader from '@/components/Loader.vue';
import TextField from '@/components/TextField.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useToast } from 'vue-toastification';

dayjs.extend(relativeTime)

export default {
        components: {
            AuthenticatedLayout, Head, Card, Loader, TextField, PrimaryButton
        },
        props: [
            'type', 'user', 'data'
        ],
        data() {
            return {
                activeConversation: {
                    id: null,
                    processing: false,
                    data: [],
                    nextCursor: null,
                },
                form: this.$inertia.form({
                    conversation_id: null,
                    message: ''
                })
            }
        },
        watch: {
            'activeConversation.data.length'() {
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            }
        },
        computed: {
            conversations() {
                return this.data.conversations;
            },
            selectedConversation() {
                if (this.activeConversation) {
                    let activeConvo = this.conversations.filter((conversation) => {
                        return (conversation.id == this.activeConversation.id);
                    });

                    return (activeConvo.length > 0) ? activeConvo[0] : null;
                }

                return null;
            },
            messages() {
                return this.data.messages;
            }
        },
        mounted() {
            if (this.data.active_id) {
                this.retrieveMessages(this.data.active_id);
            }
        },
        methods: { 
            timeAgo(date) {
                return dayjs(date).fromNow()
            },
            getAge(date) {
                if (date) {
                    return dayjs().diff(date, 'year');
                }

                return null;
            },
            changeActiveConversation(conversationId) {
                this.$inertia.visit(this.route('conversation.get', { id: conversationId }));
            },
            retrieveMessages(conversationId) {
                this.activeConversation.processing = true;
                this.activeConversation.id = conversationId;

                axios.get(this.route('conversation.get.messages', {
                    id: conversationId
                }))
                .then((response) => {
                    this.activeConversation.data = response.data.messages;
                    this.activeConversation.nextCursor = response.data.next_cursor;

                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                })
                .finally(() => {
                    this.activeConversation.processing = false;
                });
            },
            onScroll() {
                const container = this.$refs.messagesContainer;
                if (container.scrollTop <= 20) {
                    this.loadOlderMessages();
                }
            },
            loadOlderMessages() {
                if (!this.activeConversation.nextCursor) {
                    return;
                }

                const container = this.$refs.messagesContainer;
                const oldHeight = container.scrollHeight;
                axios.get(this.route('conversation.get.messages', {
                    id: this.activeConversation.id,
                    cursor: this.activeConversation.nextCursor
                }))
                .then((response) => {
                    this.activeConversation.data = [
                        ...response.data.messages,
                        ...this.activeConversation.data
                    ];

                    this.activeConversation.nextCursor = response.data.next_cursor;

                    this.$nextTick(() => {
                        container.scrollTop =
                            container.scrollHeight - oldHeight;
                    });
                });
            },
            sendMessage() {
                this.form.conversation_id = this.selectedConversation.id;
                this.form.post(this.route('conversation.send.message'), {
                    preserveScroll: true,
                    preserveState: true,
                    onError: () => {
                        useToast().error('Something went wrong sending message.');
                    },
                    onFinish: () => {
                        this.form.message = '';
                        this.retrieveMessages(this.selectedConversation.id);
                    }
                });
            },
            scrollToBottom() {
                this.$nextTick(() => {
                    const container = this.$refs.messagesContainer;

                    if (!container) return;

                    container.scrollTop = container.scrollHeight;
                });
            }
        }
    }
</script>