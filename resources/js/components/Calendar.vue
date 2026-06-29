<script setup>
    const model = defineModel({
        type: String
    });
</script>
<template>
    <div>
        <input type="date"
            v-model="model"
            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-pink-500 focus:ring-pink-500"
        />
    </div>
</template>