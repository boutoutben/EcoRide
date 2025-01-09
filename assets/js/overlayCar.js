

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
    const body = document.querySelector("body");
    body.classList.add('freeze-scroll');
    overlay("#overlayNewCar");
})
document.addEventListener("click", (e) => {
    if (!document.querySelector("#overlayNewCar").contains(e.target) && e.target !== document.querySelector("#plus-btn")) {
        document.querySelector("#overlayNewCar").classList.remove("active");
    }
});

function handleCarChoice() {
    let newCarFields = document.querySelectorAll("#autreChoiceCar .input-place");
    if (carChoice.value === "other") {
        autreChoiceCar.classList.add("active");
        newCarFields.forEach(field => field.setAttribute("required", "required"));
    } else {
        autreChoiceCar.classList.remove("active");
        newCarFields.forEach(field => field.removeAttribute("required"));
    }
}

let carChoice = document.querySelector("#create_carpool_carChoice");
let autreChoiceCar = document.querySelector("#autreChoiceCar");

handleCarChoice();

// Add an event listener for changes
carChoice.addEventListener("change", handleCarChoice);






