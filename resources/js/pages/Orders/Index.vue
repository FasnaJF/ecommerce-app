<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});
defineProps<{
    orders: Array<{
        id: number;
        total: number;
        status: string;
        created_at: string;
        orderItems: Array<{
            quantity: number;
            price: number;
            product: {
                name: string;
            };
        }>;
    }>;
}>();

const openOrder = ref<number | null>(null);
</script>

<template>
    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">My Orders</h2>

    <div v-if="orders.length === 0" class="text-gray-500 dark:text-gray-400">
        You have no completed orders yet.
    </div>

    <div v-for="order in orders" :key="order.id"
        class="bg-white rounded shadow mb-4 border dark:bg-gray-800 dark:border-gray-700">
        <!-- Order header -->
        <div class="flex justify-between items-center p-4 cursor-pointer text-gray-900 dark:text-gray-100"
            @click="openOrder = openOrder === order.id ? null : order.id">
            <div>
                <div class="font-semibold">
                    Order #{{ order.id }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ new Date(order.created_at).toLocaleDateString() }}
                </div>
            </div>

            <div class="flex items-center gap-4">
                <span class="px-3 py-1 text-sm rounded-full" :class="{
                    'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200': order.status === 'completed',
                    'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200': order.status === 'processing',
                    'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200': order.status === 'cancelled',
                }">
                    {{ order.status }}
                </span>

                <div class="font-bold">
                    <span class="text-gray-900 dark:text-gray-100">{{ order.total }} USD</span>
                </div>
            </div>
        </div>

        <!-- Order items -->
        <div v-if="openOrder === order.id" class="border-t border-gray-200 dark:border-gray-700 px-4 pb-4">
            <table class="w-full mt-3">
                <thead>
                    <tr class="text-left text-sm text-gray-600 dark:text-gray-300">
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in order.orderItems" :key="item.product.name"
                        class="text-sm text-gray-700 dark:text-gray-300">
                        <td>{{ item.product.name }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ (item.quantity * item.price).toFixed(2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
