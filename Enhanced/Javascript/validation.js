function validateForm() {
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var role = document.getElementById('role').value;

    if (username.length < 3 || username.length > 20) {
        alert("Username must be between 3 and 20 characters.");
        return false;
    }

    var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{12,}$/;
    if (!passwordPattern.test(password)) {
        alert("Password must be at least 12 characters long and include at least one numeric digit, one uppercase letter, one lowercase letter, and one special character.");
        return false;
    }
    
    if (role === "") {
        alert("Please select a role.");
        return false;
    }
    return true;
}
