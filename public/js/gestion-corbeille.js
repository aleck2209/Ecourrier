document.addEventListener('DOMContentLoaded', displayFile())

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
