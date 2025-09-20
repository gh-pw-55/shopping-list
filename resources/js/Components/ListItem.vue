<template>
    <div class="shopping-list-item bg-white px-12 justify-between p-6 text-gray-900 shadow-sm sm:rounded-lg">
        <div class="flex justify-between">
            <div class="flex gap-6">
                <div class="flex items-center">
                    <Checkbox name="is_completed" :checked="isCompleted"
                        @update:checked="(val) => emit('toggleIsCompleted', val, grocery)" />
                </div>
                <div class="flex flex-col"><span class="text-gray-900" :class="{ 'line-through': isCompleted }">{{
                    grocery.name
                        }}</span><span class="text-gray-400" :class="{ 'line-through': isCompleted }">Quantity: {{
                            grocery.quantity }}</span></div>
            </div>
            <div class="icons text-gray-900 flex items-center justify-end gap-4">
                <TrashIcon class="cursor-pointer" @click="emit('deleteItem', grocery)" />

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6 hover-svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                </svg>

            </div>
        </div>

    </div>
</template>

<script setup>
import { computed } from 'vue';
import Checkbox from './Checkbox.vue';
import TrashIcon from './TrashIcon.vue';

const props = defineProps({
    grocery: {
        type: Object,
    }
})

const isCompleted = computed(() => {
    if (props.grocery.is_completed) {
        return 1;
    }

    return 0;
})

const emit = defineEmits(['deleteItem', 'toggleIsCompleted']);
</script>
<style scoped>
.shopping-list-item .hover-svg {
    visibility: hidden;
}

.shopping-list-item:hover .hover-svg {
    visibility: visible;
}
</style>
