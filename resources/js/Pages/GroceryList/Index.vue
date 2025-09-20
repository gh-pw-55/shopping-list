<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg relative">
                    <div class="p-6 bg-white" v-for="item in data" :key="item.id">
                        <a :href="`/grocery-lists/${item.id}`"><span class="text-white bg-gray-800 border p-2 border-gray-500 rounded">{{ item.title }}</span></a>
                    </div>

                    <h3>Add Grocery List</h3>
                    <form @submit.prevent="submit">
                        <div class="py-2">
                            <InputLabel for="grocery_list" value="Grocery List" />
                            <TextInput id="grocery_list" v-model="createGroceryList.title" type="text"
                                class="mt-1 block w-full" />
                        </div>


                        <div class="py-2">

                            <PrimaryButton :disabled="disableSave"
                                class="py-2 px-4 text-white rounded bg-red-600 hover:bg-red-800">
                                Add Item
                            </PrimaryButton>
                        </div>
                        <div v-if="createGroceryList.recentlySuccessful">Item added to list!</div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    data: {
        type: Array,
    }
})

const createGroceryList = useForm({
    title: '',
});

const disableSave = computed(() => createGroceryList.title === '');

const submit = () => {
    createGroceryList.post(route('grocery-list.store'), {
        onFinish: () => {
            createGroceryList.title = '';
        },
    });
}
</script>
