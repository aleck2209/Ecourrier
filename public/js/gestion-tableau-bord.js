document.addEventListener('DOMContentLoaded', alertDelete(), displayFile());

// SOUMISSION DES FORMULAIRES DE TRIE
function submitFilter() {
    const form = document.getElementById('form-filter');
    form.submit();
}

// SOUMISSION DES FORMULAIRE DE FILTRE
function submitSort() {
    const form = document.getElementById('form-sort');
    form.submit();
}

// ALERTE DE SUPPRIMER COURRIER
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

// AFFICHAGE DES FICHIER OU PAS
function displayFile() {
    const lienCourriers = document.querySelectorAll('.lienCourrier');
    const regex = /.php$/;

    lienCourriers.forEach(link => {
        if(regex.test(link.href)) {
            link.style.display = 'none';
        }
    })
}

// DEBUT DE PERSISTANCE DES FILTRES