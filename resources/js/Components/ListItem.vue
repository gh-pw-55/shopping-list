<template>
    <div class="bg-white px-12 justify-between p-6 text-gray-900 shadow-sm sm:rounded-lg">
        <div class="flex justify-between">
            <div class="flex gap-6">
                <div class="flex items-center">
                    <Checkbox :checked="checked" />
                </div>
                <div class="flex flex-col"><span class="text-gray-900">{{ grocery.name
                }}</span><span class="text-gray-400">Quantity: {{
                            grocery.quantity }}</span></div>
            </div>
            <div class="icons text-gray-900 flex items-center justify-end gap-4">
                <PencilIcon class="cursor-pointer" />
                <TrashIcon class="cursor-pointer" @click="emit('deleteItem', grocery)" />
            </div>
        </div>

    </div>
</template>

<script setup>
defineProps({
    grocery: {
        type: Object,
    }
})

defineEmits(['deleteItem']);
</script>
