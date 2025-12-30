<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

defineProps<{
    products: {
        data: Array<{
            id: number;
            name: string;
            price: number;
            stock: number;
            // image?: string;
        }>;
    };
}>();

const addToCart = (productId: number) => {
    router.post('/cart/add', {
        product_id: productId,
        quantity: 1,
    }, {
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="grid grid-cols-3 gap-6">
        <h2 class="text-xl font-bold mb-6">Products</h2>

        <div v-for="p in products.data" :key="p.id"
            class="border p-4 rounded bg-white hover:shadow transition dark:bg-gray-800 dark:border-gray-700">
            <!-- <img v-if="p.image" :src="p.image" class="h-40 w-full object-cover" /> -->

            <h3 class="font-bold mt-2 text-gray-900 dark:text-gray-100">{{ p.name }}</h3>
            <p class="text-gray-700 dark:text-gray-300">{{ p.price }} MAD</p>
            <p class="mt-1 text-sm"
                :class="p.stock < 5 ? 'text-red-600 dark:text-red-400 font-bold' : 'text-gray-600 dark:text-gray-400'">
                Available: {{ p.stock }}
                <span v-if="p.stock < 5">⚠️ Low stock!</span>
            </p>
            <button
                class="mt-2 bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-500 dark:bg-blue-500 dark:hover:bg-blue-400"
                @click="addToCart(p.id)">
                Add to Cart
            </button>
        </div>
    </div>
</template>
