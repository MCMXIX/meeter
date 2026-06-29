<script setup>
import Calendar from '@/components/Calendar.vue';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs'

const props = defineProps({
    post: {
        type: Object,
        required: true,
    }
})
</script>
<template>
    <div class="p-5 rounded-md shadow-md w-full">
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-3">
                <Link :href="route('profile.get', { id: post.user.id })">
                    <img src="//static.vecteezy.com/system/resources/thumbnails/005/544/718/small/profile-icon-design-free-vector.jpg"
                        alt="Profile Picture"
                        class="w-14 h-14 rounded-full object-cover"
                    />
                </Link>
                <div class="flex flex-col">
                    <div class="text-base">
                        <Link :href="route('profile.get', { id: post.user.id })">
                            <strong>
                                {{ post.user.name ?? '' }}
                            </strong>
                        </Link>
                    </div>
                    <div class="text-sm self-end text-gray-600">
                        <small>
                            {{ dayjs(post.created_at).format('MMMM D, YYYY h:mm A') }}
                        </small>
                    </div>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="text-sm text-wrap">
                    {{ post.body }}
                </div>
            </div>
        </div>
    </div>
</template>