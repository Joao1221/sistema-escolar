import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('sidebarLayout', (storageKey) => ({
    sidebarOpen: false,
    sidebarCollapsed: false,

    init() {
        if (typeof window === 'undefined') {
            return;
        }

        this.sidebarCollapsed = window.localStorage.getItem(storageKey) === '1';
    },

    toggleSidebar() {
        this.sidebarCollapsed = !this.sidebarCollapsed;

        if (typeof window !== 'undefined') {
            window.localStorage.setItem(storageKey, this.sidebarCollapsed ? '1' : '0');
        }
    },
}));

Alpine.start();
