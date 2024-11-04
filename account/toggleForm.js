function toggleCreateAccount() {
    const createAccountContainer = document.getElementById('createAccountContainer');
    const loginForm = document.getElementById('loginForm');

    // Toggle visibility
    if (createAccountContainer.style.display === "none") {
        createAccountContainer.style.display = "block";
        loginForm.style.display = "none";
    } else {
        createAccountContainer.style.display = "none";
        loginForm.style.display = "block";
    }
}
