// Example of other general JavaScript functionalities

document.addEventListener("DOMContentLoaded", function() {
    console.log("Document is fully loaded and parsed");

    // Example function to show an alert
    function showAlert(message) {
        alert(message);
    }

    // Example event listener for a button click
    const exampleButton = document.getElementById("exampleButton");
    if (exampleButton) {
        exampleButton.addEventListener("click", function() {
            showAlert("Button clicked!");
        });
    }

    // Example function to handle form submission
    const exampleForm = document.getElementById("exampleForm");
    if (exampleForm) {
        exampleForm.addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission
            showAlert("Form submitted!");
        });
    }

    // Example function to toggle visibility of an element
    function toggleVisibility(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.style.display = (element.style.display === "none") ? "block" : "none";
        }
    }

    // Example event listener for a toggle button
    const toggleButton = document.getElementById("toggleButton");
    if (toggleButton) {
        toggleButton.addEventListener("click", function() {
            toggleVisibility("toggleElement");
        });
    }
});
