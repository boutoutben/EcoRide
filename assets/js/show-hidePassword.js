export function show_hide(imageId, inputId) {
    const passwordImg = document.getElementById(imageId);
    const passwordInput = document.getElementById(inputId);

    if (passwordImg.getAttribute("src") === "img/hidden.png") {
        passwordImg.setAttribute("src", "img/eye.png");
        passwordInput.setAttribute("type", "text");
    } else {
        passwordImg.setAttribute("src", "img/hidden.png");
        passwordInput.setAttribute("type", "password");
    }
}