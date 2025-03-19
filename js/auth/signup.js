document.getElementById("signup").addEventListener("click", function() {
    let forename = document.getElementById("forename").value;
    let surname = document.getElementById("surname").value;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    let postData = {
        forename: forename,
        surname: surname,
        email: email,
        password: password
    };

    fetch("/includes/auth/signup.inc.php", { // Uhh yea
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
            window.location.href = `login?email=${data.email}`; // Redirect on success
        } else {
            console.error("Error:", data.message); //Temp error message display
            document.getElementById("responseMessage").innerText = data.message; //TODO: Display the error message on the webpage... Dexter UwU
        }
    })
    .catch(error => console.error("Error:", error));
    
});
