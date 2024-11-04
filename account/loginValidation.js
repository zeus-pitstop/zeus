document.addEventListener("DOMContentLoaded", () => {
    // Validate if the login button exists
    const loginButton = document.querySelector("#loginButton");
    if (loginButton) {
        loginButton.addEventListener("click", () => {
            if (validateLoginForm()) {
                // Proceed with login logic if form is valid
            }
        });
    }
});

function validateLoginForm() {
    const username = document.getElementById('login-username').value;
    const password = document.getElementById('login-password').value;
    const errorMessage = document.createElement('div');
    errorMessage.className = 'error-message';

    // Clear previous error message
    document.querySelectorAll('.error-message').forEach(el => el.remove());

    if (username === "" || password === "") {
        errorMessage.textContent = "Username and password are required.";
        document.querySelector('form').appendChild(errorMessage);
        return false;
    }

    if (password.length < 6) {
        errorMessage.textContent = "Password must be at least 6 characters long.";
        document.querySelector('form').appendChild(errorMessage);
        return false;
    }

    return true;
}

document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordField = document.getElementById('login-password');
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);

    // Toggle the eye icon
    this.classList.toggle('fa-eye-slash');
});
