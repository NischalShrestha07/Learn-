

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.store('app', {
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        darkMode: localStorage.getItem('darkMode') === 'true',

        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
        },

        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            this.applyDarkMode();
            localStorage.setItem('darkMode', this.darkMode);
        },

        applyDarkMode() {
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },

        init() {
            this.applyDarkMode();
        },
    });
});

Alpine.start();
