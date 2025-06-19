<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { SidebarGroup, SidebarGroupContent, SidebarMenu, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';

interface Props {
    items: NavItem[];
    class?: string;
}

defineProps<Props>();
</script>

<template>
    <SidebarGroup :class="`group-data-[collapsible=icon]:p-0 ${$props.class || ''}`">
        <SidebarGroupContent>
            <SidebarMenu>
                <SidebarMenuItem v-for="item in items" :key="item.title">
                    <Button
                        as-child
                        variant="ghost"
                        class="w-full justify-start"
                    >
                        <a :href="item.href" target="_blank" rel="noopener noreferrer">
                            <component :is="item.icon" class="mr-2 size-4" />
                            <span>{{ item.title }}</span>
                        </a>
                    </Button>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroupContent>
    </SidebarGroup>
</template>
