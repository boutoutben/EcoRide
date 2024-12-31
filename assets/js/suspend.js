document.addEventListener("DOMContentLoaded", () => {
    const suspendSearch = document.getElementById("suspendSearch");
    if (suspendSearch) {
        suspendSearch.addEventListener("input", checkInput);
    }
});

document.addEventListener("click",(e)=> {
    const elementId = e.target.id;
    const parts = elementId.split("-");
    if(elementId.startsWith("suspend"))
    {
        const element = document.querySelector(`#${elementId}`);
        let condition = true;
        if(element.classList.contains("suspend"))
        {
            condition = false;
        }
        fetch("/suspend", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id: parts[1],condition: condition })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if(condition)
            {
                element.classList.add("suspend");
            }
            else{
                element.classList.remove("suspend");
            }
        })
        .catch(error => {
            console.error("Error deleting carpool:", error);
        });
    }
})

function checkInput(e) {
    const allUserUsername = document.querySelectorAll("#AllUserSupend .sub-block p:first-child");
    const allUser = document.querySelectorAll("#AllUserSupend .sub-block");

    allUserUsername.forEach((element, index) => {
        const parentBlock = allUser[index]; // Corresponding parent element
        if (!element.textContent.toLowerCase().includes(e.target.value.toLowerCase())) {
            parentBlock.style.display = "none"; // Hide the parent block
        } else {
            parentBlock.style.display = "block"; // Show the parent block
        }
    });
}