const sidebarLinks = document.querySelectorAll('.sidebar');

const current = window.location.href;

sidebarLinks.forEach(link => {
    if (link.href === current) {
        link.classList.add('sidebar-active');
        
    }
})