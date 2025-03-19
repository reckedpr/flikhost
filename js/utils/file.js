let imageName;

function dropHandler(ev) {
  console.log("File(s) dropped");

  const dropZone = ev.target;
  ev.preventDefault();

  if (ev.dataTransfer.items) {
    [...ev.dataTransfer.items].forEach((item, i) => {
      if (item.kind === "file") {
        const file = item.getAsFile();
        console.log(`Dropped file: ${file.name}`); // Log file name 
        imageName = file.name


        if (file.type.startsWith("image/")) {
          const reader = new FileReader();
          reader.onload = function (e) {
            updateImage(e.target.result);
          };
          reader.readAsDataURL(file);
        }
      }
    });
  } else {
    [...ev.dataTransfer.files].forEach((file, i) => {
      console.log(`Dropped file: ${file.name}`); // Log file name
      imageName = file.name

      if (file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = function (e) {
          updateImage(e.target.result);
        };
        reader.readAsDataURL(file);
      }
    });
  }

  dropZone.classList.remove("hover");
}

function dragOverHandler(ev) {
  console.log("File(s) in drop area");
  ev.preventDefault();
}

function dragEnterHandler(ev) {
  const dropZone = ev.target;  
  dropZone.classList.add("hover");
  ev.preventDefault();  
}

function dragLeaveHandler(ev) {
  const dropZone = ev.target;
  dropZone.classList.remove("hover");
}

function fileSelectHandler(ev) {
  const file = ev.target.files[0];

  if (file) {
    console.log(`Selected file: ${file.name}`); // Log file name
    imageName = file.name

    const reader = new FileReader();
    reader.onload = function(e) {
      updateImage(e.target.result);
    };
    reader.readAsDataURL(file);
  }
}
