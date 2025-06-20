<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog'
import { Button } from '@/components/ui/button'
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'
import type { Photo, BreadcrumbItemType } from '@/types'
import { Head, Link, router } from '@inertiajs/vue3'
import { ImageOff, Trash2 } from 'lucide-vue-next'
import { ref } from 'vue'

defineProps<{ photos: Photo[] }>()

const showDeleteDialog = ref(false)
const photoToDelete = ref<Photo | null>(null)

const getImageUrl = (path: string) => {
    return path.startsWith('http') ? path : `/storage/${path}`
}

const confirmDeletePhoto = (photo: Photo) => {
    photoToDelete.value = photo
    showDeleteDialog.value = true
}

const handleDeletePhoto = () => {
    if (!photoToDelete.value) return

    router.delete(route('photos.destroy', photoToDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            photoToDelete.value = null
            showDeleteDialog.value = false
            // Optionally: show success toast
        },
        onError: (errors) => {
            console.error('Error deleting photo:', errors)
            // Optionally: show error toast
        },
    })
}

const breadcrumbs: BreadcrumbItemType[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
]
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto py-8 sm:px-6 lg:px-8">
            <div
                v-if="photos && photos.length > 0"
                class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
            >
                <Card
                    v-for="photo in photos"
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
                        <Link :href="route('photos.show', { photo: photo.id })">
                            <CardTitle class="mb-1 truncate text-lg">
                                {{ photo.title }}
                            </CardTitle>
                        </Link>
                    </CardContent>
                    <CardFooter class="p-4 pt-0">
                        <Button
                            variant="destructive"
                            size="sm"
                            class="w-full"
                            @click="confirmDeletePhoto(photo)"
                        >
                            <Trash2 class="mr-2 size-4" />
                            Delete
                        </Button>
                    </CardFooter>
                </Card>
            </div>

            <Card
                v-else
                class="mt-8 flex flex-col items-center justify-center py-16 text-center"
            >
                <ImageOff class="mx-auto mb-4 size-16 text-muted-foreground" />
                <h3 class="text-2xl font-semibold leading-none tracking-tight">No photos yet!</h3>
                <p class="mt-2 text-sm text-muted-foreground">
                    Why not upload your first one?
                </p>
                <Button as-child class="mt-6">
                    <Link :href="route('photos.index')">
                        Upload Photo
                    </Link>
                </Button>
            </Card>
        </div>

        <AlertDialog :open="showDeleteDialog" @update:open="showDeleteDialog = $event">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                    <AlertDialogDescription>
                        This action cannot be undone. This will permanently delete
                        "{{ photoToDelete?.title }}" and remove its data from our
                        servers.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel @click="showDeleteDialog = false">
                        Cancel
                    </AlertDialogCancel>
                    <AlertDialogAction
                        @click="handleDeletePhoto"
                        variant="destructive"
                    >
                        Delete
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AppLayout>
</template>