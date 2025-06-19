<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'
import {
    Pagination,
    PaginationEllipsis,
    PaginationContent,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination'
import type { Photo, BreadcrumbItemType, PaginatedResponse, Link as InertiaLinkType } from '@/types'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import { Bookmark, ImageOff } from 'lucide-vue-next'
import { computed } from 'vue'

const props = defineProps<{
    photos: PaginatedResponse<Photo>
    bookmarkedPhotoIds: number[]
}>()

const page = usePage()
const authUser = computed(() => page.props.auth.user)

const breadcrumbs: BreadcrumbItemType[] = [
    {
        title: 'Discover Photos',
        href: route('photos.discover'),
    },
]

const getImageUrl = (path: string) => {
    return path.startsWith('http') ? path : `/storage/${path}`
}

const photoItems = computed(() => props.photos.data)
const paginationLinks = computed(() => props.photos.links)

const bookmarkedSet = computed(() => new Set(props.bookmarkedPhotoIds))

const isBookmarked = (photoId: number): boolean => {
    return bookmarkedSet.value.has(photoId)
}

const toggleBookmark = (photo: Photo) => {
    const options = {
        preserveScroll: true,
        preserveState: true,
    }

    if (isBookmarked(photo.id)) {
        router.delete(
            route('photos.bookmarks.destroy', { photo: photo.id }),
            options,
        )
    } else {
        router.post(
            route('photos.bookmarks.store', { photo: photo.id }),
            {},
            options,
        )
    }
}

const getPageUrl = (pageNumber: number): string => {
    const link = paginationLinks.value.find(l => !isNaN(Number(l.label)) && Number(l.label) === pageNumber);
    return link?.url ?? '';
}
</script>

<template>
    <Head title="Discover Photos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto py-8 sm:px-6 lg:px-8">
            <div
                v-if="photoItems.length > 0"
                class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
            >
                <Card
                    v-for="photo in photoItems"
                    :key="photo.id"
                    class="overflow-hidden"
                >
                    <Link
                        :href="route('photos.show', { photo: photo.id })"
                        class="block"
                    >
                        <CardHeader class="p-0">
                            <img
                                :src="getImageUrl(photo.file_path)"
                                :alt="photo.title"
                                class="aspect-square h-auto w-full object-cover transition-transform duration-300 hover:scale-105"
                            />
                        </CardHeader>
                    </Link>
                    <CardContent class="p-4">
                        <Link
                            :href="route('photos.show', { photo: photo.id })"
                        >
                            <CardTitle class="mb-1 truncate text-lg">
                                {{ photo.title }}
                            </CardTitle>
                        </Link>
                        <CardDescription
                            v-if="photo.user"
                            class="text-sm text-muted-foreground"
                        >
                            By: {{ photo.user.name }}
                        </CardDescription>
                    </CardContent>
                    <CardFooter
                        v-if="authUser && authUser.id !== photo.user_id"
                        class="p-4 pt-0"
                    >
                        <Button
                            variant="outline"
                            size="sm"
                            class="w-full"
                            @click.prevent="toggleBookmark(photo)"
                        >
                            <Bookmark
                                class="mr-2 size-4"
                                :class="{
                                    'fill-primary text-primary':
                                        isBookmarked(photo.id),
                                }"
                            />
                            {{
                                isBookmarked(photo.id)
                                    ? 'Bookmarked'
                                    : 'Bookmark'
                            }}
                        </Button>
                    </CardFooter>
                </Card>
            </div>

            <Card
                v-else
                class="mt-8 flex flex-col items-center justify-center py-16"
            >
                <CardHeader class="text-center">
                    <ImageOff class="mx-auto mb-4 size-16 text-muted-foreground" />
                    <CardTitle>No photos to discover yet!</CardTitle>
                    <CardDescription class="mt-2">
                        Check back later or be the first to share.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Button as-child>
                        <Link :href="route('photos.index')">
                            Upload Photo
                        </Link>
                    </Button>
                </CardContent>
            </Card>

            <div
                v-if="photos.links && photos.links.length > 3"
                class="mt-12 flex justify-center"
            >
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <template v-for="(link, index) in photos.links" :key="index">
                        <Link
                            :href="link.url || '#'"
                            :class="[
                                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                link.active ? 'z-10 bg-primary-50 border-primary-500 text-primary-600 dark:bg-primary-900 dark:border-primary-700 dark:text-primary-300' : 'bg-background border-border text-muted-foreground hover:bg-muted',
                                { 'rounded-l-md': index === 0 },
                                { 'rounded-r-md': index === photos.links.length - 1 },
                                { 'bg-muted text-muted-foreground cursor-not-allowed': !link.url }
                            ]"
                            v-html="link.label"
                            :disabled="!link.url"
                        />
                    </template>
                </nav>
            </div>
        </div>
    </AppLayout>
</template>