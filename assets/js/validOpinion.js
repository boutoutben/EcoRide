document.addEventListener("click",(e)=>{
    const elementId = e.target.id;
    if(elementId.startsWith("validBtn")||elementId.startsWith("noValidBtn"))
    {
        const parts = elementId.split("-");
        let valid = false;
        if(elementId.startsWith("validBtn"))
        {
            valid = true;
        }
        fetch("/validOpinion",{
            method:"POST",
            headers:{
                "Content-Type": "application/json"
            },
            body: JSON.stringify({id: parts[1], value: valid})
        })
        .then(response => {
            if (!response.ok) throw new Error('Il y a eu un problème');
                return response.json();
        })
        .then(data=> {
            location.reload();
        })
    }

    
})