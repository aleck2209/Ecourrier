document.addEventListener('DOMContentLoaded', error());

function error() {
    const message = document.getElementById('message');
    const alertBox = document.getElementById('alert-box');
    const confirmExit = document.getElementById('confirm-exit');

    if (message.textContent) {
        alertBox.classList.remove('alert-hidden');
    }

    confirmExit.addEventListener('click', () => {
        alertBox.classList.add("alert-hidden");
        message.textContent = ""
    });
}