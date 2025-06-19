<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <Button
                    as-child
                    :variant="item.active ? 'secondary' : 'ghost'"
                    class="w-full justify-start"
                >
                    <Link :href="item.href">
                        <component :is="item.icon" class="mr-2 size-4" />
                        <span>{{ item.title }}</span>
                    </Link>
                </Button>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
