<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { Photo, BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';

defineProps<{ photos: Photo[] }>();

const getImageUrl = (path: string) => {
    // Ensure we are not duplicating the /storage/ prefix if it's already there
    return path.startsWith('http') ? path : `/storage/${path}`;
};

const deletePhoto = (photoId: number) => {
    if (confirm('Are you sure you want to delete this photo?')) {
        router.delete(route('photos.destroy', photoId), {
            preserveScroll: true, // Keep scroll position after redirect
            onSuccess: () => {
                // Optionally, show a toast notification or handle success
            },
            onError: (errors) => {
                // Handle errors, e.g., show a toast notification
                console.error('Error deleting photo:', errors);
            },
        });
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
];
</script>
<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="photos && photos.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <div v-for="photo in photos" :key="photo.id" class="group relative overflow-hidden rounded-lg shadow-lg transition-shadow duration-300 hover:shadow-xl">
                        <img :src="getImageUrl(photo.file_path)" :alt="photo.title" class="h-60 w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent">
                            <div class="absolute bottom-0 left-0 p-4">
                                <h3 class="font-bold text-lg text-white">{{ photo.title }}</h3>
                                <button @click.stop="deletePhoto(photo.id)" class="absolute top-2 right-2 p-1.5 bg-red-600/80 hover:bg-red-700/90 rounded-full text-white transition-colors duration-200 z-10">
                                    <Trash2 class="w-4 h-4" />
                                    <span class="sr-only">Delete photo</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-16">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">No photos yet!</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Why not upload your first one?</p>
                        <Link :href="route('photos.index')" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Upload Photo
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
