document.addEventListener("DOMContentLoaded", () => {
    const ecologiqueCheckbox = document.querySelector("#isEcologique > input[type='checkbox']");
    let check = "uncheck";
    
    if (ecologiqueCheckbox) {
        ecologiqueCheckbox.addEventListener("change", (event) => {
            check = event.target.checked ? "check" : "uncheck";
            location.reload();

            fetch("/carpool", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ checkValue: check }),
            })
        });
    } else {
        console.error("The checkbox element inside #isEcologique was not found.");
    }
});




