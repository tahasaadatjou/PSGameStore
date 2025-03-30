<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
$page_title = "Register - PlayStation Game Store";
$meta_description = "Create a new account at PlayStation Game Store.";
include 'includes/header.php';
?>

<div class="auth-container">
    <h2 class="section-title">Register</h2>
    <form id="register-form" class="auth-form">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirmPassword" required>
        </div>
        <button type="submit" class="btn btn-gradient">Register</button>
        <p>Already have an account? <a href="login-page.php">Login here</a></p>
    </form>
    <div id="register-message" class="auth-message"></div>
</div>

<script>
document.getElementById('register-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    fetch('includes/register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, email, password, confirmPassword })
    })
    .then(res => res.json())
    .then(data => {
        const messageDiv = document.getElementById('register-message');
        messageDiv.textContent = data.message;
        messageDiv.style.color = data.success ? 'green' : 'red';
        if (data.success) {
            setTimeout(() => location.href = 'index.php', 1000);
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>