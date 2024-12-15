
function actionBtn(start,path,index){
    document.addEventListener("click",(e)=>{
    const elementId = e.target.id;
    if(elementId && elementId.startsWith(start)){
        const parts = elementId.split("-");
        const url = `${path}/?detail=${parts[index]}`;
        window.location.href = url;
    }
    })
}


actionBtn("participation","validParticipation",1);
actionBtn("yesBtn","validation",1);
actionBtn("noBtn","carpoolDetail",1);