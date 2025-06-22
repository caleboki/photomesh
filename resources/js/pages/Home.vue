<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Camera, Heart, UploadCloud, Lock } from 'lucide-vue-next'
</script>

<template>
    <Head title="Welcome to PhotoMesh" />
    <div class="bg-background text-foreground">
        <!-- Header -->
        <header class="fixed top-0 left-0 right-0 z-50 bg-background/80 backdrop-blur-sm">
            <div class="container mx-auto flex h-20 items-center justify-between px-4 md:px-6">
                <Link :href="route('home')" class="flex items-center gap-2">
                    <Camera class="h-8 w-8 text-primary" />
                    <span class="text-2xl font-bold">PhotoMesh</span>
                </Link>
                <nav class="hidden items-center gap-4 md:flex">
                    <Button variant="ghost" as-child>
                        <Link :href="route('login')">Login</Link>
                    </Button>
                    <Button as-child>
                        <Link :href="route('register')">Get Started</Link>
                    </Button>
                </nav>
                 <div class="md:hidden">
                    <Button variant="outline" as-child>
                         <Link :href="route('login')">Login</Link>
                    </Button>
                </div>
            </div>
        </header>

        <main class="pt-20">
            <!-- Hero Section -->
            <section class="relative py-20 md:py-32">
                 <div
                    aria-hidden="true"
                    class="absolute inset-0 grid grid-cols-2 -space-x-52 opacity-40 dark:opacity-20"
                >
                    <div class="blur-[106px] h-56 bg-gradient-to-br from-primary to-purple-400 dark:from-blue-700" />
                    <div class="blur-[106px] h-32 bg-gradient-to-r from-cyan-400 to-sky-300 dark:to-indigo-600" />
                </div>
                                <div class="container relative mx-auto px-4 text-center">
                    <h1 class="text-4xl font-extrabold tracking-tight md:text-5xl lg:text-6xl">
                        Your Beautiful Moments, Perfectly Organized
                    </h1>
                    <p class="mx-auto mt-6 max-w-2xl text-lg text-muted-foreground">
                        PhotoMesh is the elegant solution for storing, discovering, and bookmarking your favorite photos. Built for speed, privacy, and a beautiful user experience.
                    </p>
                    <div class="mt-8 flex justify-center gap-4">
                        <Button size="lg" as-child>
                            <Link :href="route('register')">Sign Up For Free</Link>
                        </Button>
                        
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="py-16 bg-muted/40">
                <div class="container mx-auto px-4">
                    <div class="mb-12 text-center">
                        <h2 class="text-3xl font-bold">Why You'll Love PhotoMesh</h2>
                        <p class="mt-2 text-muted-foreground">Everything you need in a modern photo gallery.</p>
                    </div>
                    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        <div class="flex flex-col items-center text-center p-6 rounded-lg">
                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                                <UploadCloud class="h-8 w-8" />
                            </div>
                            <h3 class="text-xl font-semibold">Seamless Uploads</h3>
                            <p class="mt-2 text-muted-foreground">
                                Quickly and easily upload your photos with our simple drag-and-drop interface.
                            </p>
                        </div>
                        <div class="flex flex-col items-center text-center p-6 rounded-lg">
                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                                <Heart class="h-8 w-8" />
                            </div>
                            <h3 class="text-xl font-semibold">Discover & Bookmark</h3>
                            <p class="mt-2 text-muted-foreground">
                                Explore a public gallery of photos and save your favorites with a single click.
                            </p>
                        </div>
                        <div class="flex flex-col items-center text-center p-6 rounded-lg">
                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                                <Lock class="h-8 w-8" />
                            </div>
                            <h3 class="text-xl font-semibold">Private & Secure</h3>
                            <p class="mt-2 text-muted-foreground">
                                Your uploaded photos are your own. We respect your privacy and keep your data safe.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="py-6 border-t">
            <div class="container mx-auto px-4 text-center text-muted-foreground">
                <p>&copy; {{ new Date().getFullYear() }} PhotoMesh. All rights reserved.</p>
            </div>
        </footer>
    </div>
</template>
