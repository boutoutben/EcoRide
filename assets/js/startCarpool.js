document.addEventListener("click",(e)=>{
    const elementId = e.target.id;
    const parts = elementId.split("-");
    
    if (elementId.startsWith("cancelle")) {
        const element = document.querySelector(`#overlayCancelle-${parts[1]}`);
        element.classList.toggle("active");
    } 
    if(elementId.startsWith("yesBtn"))
    {
        fetch("/deleteCarpool", {
            method:"POST",
            headers:{
                "Content-Type": "application/json"
            },
            body: JSON.stringify({id: parts[1]})
        })
        .then(location.reload())
    }
    if(elementId.startsWith("noBtn")){
        location.reload();
    }
    
    if (elementId.startsWith("startBtn")) {
    const element = document.querySelector(`#${elementId}`);
    const parts = elementId.split("-");
    if (element.classList.contains("stopBtn")) {
        fetch("/startCarpool", {
            method:"POST",
            headers:{
                "Content-Type": "application/json"
            },
            body: JSON.stringify({id: parts[1], value: false})
        })
        .then(
            element.textContent = "Démarrer",
            element.classList.remove("stopBtn"),
        )
    } else {
        // Otherwise, set it to "stop"
        fetch("/startCarpool", {
            method:"POST",
            headers:{
                "Content-Type": "application/json"
            },
            body: JSON.stringify({id: parts[1], value: true})
        })
        .then(
            element.textContent = "Arrêter",
            element.classList.add("stopBtn"),
        )
    }

    
}
})