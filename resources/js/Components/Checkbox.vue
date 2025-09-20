<script setup>

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },
});

const toggle = (event) => {
    emit('update:checked', event.target.checked);
}

</script>

<template>
    <input type="checkbox" :value="value" :checked="checked" @click="toggle"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
</template>
