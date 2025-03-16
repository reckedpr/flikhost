<?php
session_start();




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload - Flikhost</title>

    <link rel="stylesheet" href="css/upload.css">
    <link rel="stylesheet" href="css/checkbox.css">

    <link rel="shortcut icon" href="assets/favicon.png" type="image/x-icon">

    <link rel="prefetch" href="assets/purple-mojave.jpg"/>
</head>
<body>
    <div class="centered">
        <div class="mainBox">
            <div class="innerContainer">
                <h2 style="padding-bottom: 20px;">Upload image</h2>
                <div class="uploadContainer">
                    <div class="dropzoneContainer">
                        <div id="dropzone" class="drop_zone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" ondragenter="dragEnterHandler(event);" ondragleave="dragLeaveHandler(event);">
                            <p id="dropzoneHide">Drag an image file here</p>
                        </div>
                        <p id="filetypeHide" style="padding-top: 10px;">Accepted file types (.png, .jpg, .gif)</p>
                    </div>
                    <div id="lineHide" class="line-with-text">
                        <span>OR</span>
                    </div>
                    <div id="inputHide">
                        <input type="file" id="fileInput" accept="image/png, image/jpeg, image/gif" style="display: none;" onchange="fileSelectHandler(event)">
                        <button onclick="document.getElementById('fileInput').click();">Choose Images</button>
                    </div>
                </div>
                <span style="text-align: center; margin-top: auto;"><a href="signup.html">create an account</a> and gain control over your uploads</span>
                <span style="text-align: center; margin-top: auto;" id="tempupload"><button onclick="handleImage()">Upload</button></span>
            </div>
        </div>
<!--         <div class="configContainer">
            <div style="display: inline; gap: 20px; width: 100%;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label class="checkbox-container">
                        <input class="custom-checkbox" type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                    <p>I agree this is not chizza p</p>
                </div>
                <div>
                    <input type="range" min="1" max="100" value="50">
                </div>
            </div>
            <button style="width: 30%; background-color: grey; box-shadow: none;" onclick="updateImage()">update</button>
        </div> -->
        
    </div>
<!--     <div class="bottomLeft">
        <p>made with ❤ by reckedpr <-- bro thinks he made this himself</p>
    </div> -->
    <script>

const generateUUID = () =>
  ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
    (
      c ^
      (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (c / 4)))
    ).toString(16)
  );

function handleImage() {
  const element = document.getElementById("dropzone"); // Target the element
  const style = window.getComputedStyle(element); // Get computed styles
  const bgImage = style.backgroundImage; // Get background image property
  
  // Extract the URL from background-image: url("your-image.png")
  const imageUrl = bgImage.replace(/url\(["']?(.*?)["']?\)/, '$1');
  
  console.log(imageUrl); // Outputs: your-image.png (or full path)
  

  fetch("includes/uploadImage.inc.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
        image: imageUrl, // Replace with real base64 data
        imagename: generateUUID()
    })
  })
  .then(response => response.json())
  .then(data => console.log(data));
}

        console.log("%c" + "dafuq u here 4?", "color: #7289DA; -webkit-text-stroke: 2px black; font-size: 72px; font-weight: bold;");

        document.addEventListener("dragover", function(event) {
            event.preventDefault();
        });

        document.addEventListener("drop", function(event) {
            if (!event.target.closest(".drop_zone")) {
                event.preventDefault();
            }
        });
    </script>

    <script src="js/file.js"></script>
    <script src="js/update.js"></script>
</body>
</html>