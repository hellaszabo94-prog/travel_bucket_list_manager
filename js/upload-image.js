const imageInput = document.getElementById("destinationImage");
const fileName = document.getElementById("fileName");

imageInput.addEventListener("change", () => {
    if (imageInput.files.length > 0) {
        fileName.textContent = "Selected file: " + imageInput.files[0].name;
    } else {
        fileName.textContent = "No file selected";
    }
});