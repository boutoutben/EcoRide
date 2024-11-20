
export function show_hide(elementId, inputId) {
    let passwordImg = document.getElementById(elementId);
    let passwordInput = document.getElementById(inputId);

    if (passwordImg.getAttribute("src") === "img/hidden.png") {
        passwordImg.setAttribute("src", "img/eye.png");
        passwordInput.setAttribute("type", "text");
    } else {
        passwordImg.setAttribute("src", "img/hidden.png");
        passwordInput.setAttribute("type", "password");
    }
}