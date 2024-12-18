document.addEventListener('DOMContentLoaded', alertDelete());

function submitFilter() {
    const form = document.getElementById('form-filter');
    form.submit();
}

function submitSort() {
    const form = document.getElementById('form-sort');
    form.submit();
}

function alertDelete() {
    const supprimer = document.querySelectorAll('.supprimer');
    const alertBox = document.getElementById('alert-box');
    const confirmExit = document.getElementById('confirm-exit');
    const cancelExit = document.getElementById('cancel-exit');
    let redirectUrl = null;

    // CLIC SUR LE BOUTON SUPPRIMER
    supprimer.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            redirectUrl = link.href;
            alertBox.classList.remove('alert-hidden');
            console.log(link.href);
        });
    });

    // ACTION DE ALERTE
    confirmExit.addEventListener('click', () => {
        alertBox.classList.add("alert-hidden");
        if(redirectUrl) {
            window.location.href = redirectUrl;
        }
    });

    cancelExit.addEventListener('click', () => {
        alertBox.classList.add('alert-hidden');
        redirectUrl = null;
    })
}