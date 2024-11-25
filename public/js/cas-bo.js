document.addEventListener('DOMContentLoaded', courrierExterneArrive())

function courrierExterneArrive() {
    // RECUPERATION DES VARIABLE
    const courrierDepart = document.querySelector('.page-content-save-mail-depart');
    const courrierArrive = document.querySelector('.page-content-save-mail-arrive');
    const bureauOrdre = document.getElementById('bureau-ordre');
    if(bureauOrdre.value == "BO") {
        courrierDepart.style.display = 'none'
        courrierArrive.style.display = 'block'
    } else {
        courrierDepart.style.display = 'block'
        courrierArrive.style.display = 'none'
    }

}