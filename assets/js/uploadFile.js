import $ from 'jquery';


let input = document.querySelector('.file-input'); // Target the file input

// Find the corresponding label next to the input
let label = input.nextElementSibling;
let labelVal = label.innerHTML;

let editPicture = document.querySelector("#editPicture");
let overlay = document.querySelector("#overlay");
const uploadedImage = document.querySelector('#uploaded_image');

document.querySelector('.file-input').addEventListener('change', function (e) {

    const file = this.files[0];

    // Check if a file is selected
    if (file) {
        const fileName = file.name;
        const fileExtension = fileName.split('.').pop().toLowerCase();
        const validExtensions = ["png", "jpg", "jpeg"];
        const maxSize = 2 * 1024 * 1024; // 2MB

        // Validate file extension
        if (!validExtensions.includes(fileExtension)) {
            uploadedImage.innerHTML = "Image invalide. Seuls les formats PNG, JPG, JPEG sont autorisés.";
            setTimeout(() => {
                    document.querySelector('#uploaded_image').innerHTML = "Parcourrir...";
            }, 3000);
            return;
        }

        // Validate file size
        if (file.size > maxSize) {
            uploadedImage.innerHTML = "La taille de l'image dépasse 2MB.";
            setTimeout(() => {
                    document.querySelector('#uploaded_image').innerHTML = "Parcourrir...";
            }, 3000);
            return;
        }

        // Show "Uploading" message
        uploadedImage.innerHTML = "Image Uploading...";

        // Create FormData and send AJAX request
        const formData = new FormData();
        formData.append("file", file);

        fetch('/userPictureUpload', {
            method: 'POST',
            body: formData,
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to upload image.');
                return response.json();
            })
            .then(data => {
                const profilePic = document.querySelector('#profile-pic');
                profilePic.src = `/img/${data.file}?t=${new Date().getTime()}`; 
                overlay.src = `/img/${data.file}?t=${new Date().getTime()}`;
                document.querySelector('#uploaded_image').innerHTML = "Image uploaded successfully!";
                setTimeout(() => {
                    input.value = "";
                    document.querySelector('#uploaded_image').innerHTML = "Parcourrir...";
                    overlay.classList.remove("active");
                }, 3000);
            })
            .catch(error => {
                console.log(error);
                document.querySelector('#uploaded_image').innerHTML = "Une erreur s'est produite lors de l'upload.";
            });
    }
});



editPicture.addEventListener("click", ()=>{
    overlay.classList.toggle("active");
})

document.addEventListener("click", e => {
    if(!overlay.contains(e.target)&& e.target !== editPicture){
        overlay.classList.remove("active");
    }
})