<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <div class="min-h-screen bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100">
        <!-- Navbar -->
        <header class="bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100 shadow">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between">
                <h1 class="font-bold text-lg">🛒 Ecommerce</h1>

                <nav class="flex gap-6">
                    <Link class="hover:underline" href="/">Products</Link>
                    <Link class="hover:underline" href="/cart">Cart</Link>
                    <Link class="hover:underline" href="/orders">Orders</Link>
                </nav>
            </div>
        </header>

        <!-- Page content -->
        <main class="max-w-7xl mx-auto p-6">
            <slot />
        </main>
    </div>
</template>

