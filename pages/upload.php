<?php
session_start();

//Add this to any area where the user can perform account-related actions
if(isset($_SESSION["user_id"])){ //If they are logged in and have an expired session cookie force them to signout (same way roblox works :P)
    try{
        echo "test";
        require_once "../includes/db/dbh.inc.php";
        $query = "SELECT session_id, session_id_created_at FROM users WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$_SESSION["user_id"]]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(time() - strtotime($user["session_id_created_at"]) >= 2629743){
            header("Location: /includes/auth/logout.inc.php?session_expired=true");
        }
    }catch(Exception $e){
        return;
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload - Flikhost</title>

    <link rel="stylesheet" href="/css/pages/upload.css">
    <link rel="stylesheet" href="/css/shared/checkbox.css">

    <link rel="shortcut icon" href="/assets/favicon.png" type="image/x-icon">

    <link rel="prefetch" href="/assets/purple-mojave.jpg"/>
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
                <span style="text-align: center; margin-top: auto;" id="create"><a href="signup">create an account</a> and gain control over your uploads</span>
                <!-- temporary html elements so i can test -->
                <div class="cf-turnstile" data-sitekey="3x00000000000000000000FF" data-callback="javascriptCallback"></div>
                <span style="text-align: center; margin-top: auto;" id="tempupload"><button onclick="handleImage()">Upload</button></span>
                <span style="display: none;text-align: center; margin-top: auto;" id="uploadNotif">Image uploaded successfully!</span>
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

    <script src="/js/utils/file.js"></script>
    <script src="/js/utils/update.js"></script>
    <script>
if(<?php echo json_encode(!isset($_SESSION['user_session_id'])); ?>) {
    console.log("User not logged in");
    // If we want to redirect the user to the login page then uncomment the line below
    // Header("Location: login");
} else {
    console.log("User logged in");
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("create").style.display = "none";
    });
}

    </script>
    <script>
function handleImage() {

const element = document.getElementById("dropzone");
if (!element) {
  console.error("ERROR: #dropzone element not found!");
  return;
}

const style = window.getComputedStyle(element);
const bgImage = style.backgroundImage;
const imageUrl = bgImage.replace(/url\(["']?(.*?)["']?\)/, '$1');
const notif = document.getElementById("uploadNotif");


console.log("Extracted Image URL:", imageUrl);
console.log("Username:", "<?php echo $_SESSION['user_name'] ?? "Anon";  ?>");

fetch("/includes/utils/uploadImage.inc.php", {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify({
      image: imageUrl,
      imagename: imageName.substring(0, imageName.lastIndexOf(".")),
      user_id: <?php echo isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 'null'; ?>,
      username: "<?php echo $_SESSION['user_name']; ?>"
  })
})
.then(response => response.json())
.then(data => {
  console.log("Server Response:", data);
  if (data.success == "true" || data.success == true) {
      notif.style.display = "flex";
      notif.innerHTML = `Image uploaded successfully! You can view your image at &nbsp<a target='_blank' href='../image?image=${data.path}' style='cursor:pointer;'>this link</a>`;
  }else{
      notif.style.display = "flex";
      notif.innerHTML = "Image upload failed!";
  }
})
.catch(error => console.error("Fetch Error:", error));
}

window.handleImage = handleImage; // Ensure it's globally available - Dont know why this works but apparently it does

console.log("%cdafuq u here 4?", "color: #7289DA; -webkit-text-stroke: 2px black; font-size: 72px; font-weight: bold;");

document.addEventListener("dragover", function(event) {
  event.preventDefault();
});

document.addEventListener("drop", function(event) {
  if (!event.target.closest(".drop_zone")) {
      event.preventDefault();
  }
});

    </script>
</body>
</html>