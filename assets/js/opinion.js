document.addEventListener("click",(e)=>{
    const elementId = e.target.id; 
    if(elementId.startsWith("opinion"))
    {
        const parts = elementId.split("_");
        window.location.href = `/opinion?id=${parts[1]}`
    }
})