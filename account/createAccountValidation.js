function validateCreateAccountForm() {
    const username = document.getElementById('new-username').value;
    const email = document.getElementById('new-email').value;
    const password = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    const errorMessage = document.getElementById('createAccountErrorMessage');

    // Clear previous error message
    errorMessage.textContent = '';
    errorMessage.style.display = 'none';

    if (username === "" || email === "" || password === "" || confirmPassword === "") {
        errorMessage.textContent = "All fields are required.";
        errorMessage.style.display = "block";
        return false;
    }

    if (password.length < 6) {
        errorMessage.textContent = "Password must be at least 6 characters long.";
        errorMessage.style.display = "block";
        return false;
    }

    if (password !== confirmPassword) {
        errorMessage.textContent = "Passwords do not match.";
        errorMessage.style.display = "block";
        return false;
    }

    // Simulate account creation
    alert("Account created successfully!");
    return true;
}
