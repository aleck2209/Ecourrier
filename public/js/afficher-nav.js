// RECUPERATION DES PAGES
var pageAdmin = document.getElementById('page-admin');
var pageNotification = document.getElementById('page-notification');
var pageSaveMail = document.getElementById('page-save-mail');

// RECUPERATION DES NAV-BAR
var navAdmin = document.getElementById('nav-admin');
var navNotification = document.getElementById('nav-notification');
var navSaveMail = document.getElementById('nav-save-mail');

document.addEventListener('DOMContentLoaded', function() {
    if(pageAdmin) {
        navAdmin.style.display = "block";
        navNotification.style.display = "none";
        navSaveMail.style.display = "none";
    } else if(pageNotification) {
        navAdmin.style.display = "none";
        navNotification.style.display = "block";
        navSaveMail.style.display = "none";
    } else if(pageSaveMail) {
        navAdmin.style.display = "none";
        navNotification.style.display = "none";
        navSaveMail.style.display = "block";
    } else {
        document.getElementsByClassName('nav-bar').style.display = "none";
    }
});