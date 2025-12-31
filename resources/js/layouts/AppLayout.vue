<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const isLoggedIn = page.props.auth?.user;
</script>

<template>
    <div class="min-h-screen bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100">
        <!-- Navbar -->
        <header class="bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100 shadow">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <h1 class="font-bold text-lg">🛒 Ecommerce</h1>

                <nav class="flex gap-6 items-center">
                    <Link class="hover:underline" href="/">Products</Link>
                    <Link class="hover:underline" href="/cart">Cart</Link>
                    <Link class="hover:underline" href="/orders">Orders</Link>
                    
                    <template v-if="isLoggedIn">
                        <span class="text-sm">{{ isLoggedIn.name }}</span>
                        <Link 
                            class="hover:underline text-red-600 dark:text-red-400" 
                            href="/logout" 
                            method="post" 
                            as="button"
                        >
                            Logout
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <!-- Page content -->
        <main class="max-w-7xl mx-auto p-6">
            <slot />
        </main>
    </div>
</template>

