document.getElementById("togglePassword").addEventListener("click", function () {
    let passwordInput = document.getElementById("password");
    let eyeOpen = document.querySelector(".eye-open");
    let eyeClosed = document.querySelector(".eye-closed");
    let eyeIcon = document.querySelector(".eye-icon");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        
        eyeOpen.style.opacity = "0";
        eyeClosed.style.opacity = "1";
    } else {
        passwordInput.type = "password";

        eyeOpen.style.opacity = "1";
        eyeClosed.style.opacity = "0";
    }

    eyeIcon.classList.toggle("show-password");
});