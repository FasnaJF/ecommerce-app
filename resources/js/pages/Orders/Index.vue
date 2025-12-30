<script setup lang="ts">
defineProps<{
    orders: Array<{
        id: number;
        total: number;
        status: string;
        orderItems: Array<{
            quantity: number;
            product: {
                name: string;
            };
        }>;
    }>;
}>();
</script>

<template>
    <div
        v-for="order in orders"
        :key="order.id"
        class="border p-4 mb-4"
    >
        <div>Status: {{ order.status }}</div>
        <div>Total: {{ order.total }}</div>

        <ul class="ml-4">
            <li v-for="item in order.orderItems" :key="item.product.name">
                {{ item.product.name }} × {{ item.quantity }}
            </li>
        </ul>
    </div>
</template>
