document.addEventListener('DOMContentLoaded', message());

function message() {
    const message = document.getElementById('message');
    const alertBox = document.getElementById('message-box');
    const confirmExit = document.getElementById('message-exit');

    if (message.textContent) {
        alertBox.classList.remove('message-hidden');
    }

    confirmExit.addEventListener('click', () => {
        alertBox.classList.add("message-hidden");
        message.textContent = ""
    });
}