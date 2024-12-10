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

document.querySelector("#plus-btn").addEventListener("click", () =>{
    overlay("#overlayNewCar");
})
document.addEventListener("click", (e) => {
    if (!document.querySelector("#overlayNewCar").contains(e.target) && e.target !== document.querySelector("#plus-btn")) {
        document.querySelector("#overlayNewCar").classList.remove("active");
    }
    });

/*document.querySelector("#new_car").addEventListener("submit", (e) => {
    e.preventDefault(); // Prevent the default form submission behavior
    const form = e.target;

    // Process form data or send it via AJAX
    if(document.querySelector(".form_error") == null){
         e.target.submit();
    }
       

    // Optionally: Submit the form programmatically or handle validation here
    // Example: form.submit(); if you want to allow form submission
});*/

window.editBtn = editBtn;


