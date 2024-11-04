document.getElementById('upload-pic').addEventListener('change', function(event) {
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById('profile-pic').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
});

function logout() {
    window.location.href = '../account/logout.php';
}

function deleteAccount() {
    if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
        // Submit a hidden form for account deletion
        document.getElementById('delete-account-form').submit();
    }
}

document.getElementById('upload-pic').addEventListener('change', function(event) {
    const form = document.getElementById('upload-form');
    form.submit();  // Submit the form when a new file is chosen
});
