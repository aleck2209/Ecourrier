const navLiens = document.querySelectorAll('.nav-bar__link');

const currentURL = window.location.href;

navLiens.forEach(link => {
    if (link.href === currentURL) {
        link.classList.add('nav-bar__link_active');
    }
})