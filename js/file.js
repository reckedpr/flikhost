
// ty mozilla for this wonderful function <3
function dropHandler(ev) {
  console.log("File(s) dropped");
  
  const dropZone = ev.target;
  // Prevent default behavior (Prevent file from being opened)
  ev.preventDefault();

  if (ev.dataTransfer.items) {
    // Use DataTransferItemList interface to access the file(s)
    [...ev.dataTransfer.items].forEach((item, i) => {
      // If dropped items aren't files, reject them
      if (item.kind === "file") {
        const file = item.getAsFile();
        console.log(`… file[${i}].name = ${file.name}`);
      }
    });
  } else {
    // Use DataTransfer interface to access the file(s)
    [...ev.dataTransfer.files].forEach((file, i) => {
      console.log(`… file[${i}].name = ${file.name}`);
    });
  }
  dropZone.classList.remove("hover");
}


function dragOverHandler(ev) {
  console.log("file(s) in drop area");

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

function validateFile(file_item) {
  var types = /(\.jpg|\.jpeg|\.gif|\.png)$/i; // stoled
  if (!re.exec(fname)) {
    //not supported

  } else {
    // do somethin dawg
  }
}