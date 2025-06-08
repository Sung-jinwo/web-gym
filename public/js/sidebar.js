document.addEventListener('DOMContentLoaded', function() {
    // Get the sidebar elements
    const sidebar = document.querySelector('.sidebar');
    const sidebarNav = document.querySelector('.sidebar__nav');
    const mobileOverlay = document.querySelector('.mobile-overlay');
    const STORAGE_KEY = 'sidebar_scroll_position';
    const SIDEBAR_STATE_KEY = 'sidebar_collapsed_state';

    // ===== SIDEBAR TOGGLE FUNCTIONALITY =====
    // Initialize sidebar state from localStorage
    const savedSidebarState = localStorage.getItem(SIDEBAR_STATE_KEY);
    if (savedSidebarState === 'collapsed' && sidebar) {
        sidebar.classList.add('sidebar--collapsed');
        document.querySelector('.main-content')?.classList.add('main-content--expanded');
    }

    // Toggle menu function for collapsing sidebar (DESKTOP)
    window.toggleMenu = function() {
        if (sidebar) {
            // Toggle collapsed state
            sidebar.classList.toggle('sidebar--collapsed');

            // Toggle main content expanded state
            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
                mainContent.classList.toggle('main-content--expanded');
            }

            // Save state to localStorage
            const isCollapsed = sidebar.classList.contains('sidebar--collapsed');
            localStorage.setItem(SIDEBAR_STATE_KEY, isCollapsed ? 'collapsed' : 'expanded');
        }
    };

    // Mobile menu toggle
    window.toggleMobileMenu = function() {
        if (sidebar && mobileOverlay) {
            sidebar.classList.toggle('sidebar--mobile-active');
            mobileOverlay.classList.toggle('active');

            // Prevenir scroll del body cuando el menú está abierto
            if (sidebar.classList.contains('sidebar--mobile-active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }
    };

    // Cerrar menú móvil
    window.closeMobileMenu = function() {
        if (sidebar && mobileOverlay) {
            sidebar.classList.remove('sidebar--mobile-active');
            mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    // ===== SUBMENU FUNCTIONALITY - CORREGIDO =====
    // Submenu toggle - SOLUCIÓN PARA EL PROBLEMA
    const submenuToggles = document.querySelectorAll('.sidebar__link--toggle');
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault(); // Prevenir la navegación por defecto
            e.stopPropagation(); // Detener la propagación del evento

            const menuItem = this.closest('.sidebar__item--has-submenu');
            if (menuItem) {
                menuItem.classList.toggle('sidebar__item--open');

                // Guardar estado en localStorage
                const menuId = menuItem.dataset.menuId || 'menu-' + Array.from(menuItem.parentNode.children).indexOf(menuItem);
                localStorage.setItem('submenu_' + menuId, menuItem.classList.contains('sidebar__item--open') ? 'open' : 'closed');

                // NO cerrar el menú móvil cuando se abre un submenú
                return false;
            }
        });
    });

    // Cerrar menú móvil SOLO al hacer clic en enlaces regulares (no en toggles de submenú)
    const regularLinks = document.querySelectorAll('.sidebar__link:not(.sidebar__link--toggle), .sidebar__submenu-link');
    regularLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                // Solo cerrar si es un enlace normal, no un toggle de submenú
                if (!this.classList.contains('sidebar__link--toggle')) {
                    closeMobileMenu();
                }
            }
        });
    });

    // Auto-open submenu if a submenu item is active
    const activeSubmenuItems = document.querySelectorAll('.sidebar__submenu-item.active');
    activeSubmenuItems.forEach(item => {
        const parentMenu = item.closest('.sidebar__item--has-submenu');
        if (parentMenu) {
            parentMenu.classList.add('sidebar__item--open');

            // Save state in localStorage
            const menuId = parentMenu.dataset.menuId || 'menu-' + Array.from(parentMenu.parentNode.children).indexOf(parentMenu);
            localStorage.setItem('submenu_' + menuId, 'open');
        }
    });

    // Restore submenu states from localStorage
    document.querySelectorAll('.sidebar__item--has-submenu').forEach(item => {
        const menuId = item.dataset.menuId || 'menu-' + Array.from(item.parentNode.children).indexOf(item);
        if (localStorage.getItem('submenu_' + menuId) === 'open') {
            item.classList.add('sidebar__item--open');
        }
    });

    // Cerrar menú móvil al redimensionar ventana
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeMobileMenu();
        }
    });

    // ===== SCROLL POSITION FUNCTIONALITY =====
    if (sidebarNav) {
        // Auto-save scroll position when clicking any link
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && !link.classList.contains('sidebar__link--toggle')) {
                localStorage.setItem(STORAGE_KEY, sidebarNav.scrollTop.toString());
            }
        });

        // Save position when user leaves the page
        window.addEventListener('beforeunload', function() {
            localStorage.setItem(STORAGE_KEY, sidebarNav.scrollTop.toString());
        });

        // Smooth scroll restoration with a slight delay
        setTimeout(function() {
            const savedPosition = localStorage.getItem(STORAGE_KEY);
            if (savedPosition) {
                smoothScrollTo(sidebarNav, parseInt(savedPosition), 300);
            }
        }, 100);
    }
});

// Smooth scroll helper function
function smoothScrollTo(element, targetPosition, duration) {
    const startPosition = element.scrollTop;
    const distance = targetPosition - startPosition;
    let startTime = null;

    function animation(currentTime) {
        if (startTime === null) startTime = currentTime;
        const timeElapsed = currentTime - startTime;
        const progress = Math.min(timeElapsed / duration, 1);
        const easeInOutQuad = progress < 0.5
            ? 2 * progress * progress
            : 1 - Math.pow(-2 * progress + 2, 2) / 2;

        element.scrollTop = startPosition + distance * easeInOutQuad;

        if (timeElapsed < duration) {
            requestAnimationFrame(animation);
        }
    }

    requestAnimationFrame(animation);
}
