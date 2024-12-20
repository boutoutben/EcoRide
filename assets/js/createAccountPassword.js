import { show_hide } from './show-hidePassword';

// Add event listeners to icons (or buttons)
document.getElementById('password').addEventListener('click', () => {
    show_hide('password', 'create_account_password');
});
document.getElementById('comformPassword').addEventListener('click', () => {
    show_hide('comformPassword', 'create_account_passwordAgain');
});