<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import InputError from '@/components/InputError.vue'
import type { BreadcrumbItemType } from '@/types'
import { Head, useForm } from '@inertiajs/vue3'

const form = useForm({
    title: '',
    description: '',
    image: null as File | null,
})

const breadcrumbs: BreadcrumbItemType[] = [
    {
        title: 'Upload Photo',
        href: route('photos.index'),
    },
]

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement
    if (target.files && target.files[0]) {
        form.image = target.files[0]
    }
}

const submit = () => {
    form.post(route('photos.store'), {
        onSuccess: () => {
            form.reset()
        },
    })
}
</script>

<template>
    <Head title="Upload Photo" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <Card>
                        <CardHeader>
                            <CardTitle> Upload New Photo </CardTitle>
                            <CardDescription>
                                Choose an image file and provide some details
                                about your photo.
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="grid gap-6">
                            <div class="grid w-full max-w-sm items-center gap-2">
                                <Label for="image">Photo</Label>
                                <Input
                                    id="image"
                                    type="file"
                                    accept="image/*"
                                    @change="handleFileChange"
                                />
                                <InputError :message="form.errors.image" />
                            </div>
                            <div class="grid w-full items-center gap-2">
                                <Label for="title">Title</Label>
                                <Input
                                    id="title"
                                    v-model="form.title"
                                    type="text"
                                    placeholder="e.g. A day at the beach"
                                />
                                <InputError :message="form.errors.title" />
                            </div>
                            <div class="grid w-full items-center gap-2">
                                <Label for="description"
                                    >Description (Optional)</Label
                                >
                                <Textarea
                                    id="description"
                                    v-model="form.description"
                                    placeholder="e.g. A beautiful sunset over the ocean."
                                />
                                <InputError
                                    :message="form.errors.description"
                                />
                            </div>
                        </CardContent>
                        <CardFooter class="flex justify-end">
                            <Button type="submit" :disabled="form.processing">
                                Upload Photo
                            </Button>
                        </CardFooter>
                    </Card>
                </form>
            </div>
        </div>
    </AppLayout>
</template>