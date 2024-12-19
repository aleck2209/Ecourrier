document.addEventListener('DOMContentLoaded', alertDelete(), displayFile());

// SOUMISSION DES FORMULAIRES DE TRIE
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

function restoreFilterValue() {
    document.querySelectorAll('#form-filter select').forEach(select => {
        const storedValue = localStorage.getItem(select.name);
        if (storedValue) {
            select.value = storedValue;
        }
    });
}

function handleSelectChange(event) {
    
    const changedSelect = event.target;
    console.log(changedSelect)

    localStorage.setItem(changedSelect.name, changedSelect.value);

    document.querySelectorAll('#form-filter select').forEach(select => {
        if (select !== changedSelect) {
            select.value = "";
            localStorage.removeItem(select.name);
        }
    });

    document.getElementById('form-filter').submit();
}

document.querySelectorAll('#form-filter select').forEach(select => {
    select.addEventListener('change', handleSelectChange);
});

window.onload = restoreFilterValue();

