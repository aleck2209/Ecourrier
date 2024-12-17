document.addEventListener('DOMContentLoaded', DisplayElement());


function DisplayElement() {
    const elements = document.querySelectorAll('.not-required');

    // VARIABLE DES LABELS
    const pieceJoints = document.getElementById('pieceJoint');
    const fichierEnregistre = document.getElementById('fichierEnregistre');

    // VARIABLE CATEGORIE
    const categorie = document.getElementById('categorie');
    const optionUpdate = document.getElementById('optionUpdate');

    
    // VALEUR DES PARAGRPHES
    const fichier = "fichier enregistré";
    const piece = "Pièce(s) Jointes(s)";

    // VARIABLE COPIE, REFERENCE, TYPE DE DOCUMENT
    const reference = document.getElementById('reference');
    const typeDocument = document.getElementById('typeDocument') 
    const copie = document.getElementById('copie');


    // AJOUT DE PLACEHORDER QUAND ELEMENT EST VIDE
    if (copie.value.trim() == "") {
        copie.setAttribute('placeholder', 'Aucune entité a été mis en copie')
    }
    if (reference.value.trim() == "") {
        reference.setAttribute('placeholder', 'Pas de reférence renseigné')
    }
    if (typeDocument.value.trim() == "") {
        typeDocument.setAttribute('placeholder', 'Type de document non renseigné')
    }


    
    // GESTION DE LA CATEGORIE
    if (optionUpdate.value.trim() == "urgent" || optionUpdate.value.trim() == "Urgent") {
        const normalOption = new Option('normal', 'normal');
        categorie.add(normalOption);
    } else if (optionUpdate.value.trim() == "normal" || optionUpdate.value.trim()) {
        const urgentOption = new Option('urgent', 'urgent');
        categorie.add(urgentOption);
    }

    // AFFICHAGE OU PAS DES FICHIER
    elements.forEach(div => {
        const link = div.querySelector('.update-file');
        const paragraph = div.querySelector('p')

        if (link && (!link.getAttribute('href') || link.getAttribute('href').trim() == "")) {
            div.style.display = 'none';
        }

        if (paragraph.textContent.trim() === fichier) {
            if (link && (!link.getAttribute('href') || link.getAttribute('href').trim() == "")) {
                fichierEnregistre.textContent = "Ajouter fichier du courrier";
            } else {
                fichierEnregistre.textContent = "Remplacer fichier du courrier";
            }
        }
        
        if (paragraph.textContent.trim() === piece) {
            if (link && (!link.getAttribute('href') || link.getAttribute('href').trim() == "")) {
                pieceJoints.textContent = "Ajouter pièce(s) jointe(s)";
            } else {
                pieceJoints.textContent = "Remplacer pièce(s) jointe(s)";
            }
        }

    })
}