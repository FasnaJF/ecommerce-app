<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

defineProps<{
    cart: {
        id: number;
        total: number;
        orderItems: Array<{
            id: number;
            quantity: number;
            price: number;
            product: {
                name: string;
            };
        }>;
    } | null;
}>();

const updateQty = (itemId: number, qty: number) => {
    router.put(`/cart/item/${itemId}`, {
        quantity: qty,
    });
};

const removeItem = (itemId: number) => {
    router.delete(`/cart/item/${itemId}`);
};
</script>

<template>
    <div v-if="!cart || cart.orderItems.length === 0" class="bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100 rounded shadow">
        Your cart is empty 🛒
    </div>

    <div v-else>
        <table class="w-full border">
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th></th>
            </tr>

            <tr v-for="item in cart.orderItems" :key="item.id">
                <td>{{ item.product.name }}</td>
                <td>
                    <input type="number" min="1" :value="item.quantity"
                        @change="updateQty(item.id, +$event?.target?.value)" class="w-16 border" />
                </td>
                <td>{{ item.quantity * item.price }}</td>
                <td>
                    <button class="text-red-600" @click="removeItem(item.id)">
                        Remove
                    </button>
                </td>
            </tr>
        </table>

        <div class="mt-4 text-right font-bold">
            Total: {{ cart.total }} MAD
        </div>

        <button class="mt-4 bg-green-600 text-white px-6 py-2 rounded" @click="router.post('/checkout')">
            Checkout
        </button>
    </div>
</template>
