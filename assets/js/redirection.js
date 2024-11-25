function redirection(btnId, localisation){
    document.getElementById(btnId).addEventListener("click", ()=>{
        window.location = localisation;
    });
}
window.redirection = redirection;