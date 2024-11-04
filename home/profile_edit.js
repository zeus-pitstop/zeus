document.addEventListener('DOMContentLoaded', () => {
    // Handle profile picture preview
    const profilePicInput = document.getElementById('upload-pic');
    const profilePicImg = document.getElementById('profile-pic');

    profilePicInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                profilePicImg.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Optional: Form validation if needed
    const form = document.querySelector('form');
    form.addEventListener('submit', (event) => {
        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        
        if (!username || !email) {
            alert('Please fill out all required fields.');
            event.preventDefault();
        }
    });
});
