
const input = document.getElementById('numeroOrdre');
const forms = document.querySelectorAll('.form-save-mail');
const inputArrive = document.getElementById('numeroOrdreArrive');

// ANNEE ENCOURS
const currentYear = new Date().getFullYear();

// AJOUT DU PLACEHOLDER
input.setAttribute('placeholder', `Ex: 152/DGE/DSI/${currentYear}`);
inputArrive.setAttribute('placeholder', `Ex: 175/DGE/DRHF/${currentYear}`);

forms.forEach(form => {
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const regex = /^\d{1,4}(?:\/[A-Z]{2,10})+\/\d{4}$/
        console.log()
        if (regex.test(input.value) || regex.test(inputArrive.value)) {
            form.submit();
        } else {
            alert("veuillez respecter le format attendu, exemple: 25/DGE/DSI/2024.")
        }
    });
});
