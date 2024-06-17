function validateForm() {
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var role = document.getElementById('role').value;

    if (username.length < 3 || username.length > 20) {
        alert("Username must be between 3 and 20 characters.");
        return false;
    }

    var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;
    if (!passwordPattern.test(password)) {
        alert("Password must be between 6 and 20 characters and include at least one numeric digit, one uppercase, and one lowercase letter.");
        return false;
    }
    
    if (role === "") {
        alert("Please select a role.");
        return false;
    }
    return true;
}
