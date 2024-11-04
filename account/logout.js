document.addEventListener("DOMContentLoaded", function() {
    const messageElement = document.getElementById("message");
    const spinnerElement = document.getElementById("spinner");

    // Check if the user is logged in
    const isLoggedIn = localStorage.getItem('isLoggedIn');

    if (isLoggedIn === 'true') {
        // User is logged in, show logout message and spinner
        messageElement.textContent = "You have been successfully logged out.";
        spinnerElement.style.display = "block";

        // Clear the login state
        localStorage.removeItem('isLoggedIn');

        // Redirect after a delay (3 seconds)
        setTimeout(() => {
            messageElement.textContent = "Redirecting to the homepage...";
            spinnerElement.style.display = "none";
            window.location.href = "index.php";
        }, 3000);
    } else {
        // No user is logged in, show error message
        messageElement.textContent = "No account is logged in.";
        spinnerElement.style.display = "none";

        // After 3 seconds, update the message and then redirect to the homepage
        setTimeout(() => {
            messageElement.textContent = "Redirecting to the homepage...";
            spinnerElement.style.display = "none";
            setTimeout(() => {
                window.location.href = "index.php";
            }, 1000); // Additional 2-second delay for better user experience
        }, 1000);
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const logoutText = document.querySelector(".logout-text");
    if (logoutText) {
      logoutText.textContent = "Logout";
    }
  });
  