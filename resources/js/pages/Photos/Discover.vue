<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Bookmark } from 'lucide-vue-next';
import type { Photo, BreadcrumbItem, PaginatedResponse } from '@/types';
import { computed } from 'vue';

const props = defineProps<{
    photos: PaginatedResponse<Photo>;
    bookmarkedPhotoIds: number[];
}>();

const getImageUrl = (path: string) => {
    return path.startsWith('http') ? path : `/storage/${path}`;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Discover Photos',
        href: route('photos.discover'),
    },
];

const photoItems = computed(() => props.photos.data);

const isBookmarked = (photoId: number): boolean => {
    return props.bookmarkedPhotoIds.includes(photoId);
};

const toggleBookmark = (photo: Photo) => {
    const options = {
        preserveScroll: true,
    };

    if (isBookmarked(photo.id)) {
        router.delete(route('photos.bookmarks.destroy', { photo: photo.id }), options);
    } else {
        // The second argument for router.post is the data payload, which is empty here.
        router.post(route('photos.bookmarks.store', { photo: photo.id }), {}, options);
    }
};

</script>

<template>
    <Head title="Discover Photos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="photoItems && photoItems.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <div v-for="photo in photoItems" :key="photo.id" class="group relative overflow-hidden rounded-lg shadow-lg transition-shadow duration-300 hover:shadow-xl">
                        <img :src="getImageUrl(photo.file_path)" :alt="photo.title" class="h-60 w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent">
                            <div class="absolute bottom-0 left-0 p-4">
                                <h3 class="font-bold text-lg text-white">{{ photo.title }}</h3>
                                <p v-if="photo.user" class="text-sm text-gray-300">By: {{ photo.user.name }}</p>
                            </div>
                            <div class="absolute top-2 right-2">
                                <button
                                    @click.prevent="toggleBookmark(photo)"
                                    class="p-2 bg-black/30 rounded-full text-white hover:bg-black/50 transition-colors"
                                    :aria-label="isBookmarked(photo.id) ? 'Unbookmark Photo' : 'Bookmark Photo'"
                                >
                                    <Bookmark
                                        class="w-5 h-5 transition-all"
                                        :class="{ 'fill-current text-yellow-400': isBookmarked(photo.id) }"
                                    />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-16">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">No photos to discover yet!</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Check back later or upload your own.</p>
                        <Link :href="route('photos.index')" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Upload Photo
                        </Link>
                    </div>
                </div>

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
