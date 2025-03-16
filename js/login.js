document.getElementById("login").addEventListener("click", function() {
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    let postData = {
        email: email,
        password: password
    };

    fetch("includes/login.inc.php", { // Uhh yea
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(postData)
    })
    .then(response => response.text()) // Get raw text instead of JSON first just in case I forgotted something
    .then(text => {
        console.log("Raw response from server:", text); // Log response to console
        return JSON.parse(text); // Manually parse JSON
    })
    .then(data => {
        if (data.success) {
            window.location.href = `home.php?login=true`; // Redirect on success
        } else {
            console.error("Error:", data.message); //Temp error message display
            document.getElementById("responseMessage").innerText = data.message; //TODO: Display the error message on the webpage... Dexter UwU
        }
    })
    .catch(error => console.error("Error:", error));
    
});
