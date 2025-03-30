<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$base_url = $protocol . "://$_SERVER[HTTP_HOST]" . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
?>

<footer class="modern-footer">
    <div class="footer-container">
        <div class="footer-links">
            <a href="<?php echo $base_url; ?>about.php">About</a>
            <a href="<?php echo $base_url; ?>contact.php">Contact</a>
            <a href="<?php echo $base_url; ?>privacy.php">Privacy Policy</a>
        </div>
        <div class="footer-copy">
            © <?php echo date('Y'); ?> PlayStation Game Store. All rights reserved.
        </div>
    </div>
</footer>

<!-- Alert Container -->
<div id="alert-container"></div>

<script src="<?php echo $base_url; ?>assets/js/script.js"></script>
</body>
</html>