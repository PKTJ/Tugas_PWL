// Function to validate the form
function validateForm() {
    // Reset error messages
    clearErrors();
    
    let valid = true;
    
    // Get form elements
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const fullName = document.getElementById('full_name').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    // Username validation
    if (username.trim() === '') {
        displayError('username-error', 'Username is required');
        valid = false;
    } else if (!/^[a-zA-Z0-9_]+$/.test(username)) {
        displayError('username-error', 'Username can only contain letters, numbers, and underscores');
        valid = false;
    }
    
    // Email validation
    if (email.trim() === '') {
        displayError('email-error', 'Email is required');
        valid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        displayError('email-error', 'Invalid email format');
        valid = false;
    }
    
    // Full name validation
    if (fullName.trim() === '') {
        displayError('full-name-error', 'Full name is required');
        valid = false;
    }
    
    // Password validation
    if (password.trim() === '') {
        displayError('password-error', 'Password is required');
        valid = false;
    } else if (password.length < 8) {
        displayError('password-error', 'Password must be at least 8 characters long');
        valid = false;
    }
    
    // Confirm password validation
    if (confirmPassword.trim() === '') {
        displayError('confirm-password-error', 'Please confirm your password');
        valid = false;
    } else if (password !== confirmPassword) {
        displayError('confirm-password-error', 'Passwords do not match');
        valid = false;
    }
    
    return valid;
}

// Function to display error message
function displayError(id, message) {
    const errorElement = document.getElementById(id);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

// Function to clear all error messages
function clearErrors() {
    const errorElements = document.querySelectorAll('.error-text');
    errorElements.forEach(element => {
        element.textContent = '';
        element.style.display = 'none';
    });
}

// Periksa kesalahan registrasi dari server (yang dilewatkan melalui sesi)
document.addEventListener('DOMContentLoaded', function() {
    // Implementasi untuk menampilkan kesalahan sisi server akan ada di sini
    // mengharuskan PHP untuk meneruskan kesalahan kembali ke halaman
});