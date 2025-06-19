<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue'
import Breadcrumbs from '@/components/Breadcrumbs.vue'
import NavFooter from '@/components/NavFooter.vue'
import NavMain from '@/components/NavMain.vue'
import NavUser from '@/components/NavUser.vue'
import { useNavigation } from '@/composables/useNavigation'
import { Button } from '@/components/ui/button'
import { Sheet, SheetContent, SheetTrigger } from '@/components/ui/sheet'
import type { BreadcrumbItemType } from '@/types'
import { Link } from '@inertiajs/vue3'
import { PanelLeft } from 'lucide-vue-next'

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[]
    }>(),
    {
        breadcrumbs: () => [],
    },
)

const { mainNavItems, footerNavItems } = useNavigation()
</script>

<template>
    <header
        class="flex h-14 items-center gap-4 border-b bg-background px-4 sm:h-16 sm:px-6"
    >
        <Sheet>
            <SheetTrigger as-child>
                <Button size="icon" variant="outline" class="sm:hidden">
                    <PanelLeft class="size-5" />
                    <span class="sr-only">Toggle Menu</span>
                </Button>
            </SheetTrigger>
            <SheetContent side="left" class="flex flex-col sm:max-w-xs">
                <div class="p-4">
                    <Link :href="route('dashboard')" class="mb-4 flex items-center">
                        <AppLogo class="size-8" />
                    </Link>
                    <NavMain :items="mainNavItems" />
                </div>

                <div class="mt-auto flex flex-col gap-4 p-4">
                    <NavFooter :items="footerNavItems" />
                    <NavUser />
                </div>
            </SheetContent>
        </Sheet>

        <Breadcrumbs :breadcrumbs="breadcrumbs" class="hidden md:block" />
    </header>
</template>
