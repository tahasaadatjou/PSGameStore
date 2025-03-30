<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
$page_title = "Login - PlayStation Game Store";
$meta_description = "Log in to your PlayStation Game Store account.";
include 'includes/header.php';
?>

<div class="auth-container">
    <h2 class="section-title">Login</h2>
    <form id="login-form" class="auth-form">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-gradient">Login</button>
        <p>Don't have an account? <a href="register-page.php">Register here</a></p>
    </form>
    <div id="login-message" class="auth-message"></div>
</div>

<script>
document.getElementById('login-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    fetch('includes/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
    })
    .then(res => res.json())
    .then(data => {
        const messageDiv = document.getElementById('login-message');
        messageDiv.textContent = data.message;
        messageDiv.style.color = data.success ? 'green' : 'red';
        if (data.success) {
            setTimeout(() => location.href = 'index.php', 1000);
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>