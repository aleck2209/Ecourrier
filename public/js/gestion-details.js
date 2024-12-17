document.addEventListener('DOMContentLoaded', displayElement());

function displayElement() {
    const elements = document.querySelectorAll('.not-required');

    elements.forEach(div => {
        const paragraph = div.querySelector('.detail-element__content');
        const link = div.querySelector('.file');

        if (paragraph && paragraph.innerHTML.trim() == "") {
            div.style.display = 'none'
        }

        if (link && (!link.getAttribute('href') || link.getAttribute('href').trim() == "")) {
            div.style.display = 'none';
        }
    })
}