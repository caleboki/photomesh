<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Photo, BreadcrumbItem } from '@/types';
import { Bookmark } from 'lucide-vue-next';

interface PageProps {
    photo: Photo;
    isBookmarked: boolean;
}

const props = defineProps<PageProps>();

const page = usePage();
const authUser = computed(() => page.props.auth.user);

const localIsBookmarked = ref(props.isBookmarked);

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    {
        title: 'Discover',
        href: route('photos.discover'),
    },
    {
        title: props.photo.title,
        href: route('photos.show', { photo: props.photo.id }),
    },
]);

const getImageUrl = (path: string) => {
    return path.startsWith('http') ? path : `/storage/${path}`;
};

const formattedJoinDate = computed(() => {
    if (!props.photo.user?.created_at) return '';
    return new Date(props.photo.user.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
    });
});

const toggleBookmark = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            localIsBookmarked.value = !localIsBookmarked.value;
        },
        onError: () => {
            // Handle potential errors, e.g., show a notification
        },
    };

    if (localIsBookmarked.value) {
        router.delete(route('photos.bookmarks.destroy', { photo: props.photo.id }), options);
    } else {
        router.post(route('photos.bookmarks.store', { photo: props.photo.id }), {}, options);
    }
};
</script>

<template>
    <Head :title="props.photo.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="md:flex">
                        <div class="md:w-2/3">
                            <img :src="getImageUrl(props.photo.file_path)" :alt="props.photo.title" class="w-full h-full object-cover max-h-[75vh]">
                        </div>

                        <div class="md:w-1/3 p-6 flex flex-col justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ props.photo.title }}</h1>
                                <p v-if="props.photo.description" class="mt-2 text-gray-600 dark:text-gray-400">{{ props.photo.description }}</p>
                            </div>

                            <div>
                                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                                    <div class="flex items-center">
                                        <img v-if="props.photo.user?.profile_photo_url" class="h-12 w-12 rounded-full object-cover" :src="props.photo.user.profile_photo_url" :alt="props.photo.user.name">
                                        <div class="ml-4">
                                            <div class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ props.photo.user.name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Joined {{ formattedJoinDate }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 space-y-4" v-if="authUser && authUser.id !== props.photo.user_id">
                                    <button @click="toggleBookmark" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium transition-colors" :class="[localIsBookmarked ? 'bg-yellow-400 text-yellow-900 hover:bg-yellow-500' : 'bg-indigo-600 text-white hover:bg-indigo-700', 'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']">
                                        <Bookmark class="w-5 h-5 mr-2" :class="{ 'fill-current': localIsBookmarked }" />
                                        <span>{{ localIsBookmarked ? 'Bookmarked' : 'Bookmark' }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
