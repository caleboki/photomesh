<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader } from '@/components/ui/card'
import { Separator } from '@/components/ui/separator'
import type { BreadcrumbItemType, Photo, User } from '@/types'
import { Head, router, usePage } from '@inertiajs/vue3'
import { Bookmark } from 'lucide-vue-next'
import { computed, ref } from 'vue'

interface PageProps {
    photo: Photo & { user: User }
    isBookmarked: boolean
}

const props = defineProps<PageProps>()

const page = usePage<{ auth: { user: User } }>()
const authUser = computed(() => page.props.auth.user)

const localIsBookmarked = ref(props.isBookmarked)

const breadcrumbs = computed<BreadcrumbItemType[]>(() => [
    {
        title: 'Discover',
        href: route('photos.discover'),
    },
    {
        title: props.photo.title,
        href: route('photos.show', { photo: props.photo.id }),
        truncate: true,
    },
])



const formattedJoinDate = computed(() => {
    if (!props.photo.user?.created_at) return ''
    return new Date(props.photo.user.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
    })
})

const toggleBookmark = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            localIsBookmarked.value = !localIsBookmarked.value
        },
    }

    if (localIsBookmarked.value) {
        router.delete(
            route('photos.bookmarks.destroy', { photo: props.photo.id }),
            options,
        )
    } else {
        router.post(
            route('photos.bookmarks.store', { photo: props.photo.id }),
            {},
            options,
        )
    }
}
</script>

<template>
    <Head :title="props.photo.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto py-8 sm:px-6 lg:px-8">
            <Card class="overflow-hidden">
                <div class="grid md:grid-cols-3">
                    <div class="md:col-span-2">
                        <CardHeader class="p-0">
                            <img
                                :src="props.photo.file_url"
                                :alt="props.photo.title"
                                class="h-full max-h-[80vh] w-full object-cover"
                            />
                        </CardHeader>
                    </div>

                    <div class="flex flex-col p-6 md:col-span-1">
                        <div class="flex-grow">
                            <h1 class="mb-2 text-3xl font-bold">
                                {{ props.photo.title }}
                            </h1>
                            <p
                                v-if="props.photo.description"
                                class="text-muted-foreground"
                            >
                                {{ props.photo.description }}
                            </p>
                        </div>

                        <div class="mt-6">
                            <Separator class="my-6" />

                            <div class="flex items-center space-x-4">
                                <Avatar>
                                    <AvatarImage
                                        v-if="props.photo.user?.profile_photo_url"
                                        :src="props.photo.user.profile_photo_url"
                                        :alt="props.photo.user.name"
                                    />
                                    <AvatarFallback>
                                        {{
                                            props.photo.user.name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}
                                    </AvatarFallback>
                                </Avatar>
                                <div class="flex-grow">
                                    <p class="font-semibold">
                                        {{ props.photo.user.name }}
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        Joined {{ formattedJoinDate }}
                                    </p>
                                </div>
                            </div>

                            <div
                                v-if="authUser && authUser.id !== props.photo.user_id"
                                class="mt-6"
                            >
                                <Button
                                    class="w-full"
                                    variant="outline"
                                    @click="toggleBookmark"
                                >
                                    <Bookmark
                                        class="mr-2 size-4"
                                        :class="{
                                            'fill-primary text-primary':
                                                localIsBookmarked,
                                        }"
                                    />
                                    {{
                                        localIsBookmarked
                                            ? 'Bookmarked'
                                            : 'Bookmark'
                                    }}
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>