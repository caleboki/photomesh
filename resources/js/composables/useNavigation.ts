import type { NavItem } from '@/types'
import {
    Bookmark,
    BookOpen,
    Compass,
    Folder,
    LayoutGrid,
    UploadCloud,
} from 'lucide-vue-next'

export function useNavigation() {
    const mainNavItems: NavItem[] = [
        {
            title: 'Dashboard',
            href: route('dashboard'),
            icon: LayoutGrid,
            active: route().current('dashboard'),
        },
        {
            title: 'Upload Photo',
            href: route('photos.index'),
            icon: UploadCloud,
            active: route().current('photos.index'),
        },
        {
            title: 'Discover',
            href: route('photos.discover'),
            icon: Compass,
            active: route().current('photos.discover'),
        },
        {
            title: 'My Bookmarks',
            href: route('bookmarks.index'),
            icon: Bookmark,
            active: route().current('bookmarks.index'),
        },
    ]

    const footerNavItems: NavItem[] = [
        {
            title: 'Github Repo',
            href: 'https://github.com/caleboki/photomesh',
            icon: Folder,
        },
        {
            title: 'Documentation',
            href: 'https://github.com/caleboki/photomesh/blob/main/README.md',
            icon: BookOpen,
        },
    ]

    return { mainNavItems, footerNavItems }
}
