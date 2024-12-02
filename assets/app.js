import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)

import { show_hide } from '../assets/js/show-hidePassword';

// La fonction peut maintenant être utilisée directement
document.getElementById('password').addEventListener('click', () => {
            show_hide('password', 'passwordInput');
});