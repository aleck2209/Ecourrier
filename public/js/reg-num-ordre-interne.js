
const input = document.getElementById('numeroOrdre');
const form = document.getElementById('form-save-mail');
// ANNEE ENCOURS
const currentYear = new Date().getFullYear();

// AJOUT DU PLACEHOLDER
input.setAttribute('placeholder', `Ex: 152/DGE/DSI/${currentYear}`);


form.addEventListener('submit', (e) => {
    e.preventDefault();
    const regex = /^\d{1,4}(?:\/[A-Z]{2,10})+\/\d{4}$/
    console.log()
    if (regex.test(input.value)) {
        form.submit();
    } else {
        alert("veuillez respecter le format attendu, exemple: 25/DGE/DSI/2024.")
    }
});