<template>
    <div class="bg-white px-12 justify-between p-6 text-gray-900 shadow-sm sm:rounded-lg">
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
