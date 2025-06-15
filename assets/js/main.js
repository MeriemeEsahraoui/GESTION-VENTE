// Sidebar Toggle
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
}

// Mobile Sidebar Toggle
function toggleMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
}

// Close mobile sidebar when clicking overlay
document.getElementById('sidebarOverlay').addEventListener('click', function() {
    toggleMobileSidebar();
});

// Submenu Toggle
function toggleSubmenu(element) {
    const submenu = element.nextElementSibling;
    const chevron = element.querySelector('.fa-chevron-down');
    
    if (submenu) {
        submenu.classList.toggle('show');
        chevron.style.transform = submenu.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
    }
}

// Active Menu Item (only for mobile sidebar closing)
document.querySelectorAll('.sidebar-menu a').forEach(link => {
    link.addEventListener('click', function(e) {
        // Don't prevent default for submenu toggles or regular navigation
        if (!this.onclick && !this.href) {
            e.preventDefault();
        }
        
        // Close mobile sidebar when a menu item is clicked
        if (window.innerWidth <= 768) {
            toggleMobileSidebar();
        }
        
        // Don't override server-side active state - let PHP handle it
    });
});

// Responsive handling
function handleResize() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (window.innerWidth > 768) {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    }
}

window.addEventListener('resize', handleResize);

// Initialize tooltips for collapsed sidebar
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Add tooltips to sidebar items when collapsed
function updateTooltips() {
    const sidebar = document.getElementById('sidebar');
    const menuItems = document.querySelectorAll('.sidebar-menu a span');
    
    if (sidebar.classList.contains('collapsed')) {
        menuItems.forEach(item => {
            const link = item.closest('a');
            link.setAttribute('data-bs-toggle', 'tooltip');
            link.setAttribute('data-bs-placement', 'right');
            link.setAttribute('title', item.textContent);
        });
        initTooltips();
    } else {
        menuItems.forEach(item => {
            const link = item.closest('a');
            link.removeAttribute('data-bs-toggle');
            link.removeAttribute('data-bs-placement');
            link.removeAttribute('title');
        });
    }
}

// Update tooltips when sidebar is toggled
document.querySelector('.sidebar-toggle').addEventListener('click', function() {
    setTimeout(updateTooltips, 300); // Wait for animation to complete
});

// Logout function
function logout() {
    if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
        // Clear session data
        localStorage.removeItem('isLoggedIn');
        localStorage.removeItem('userEmail');
        localStorage.removeItem('userName');
        localStorage.removeItem('userRole');
        
        // Redirect to login page
        window.location.href = '../index.php';
    }
}

// Check if user is logged in on page load
document.addEventListener('DOMContentLoaded', function() {
    // Only check on pages other than login
    if (!window.location.pathname.includes('index.php')) {
        if (localStorage.getItem('isLoggedIn') !== 'true') {
            // Not logged in, redirect to login
            window.location.href = '../index.php';
        }
    }
});