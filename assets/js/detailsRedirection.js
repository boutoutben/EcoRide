document.addEventListener("click",(e)=>{
    const elementId = e.target.id;
    if(elementId && elementId.startsWith("detail-btn")){
        const parts = elementId.split("-");
        const url = `/carpoolDetail?detail=${parts[2]}`;
        window.location.href = url;
    }
})