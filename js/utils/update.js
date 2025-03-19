function updateImage(image) {
    let elements = [
        document.getElementById("dropzoneHide"),
        document.getElementById("filetypeHide"),
        document.getElementById("lineHide"),
        document.getElementById("inputHide")
    ];

    let dropzone = document.querySelector(".drop_zone");

    elements.forEach(el => {
        if (el) el.style.display = "none";
    });

    if (dropzone) {
        dropzone.style.backgroundImage = `url(${image})`;
        dropzone.style.backgroundSize = "contain";
        dropzone.style.backgroundRepeat = "no-repeat";
        dropzone.style.backgroundPosition = "center";

        dropzone.style.borderStyle = "solid";
        dropzone.style.height = "100%";
    }

    dropzone.removeEventListener("dragover", dragOverHandler);
    dropzone.removeEventListener("drop", dropHandler);
    dropzone.removeEventListener("dragenter", dragEnterHandler);
    dropzone.removeEventListener("dragleave", dragLeaveHandler);

    dropzone.style.pointerEvents = "none";
}
