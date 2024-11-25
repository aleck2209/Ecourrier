document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('.form-save-mail');
    const alertBox = document.getElementById('alert-box');
    const confirmExit = document.getElementById('confirm-exit');
    const cancelExit = document.getElementById('cancel-exit');
    const navigationLinks = document.querySelectorAll('a');
    let isFormDirty = false;
    let redirectUrl = null; //STOCKE URL CIBLE APRES CONFIRMATION DE CHANGEMENT DE PAGE

    // MARQUER LE FORMULAIRE COMME MODIFIE LORSQU'UN CHAMP CHANGE
    forms.forEach(form => {  
        form.addEventListener('change', () => {
            isFormDirty = true;
        });
    })

    // EMPECHE LE CHANGEMENT ONGLET SI LE FORMULAIRE EST MODIFIE
    // window.addEventListener("beforeunload", (e) => {
    //     if (isFormDirty) {
    //         e.preventDefault();
    //         e.returnValue = "";
    //     }
    // });

    // GESTION DES CLICS SUR LES LIENS INTERNES DE APPLICATION
    navigationLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            if(isFormDirty) {
                e.preventDefault(); //EMPECHE LA NAVIGATION IMMEDIATE
                redirectUrl = e.target.href; //CAPTURE URL IMMEDIATE
                alertBox.classList.remove('alert-hidden');
            }
        });
    });

    // ACTION DE ALERTE
    confirmExit.addEventListener('click', () => {
        alertBox.classList.add("alert-hidden");
        if(redirectUrl) {
            window.location.href = redirectUrl //REDIRIGE VERS LA PAGE DEMANDEE
        }
    });

    cancelExit.addEventListener('click', () => {
        alertBox.classList.add("alert-hidden");
        redirectUrl = null; //REINITIALISE URL CIBLE SI UTILISATEUR ANNULE
    })
})