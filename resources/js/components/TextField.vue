<script setup>
    import { onMounted, ref } from 'vue';

    const model = defineModel({
        type: String,
        required: true,
    });

    const input = ref(null);

    onMounted(() => {
        if (input.value.hasAttribute('autofocus')) {
            input.value.focus();
        }
    });

    defineExpose({ focus: () => input.value.focus() });
</script>
<template>
    <div class="space-y-2">
        <textarea
            id="bio"
            rows="5"
            placeholder="What's new?"
            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 h-full"
            v-model="model"
            ref="input"
        ></textarea>
  </div>
</template>
