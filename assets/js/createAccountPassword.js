import { show_hide } from './show-hidePassword';

// La fonction peut maintenant être utilisée directement
document.getElementById('password').addEventListener('click', () => {
    show_hide('password', 'create_account_password');
});
document.getElementById('comformPassword').addEventListener('click', () => {
    show_hide('comformPassword', 'create_account_passwordEgain');
});