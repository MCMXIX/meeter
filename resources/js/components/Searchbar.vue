<script setup>
import { computed, ref } from 'vue'
import { onMounted } from 'vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
dayjs.extend(relativeTime)

const search = ref('')

const users = ref([]);

const isReqLoading = ref(false);

const filtered = computed(() => {
    if (!search.value) return []

    return users.value.filter(user =>
        user.name.toLowerCase().includes(search.value.toLowerCase())
    )
})

function searchUser() {
    isReqLoading.value = true
    if (search.value.length >= 3) {
        axios.get('/user/search')
            .then(function (response) {
                if (response.data.length > 0) {
                    users.value = response.data;
                } else {
                    users.value = [];
                }
            })
            .finally(() => {
                isReqLoading.value = false;
            });
    }
}

function getAge(date) {
    if (date) {
        return dayjs().diff(date, 'year');
    }

    return null;
}

</script>

<template>
    <div class="relative w-64">
        <input
            v-model="search"
            placeholder="Search people..."
            class="w-full rounded-full border border-gray-300 px-5 py-3"
            @input="searchUser()"
        />
        <div
            v-if="filtered.length"
            class="absolute z-10 mt-2 w-full overflow-hidden rounded-xl border bg-white shadow-lg"
        >
            <button
                v-for="user in filtered"
                :key="user.id"
                class="flex w-full items-center gap-3 px-4 py-3 hover:bg-gray-50"
            >
                <img
                    :src="'//static.vecteezy.com/system/resources/thumbnails/005/544/718/small/profile-icon-design-free-vector.jpg'"
                    class="h-10 w-10 rounded-full object-cover"
                />

                <div class="text-left">
                    <Link :href="`${route('profile.get')}/${user.id}`">
                        <p class="font-medium">
                            {{ user.name }}, {{ getAge(user.birth_date) }}
                        </p>
                        <p class="text-sm text-gray-500">
                            View profile
                        </p>
                    </Link>
                </div>
            </button>
        </div>
    </div>
</template>