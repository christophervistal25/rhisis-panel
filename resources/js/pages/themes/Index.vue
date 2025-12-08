<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div>
        <div class="flex min-h-screen items-center justify-center">
            <div class="card-rhisis text-center">
                <h1 class="glow mb-4 text-6xl font-black">RHISIS</h1>
                <p class="text-rh-muted mb-8 text-xl">Control Panel 2025</p>
                <button class="btn-rhisis">ENTER THE WORLD</button>
            </div>
        </div>
    </div>
</template>
