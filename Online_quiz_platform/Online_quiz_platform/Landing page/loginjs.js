function validateForm() {
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    if (email === "" || password === "") {
        alert("Please fill out all fields.");
        return false;
    }
    return true;
}
