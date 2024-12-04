import { show_hide } from './show-hidePassword';

// La fonction peut maintenant être utilisée directement
document.getElementById('password').addEventListener('click', () => {
    show_hide('password', 'user_profile_password');
});
document.getElementById('newPassword').addEventListener('click', () => {
    show_hide('newPassword', 'user_profile_newPassword');
});