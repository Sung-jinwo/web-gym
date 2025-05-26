document.addEventListener('DOMContentLoaded', function() {
    // Get the sidebar elements
    const sidebar = document.querySelector('.sidebar');
    const sidebarNav = document.querySelector('.sidebar__nav');
    const STORAGE_KEY = 'sidebar_scroll_position';
    const SIDEBAR_STATE_KEY = 'sidebar_collapsed_state';

    // ===== SIDEBAR TOGGLE FUNCTIONALITY =====
    // Initialize sidebar state from localStorage
    const savedSidebarState = localStorage.getItem(SIDEBAR_STATE_KEY);
    if (savedSidebarState === 'collapsed' && sidebar) {
        sidebar.classList.add('sidebar--collapsed');
        document.querySelector('.main-content')?.classList.add('main-content--expanded');
    }

    // Toggle menu function for collapsing sidebar
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
        if (sidebar) {
            sidebar.classList.toggle('sidebar--active');
        }
    };

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

    // ===== SUBMENU FUNCTIONALITY =====
    // Submenu toggle
    const submenuToggles = document.querySelectorAll('.sidebar__link--toggle');
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const menuItem = this.closest('.sidebar__item--has-submenu');
            menuItem.classList.toggle('sidebar__item--open');

            // Save state in localStorage
            const menuId = menuItem.dataset.menuId || 'menu-' + Array.from(menuItem.parentNode.children).indexOf(menuItem);
            localStorage.setItem('submenu_' + menuId, menuItem.classList.contains('sidebar__item--open') ? 'open' : 'closed');
        });
    });

    // Auto-open submenu if a submenu item is active
    const activeSubmenuItems = document.querySelectorAll('.sidebar__submenu-item.activo');
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

    // ===== ALERT FUNCTIONALITY =====
    // Función para cerrar la alerta
    window.closeAlert = function() {
        const alert = document.getElementById('alertBox');
        const backdrop = document.getElementById('alertBackdrop');
        const warning = document.getElementById('warningBox');

        if (alert && backdrop) {
            // Añadir animación de salida
            alert.classList.add('fade-out');
            backdrop.classList.add('backdrop-fade-out');

            // Eliminar los elementos después de que termine la animación
            setTimeout(() => {
                alert.style.display = 'none';
                backdrop.style.display = 'none';
            }, 300);
        }

        if (warning) {
            // Añadir animación de salida
            warning.classList.add('slide-out-right');

            // Eliminar el elemento después de que termine la animación
            setTimeout(() => {
                if (warning.parentNode) warning.parentNode.removeChild(warning);
            }, 300);
        }
    };

    // Función para iniciar el temporizador de cierre automático
    window.startAlertTimer = function() {
        // Auto-cerrar después de 5 segundos
        setTimeout(function() {
            closeAlert();
        }, 5000);
    };

    // Start alert timer if alerts exist
    if (document.getElementById('alertBox') || document.getElementById('warningBox')) {
        startAlertTimer();
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
