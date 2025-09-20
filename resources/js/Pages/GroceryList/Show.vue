<template>
    <AuthenticatedLayout>
        <div class="py-12 bg-slate-100 container px-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 relative">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 py-4">
                    {{ data.groceryList.title }}
                </h2>
                <div class="flex flex-col gap-4 ">
                    <template v-for="(grocery, key) in groceries.data" :key="grocery.id">
                        <ListItem :grocery="grocery" @deleteItem="deleteGroceryItem"
                            @toggleIsCompleted="toggleCompleted" />
                    </template>

                    <div class="pt-6 text-white">
                        <hr class="text-white bg-white" />
                    </div>
                    <h3 class="text-xl strong">Add items to list</h3>
                    <form @submit.prevent="submit">
                        <div class="py-2">
                            <InputLabel for="grocery_name" value="Grocery" />
                            <TextInput id="grocery_name" ref="groceryName" v-model="addGroceryItemForm.name" type="text"
                                class="mt-1 block w-full" />
                        </div>

                        <div class="py-2">
                            <InputLabel for="grocery_quantity" value="Quantity" />
                            <TextInput id="grocery_name" ref="groceryQuantity" v-model="addGroceryItemForm.quantity"
                                type="number" min="1" class="mt-1 block w-full" />
                        </div>


                        <div class="py-2">

                            <PrimaryButton :disabled="disableSave"
                                class="py-2 px-4 text-white rounded bg-red-600 hover:bg-red-800">
                                Add Item
                            </PrimaryButton>
                        </div>
                        <div v-if="addGroceryItemForm.recentlySuccessful">Item added to list!</div>
                    </form>
                </div>
                <div v-if="hasSuccess" class="bg-green-100 text-green-700 p-6 rounded fixed top-1/3 right-6">
                    {{ flash.success }}
                </div>
                <div v-if="addGroceryItemForm.hasErrors" class="bg-red-100 text-red-700 p-6 rounded fixed top-1/3 right-6">
                    {{ addGroceryItemForm.errors.name }}
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import ListItem from '@/Components//ListItem.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { computed, reactive, ref } from 'vue';

const page = usePage();

const props = defineProps({
    data: {
        type: Object,
    }
})

const addGroceryItemForm = useForm({
    name: '',
    quantity: 1,
    grocery_list_id: props.data.groceryList.id,
});

const groceryName = ref(null);
const groceryQuantity = ref(null);

const groceries = reactive({
    data: props.data.groceryList.groceries
})

const flash = computed(() => page.props.flash)

const hasSuccess = computed(() => flash.value && 'success' in flash.value && flash.value.success)

const disableSave = computed(() => {
    if (addGroceryItemForm.name === '') {
        return true;
    }

    return false;
})

const deleteGroceryItem = (item) => {
    if (confirm('Are you sure you want to remove this item?')) {
        router.delete(route('groceries.destroy', item.id), {
            preserveScroll: true,
            onSuccess: (resp) => {
                groceries.data = [...resp.props.data.groceries]
            }
        })
    }
}

const toggleCompleted = (checkVal, groceryItem) => {
    groceryItem.is_completed = checkVal

    router.patch(route('groceries.update', groceryItem.id), {
        is_completed: groceryItem.is_completed,
    }, {
        preserveScroll: true,
    });
}

const submit = () => {
    addGroceryItemForm.post(route('grocery.store'), {
        onFinish: () => {
            addGroceryItemForm.name = '';
            addGroceryItemForm.quantity = 1;
        },
        onSuccess: (resp) => {
            groceries.data = [...resp.props.data.groceries]
        },
        onError: (error) => {
            console.log('err on', error, addGroceryItemForm)
        }
    });
}
</script>
