var pageAdmin = document.getElementById('page-admin');

var hearderAdmin = document.getElementById('header-admin');
var headerUser = document.getElementById('header-user');

document.addEventListener('DOMContentLoaded', function() {
    if(pageAdmin) {
        hearderAdmin.style.display = "block";
        headerUser.style.display = "none";
    } else {
        hearderAdmin.style.display = "none";
        headerUser.style.display = "flex";
    }
})