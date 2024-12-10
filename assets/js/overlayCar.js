

function overlay(overlayid){
    document.querySelector(overlayid).classList.toggle("active");
}
const allOverlay = document.querySelectorAll(".overlay");

function editBtn(nbCar)
{
    for (let i = 1; i <= nbCar; i++) {
        const editButton = document.querySelector("#edit" + i);
        const overlay = document.querySelector("#overlayEditCar" + i);

        // Toggle the overlay when clicking the button
        editButton.addEventListener("click", (e) => {
            overlay.classList.toggle("active");
        });

            // Close the overlay when clicking outside
         document.addEventListener("click", (e) => {
            if (!overlay.contains(e.target) && e.target !== editButton) {
                overlay.classList.remove("active");
            }
        });
    }
}
let nbTotalCar = document.querySelector("#nbTotalCar").textContent;
editBtn(nbTotalCar);


document.querySelector("#plus-btn").addEventListener("click", () =>{
    overlay("#overlayNewCar");
})
document.addEventListener("click", (e) => {
    if (!document.querySelector("#overlayNewCar").contains(e.target) && e.target !== document.querySelector("#plus-btn")) {
        document.querySelector("#overlayNewCar").classList.remove("active");
    }
    });





