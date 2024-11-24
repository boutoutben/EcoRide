const hamMenu = document.querySelector('#burggerMenu');
const hamMenuClose = document.querySelector("#burggerMenuClose")
const offScreenMenu = document.querySelector('#off-screen-menu');

hamMenu.addEventListener("click", () =>{
    offScreenMenu.classList.toggle("active");
})
hamMenuClose.addEventListener("click", ()=> {
    offScreenMenu.classList.remove("active");
})

document.addEventListener("click", e => {
    if(!offScreenMenu.contains(e.target)&& e.target !== hamMenu){
        console.log(e.target);
        offScreenMenu.classList.remove("active");
    }
})