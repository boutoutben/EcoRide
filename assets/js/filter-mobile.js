const filter_btn = document.querySelector('#filter-btn');
const filter = document.querySelector("#filter")

filter_btn.addEventListener("click", ()=> {
    console.log("cc");
    filter.classList.toggle("active");
})

document.addEventListener("click", e => {
    if(!filter.contains(e.target)&& e.target !== filter_btn){
    filter.classList.remove("active");
    }
})