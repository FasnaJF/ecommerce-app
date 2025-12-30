<script setup lang="ts">
import { router } from '@inertiajs/vue3';

defineProps<{
    products: {
        data: Array<{
            id: number;
            name: string;
            price: number;
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
        <div
            v-for="p in products.data"
            :key="p.id"
            class="border p-4 rounded"
        >
            <!-- <img v-if="p.image" :src="p.image" class="h-40 w-full object-cover" /> -->

            <h3 class="font-bold mt-2">{{ p.name }}</h3>
            <p>{{ p.price }} MAD</p>

            <button
                class="mt-2 bg-blue-600 text-white px-4 py-1 rounded"
                @click="addToCart(p.id)"
            >
                Add to Cart
            </button>
        </div>
    </div>
</template>
