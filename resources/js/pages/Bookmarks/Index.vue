<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Card } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { BookmarkX } from 'lucide-vue-next';
import type { Photo, BreadcrumbItem, PaginatedResponse } from '@/types';
import { computed } from 'vue';

const props = defineProps<{
    photos: PaginatedResponse<Photo>;
}>();

const getImageUrl = (path: string) => {
    return path.startsWith('http') ? path : `/storage/${path}`;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'My Bookmarks',
        href: route('bookmarks.index'),
    },
];

const photoItems = computed(() => props.photos.data);

</script>

<template>
    <Head title="My Bookmarks" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="photoItems && photoItems.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <Link v-for="photo in photoItems" :key="photo.id" :href="route('photos.show', { photo: photo.id })" class="block group relative overflow-hidden rounded-lg shadow-lg transition-shadow duration-300 hover:shadow-xl">
                        <img :src="getImageUrl(photo.file_path)" :alt="photo.title" class="h-60 w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent">
                            <div class="absolute bottom-0 left-0 p-4">
                                <h3 class="font-bold text-lg text-white">{{ photo.title }}</h3>
                                <p v-if="photo.user" class="text-sm text-gray-300">By: {{ photo.user.name }}</p>
                            </div>
                        </div>
                    </Link>
                </div>
                <Card v-else class="mt-8 flex flex-col items-center justify-center py-16 text-center">
                    <BookmarkX class="mx-auto mb-4 size-16 text-muted-foreground" />
                    <h3 class="text-2xl font-semibold leading-none tracking-tight">No bookmarks yet!</h3>
                    <p class="mt-2 text-sm text-muted-foreground">
                        Head over to the Discover page to find photos you like.
                    </p>
                    <Button as-child class="mt-6">
                        <Link :href="route('photos.discover')">
                            Discover Photos
                        </Link>
                    </Button>
                </Card>

                <!-- Pagination Links -->
                <div v-if="photos.links && photos.links.length > 3" class="mt-8 flex justify-center">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, index) in photos.links" :key="index">
                            <Link
                                :href="link.url ?? ''"
                                :class="[
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600 dark:bg-indigo-900 dark:border-indigo-700 dark:text-indigo-300' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700',
                                    { 'rounded-l-md': index === 0 },
                                    { 'rounded-r-md': index === photos.links.length - 1 },
                                    { '!bg-gray-100 dark:!bg-gray-700 !text-gray-400 dark:!text-gray-500 cursor-not-allowed': !link.url }
                                ]"
                                v-html="link.label"
                                :disabled="!link.url"
                            />
                        </template>
                    </nav>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
