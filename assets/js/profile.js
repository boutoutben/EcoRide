import { show_hide } from './show-hidePassword';

// La fonction peut maintenant être utilisée directement
document.getElementById('password').addEventListener('click', () => {
    show_hide('password', 'passwordInput');
});
document.getElementById('newPassword').addEventListener('click', () => {
    show_hide('newPassword', 'newPasswordInput');
});