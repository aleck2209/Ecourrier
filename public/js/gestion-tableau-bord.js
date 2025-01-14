document.addEventListener('DOMContentLoaded', alertDelete(), displayFile(), copieCase());

// SOUMISSION DES FORMULAIRES DE TRIE
function submitSort() {
    localStorage.clear();

    const form = document.getElementById('form-sort');
    const selectSortType = document.getElementById('sortType');
    const selectSortOrder = document.getElementById('sortOrder');

    localStorage.setItem('sortType', selectSortType.value);
    localStorage.setItem('sortOrder', selectSortOrder.value);

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


// DEBUT PERSISTANCE DES FILTRES
// FONCTION POUR RESTAURER LES INPUT DE RECHERCHE
function restoreFilterValue() {
    const inputKeyWord = document.getElementById('motCle');
    const inputStartDate = document.getElementById('startDate');
    const inputEndDate = document.getElementById('endDate');

    const selectSortType = document.getElementById('sortType');
    const selectSortOrder = document.getElementById('sortOrder');

    const storedKeyWord = localStorage.getItem('keyWord');
    const storedStartDate = localStorage.getItem('startDate');
    const storedEndDate = localStorage.getItem('endDate');
    const storedSortType = localStorage.getItem('sortType');
    const storedSortOrder = localStorage.getItem('sortOrder');

    // RESTAURATION DES DONNEES POUR LES FILTRES
    document.querySelectorAll('#form-filter select').forEach(select => {
        const storedValue = localStorage.getItem(select.name);
        if (storedValue) {
            select.value = storedValue;
        }
    });

    // RESTAURATION DES DONNEES POUR LA RECHERCHE AVEC MOT CLES
    if (storedKeyWord) {
        inputKeyWord.value = storedKeyWord;
    }

    // RESTAURATION DES DONNEES POUR LA RECHERCHE AVEC UN INTERVALLE DE DATES
    if (storedEndDate && storedStartDate) {
        inputStartDate.value = storedStartDate;
        inputEndDate.value = storedEndDate;
    }

    if (storedSortOrder && storedSortType) {
        selectSortOrder.value = storedSortOrder;
        selectSortType.value = storedSortType;
    }
}

function handleSelectChange(event) {
    localStorage.clear();

    const changedSelect = event.target;

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
// FIN PERSISTANCE DES FILTRES

// AFFICHER TOUT LES COURRIERS
function allDisplay() {
    const form = document.getElementById('form-filter');
    const selects = form.querySelectorAll('select');

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        selects.forEach(select => {
            select.value = ""
            localStorage.clear();
        });
        form.submit();
    })
}

function searchKeyWord() {
    const input = document.getElementById('motCle');
    const form = document.querySelector('.form-search-key-word');

    localStorage.clear();

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        localStorage.setItem('keyWord', input.value)

        form.submit();
    })
}

function searchDate() {
    const inputStartDate = document.getElementById('startDate');
    const inputEndDate = document.getElementById('endDate');
    const form = document.querySelector('.form-search-date');
    
    localStorage.clear();

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        localStorage.setItem('startDate', inputStartDate.value);
        localStorage.setItem('endDate', inputEndDate.value);

        form.submit();
    })
}

function copieCase() {
    divs = document.querySelectorAll('.element-dashboard-mail');

    divs.forEach(div => {
        const input = div.querySelector('input');
        const btnSupprimer = div.querySelector('.supprimer');
        const btnModifier = div.querySelector('.btn-modifier');
        if (input.value == 'copie courrier') {
            btnModifier.style.display = 'none';
            btnSupprimer.style.display = 'none'
        }
    })
}    